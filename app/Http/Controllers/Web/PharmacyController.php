<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Models\DrugAdministration;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyController extends Controller
{
    /**
     * Display pharmacy dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get pharmacy statistics
        $stats = [
            'total_drugs' => Drug::count(),
            'low_stock_drugs' => Drug::where('stock_quantity', '<=', 10)->count(),
            'pending_prescriptions' => DrugAdministration::where('status', 'prescribed')->count(),
            'dispensed_today' => DrugAdministration::where('status', 'dispensed')
                ->whereDate('dispense_date', today())->count(),
            'total_prescriptions' => DrugAdministration::count(),
            'active_treatments' => DrugAdministration::where('status', 'active')->count()
        ];

        // Get recent prescriptions
        $recentPrescriptions = DrugAdministration::with(['patient', 'doctor', 'drug'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get low stock drugs
        $lowStockDrugs = Drug::where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity')
            ->get();

        return view('pharmacy.index', compact('stats', 'recentPrescriptions', 'lowStockDrugs'));
    }

    /**
     * Get all drugs
     */
    public function getDrugs(Request $request)
    {
        $query = Drug::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Apply stock filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock_quantity', '<=', 10);
                    break;
                case 'out':
                    $query->where('stock_quantity', 0);
                    break;
                case 'available':
                    $query->where('stock_quantity', '>', 10);
                    break;
            }
        }

        $drugs = $query->orderBy('name')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $drugs
        ]);
    }

    /**
     * Get drug inventory
     */
    public function getInventory(Request $request)
    {
        $query = Drug::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%");
            });
        }

        // Apply stock filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock_quantity', '<=', 10);
                    break;
                case 'out':
                    $query->where('stock_quantity', 0);
                    break;
                case 'available':
                    $query->where('stock_quantity', '>', 10);
                    break;
            }
        }

        $drugs = $query->orderBy('stock_quantity')->get();

        return response()->json([
            'success' => true,
            'data' => $drugs
        ]);
    }

    /**
     * Dispense drug
     */
    public function dispenseDrug(Request $request)
    {
        $request->validate([
            'drug_id' => 'required|exists:drugs,id',
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'instructions' => 'nullable|string|max:1000'
        ]);

        $drug = Drug::findOrFail($request->drug_id);

        // Check stock availability
        if ($drug->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $drug->stock_quantity
            ], 400);
        }

        // Create drug administration record
        $drugAdministration = DrugAdministration::create([
            'drug_id' => $request->drug_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'pharmacist_id' => Auth::id(),
            'quantity' => $request->quantity,
            'dosage' => $request->dosage,
            'frequency' => $request->frequency,
            'duration' => $request->duration,
            'instructions' => $request->instructions,
            'status' => 'dispensed',
            'dispense_date' => now()
        ]);

        // Update drug stock
        $drug->decrement('stock_quantity', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Drug dispensed successfully',
            'data' => $drugAdministration
        ]);
    }

    /**
     * Get prescriptions
     */
    public function getPrescriptions(Request $request)
    {
        $query = DrugAdministration::with(['patient', 'doctor', 'drug', 'pharmacist']);

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('drug', function ($drugQuery) use ($search) {
                    $drugQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Apply date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $prescriptions = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Fulfill prescription
     */
    public function fulfillPrescription(Request $request, DrugAdministration $prescription)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000'
        ]);

        $drug = $prescription->drug;

        // Check stock availability
        if ($drug->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $drug->stock_quantity
            ], 400);
        }

        // Update prescription
        $prescription->update([
            'quantity' => $request->quantity,
            'status' => 'dispensed',
            'dispense_date' => now(),
            'notes' => $request->notes,
            'pharmacist_id' => Auth::id()
        ]);

        // Update drug stock
        $drug->decrement('stock_quantity', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Prescription fulfilled successfully',
            'data' => $prescription
        ]);
    }

    /**
     * Get drug categories
     */
    public function getCategories()
    {
        $categories = Drug::distinct()->pluck('category')->filter()->values();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get pharmacy statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total_drugs' => Drug::count(),
            'low_stock_drugs' => Drug::where('stock_quantity', '<=', 10)->count(),
            'out_of_stock_drugs' => Drug::where('stock_quantity', 0)->count(),
            'pending_prescriptions' => DrugAdministration::where('status', 'prescribed')->count(),
            'dispensed_today' => DrugAdministration::where('status', 'dispensed')
                ->whereDate('dispense_date', today())->count(),
            'dispensed_this_month' => DrugAdministration::where('status', 'dispensed')
                ->whereMonth('dispense_date', now()->month)->count(),
            'total_prescriptions' => DrugAdministration::count(),
            'active_treatments' => DrugAdministration::where('status', 'active')->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
