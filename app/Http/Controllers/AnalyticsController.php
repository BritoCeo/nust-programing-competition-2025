<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    private $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get disease trend analysis
     */
    public function getDiseaseTrends(Request $request): JsonResponse
    {
        $months = $request->input('months', 12);
        $trends = $this->analyticsService->getDiseaseTrends($months);

        return response()->json([
            'success' => true,
            'data' => $trends,
            'period' => "Last {$months} months"
        ]);
    }

    /**
     * Get patient outcome tracking
     */
    public function getPatientOutcomes(Request $request): JsonResponse
    {
        $months = $request->input('months', 6);
        $outcomes = $this->analyticsService->getPatientOutcomes($months);

        return response()->json([
            'success' => true,
            'data' => $outcomes,
            'period' => "Last {$months} months"
        ]);
    }

    /**
     * Get treatment effectiveness metrics
     */
    public function getTreatmentEffectiveness(): JsonResponse
    {
        $effectiveness = $this->analyticsService->getTreatmentEffectiveness();

        return response()->json([
            'success' => true,
            'data' => $effectiveness
        ]);
    }

    /**
     * Get predictive analytics
     */
    public function getPredictiveAnalytics(): JsonResponse
    {
        $analytics = $this->analyticsService->getPredictiveAnalytics();

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get system statistics
     */
    public function getSystemStatistics(): JsonResponse
    {
        $stats = $this->analyticsService->getSystemStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get dashboard analytics
     */
    public function getDashboardAnalytics(): JsonResponse
    {
        $analytics = $this->analyticsService->getDashboardAnalytics();

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get analytics report
     */
    public function getAnalyticsReport(Request $request): JsonResponse
    {
        $reportType = $request->input('type', 'comprehensive');
        $months = $request->input('months', 12);

        $report = [
            'generated_at' => now(),
            'period' => "Last {$months} months",
            'type' => $reportType
        ];

        switch ($reportType) {
            case 'disease_trends':
                $report['data'] = $this->analyticsService->getDiseaseTrends($months);
                break;
            case 'patient_outcomes':
                $report['data'] = $this->analyticsService->getPatientOutcomes($months);
                break;
            case 'treatment_effectiveness':
                $report['data'] = $this->analyticsService->getTreatmentEffectiveness();
                break;
            case 'predictive':
                $report['data'] = $this->analyticsService->getPredictiveAnalytics();
                break;
            case 'comprehensive':
            default:
                $report['data'] = [
                    'disease_trends' => $this->analyticsService->getDiseaseTrends($months),
                    'patient_outcomes' => $this->analyticsService->getPatientOutcomes($months),
                    'treatment_effectiveness' => $this->analyticsService->getTreatmentEffectiveness(),
                    'predictive_analytics' => $this->analyticsService->getPredictiveAnalytics(),
                    'system_statistics' => $this->analyticsService->getSystemStatistics()
                ];
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request): JsonResponse
    {
        $format = $request->input('format', 'json');
        $reportType = $request->input('type', 'comprehensive');
        $months = $request->input('months', 12);

        $data = $this->getAnalyticsReport($request)->getData(true);

        // In a real implementation, you would generate and return the file
        // For now, we'll return the data structure
        return response()->json([
            'success' => true,
            'message' => 'Analytics data prepared for export',
            'format' => $format,
            'data' => $data['data']
        ]);
    }
}
