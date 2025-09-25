<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportExportService
{
    /**
     * Export report as PDF
     */
    public function exportToPdf(Report $report): string
    {
        $data = $report->report_data;
        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $path = 'reports/pdf/' . $filename;
        
        // Generate PDF content (you can use libraries like DomPDF, TCPDF, etc.)
        $html = $this->generateReportHtml($report, $data);
        
        // For now, we'll create a simple text file as placeholder
        // In production, you would use a PDF library
        $content = $this->generateTextReport($report, $data);
        
        Storage::disk('public')->put($path, $content);
        
        return $path;
    }

    /**
     * Export report as Excel
     */
    public function exportToExcel(Report $report): string
    {
        $data = $report->report_data;
        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $path = 'reports/excel/' . $filename;
        
        // Generate Excel content (you can use libraries like PhpSpreadsheet)
        $content = $this->generateExcelReport($report, $data);
        
        Storage::disk('public')->put($path, $content);
        
        return $path;
    }

    /**
     * Export report as CSV
     */
    public function exportToCsv(Report $report): string
    {
        $data = $report->report_data;
        $filename = 'report_' . $report->id . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $path = 'reports/csv/' . $filename;
        
        $content = $this->generateCsvReport($report, $data);
        
        Storage::disk('public')->put($path, $content);
        
        return $path;
    }

    /**
     * Generate HTML report
     */
    private function generateReportHtml(Report $report, array $data): string
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>' . $report->report_name . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .section { margin-bottom: 20px; }
                .section h3 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . $report->report_name . '</h1>
                <p>Generated on: ' . $report->created_at->format('M d, Y H:i:s') . '</p>
            </div>
            
            <div class="section">
                <h3>Report Summary</h3>
                <p>' . $report->description . '</p>
            </div>
            
            <div class="section">
                <h3>Report Data</h3>
                <pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Generate text report
     */
    private function generateTextReport(Report $report, array $data): string
    {
        $content = "MEDICAL EXPERT SYSTEM REPORT\n";
        $content .= "============================\n\n";
        $content .= "Report Name: " . $report->report_name . "\n";
        $content .= "Generated On: " . $report->created_at->format('M d, Y H:i:s') . "\n";
        $content .= "Description: " . $report->description . "\n\n";
        
        $content .= "REPORT DATA:\n";
        $content .= "============\n";
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $content .= $key . ":\n";
                foreach ($value as $subKey => $subValue) {
                    $content .= "  " . $subKey . ": " . $subValue . "\n";
                }
            } else {
                $content .= $key . ": " . $value . "\n";
            }
        }
        
        return $content;
    }

    /**
     * Generate Excel report
     */
    private function generateExcelReport(Report $report, array $data): string
    {
        // This is a placeholder - in production you would use PhpSpreadsheet
        $content = "Report Name,Value\n";
        $content .= "Report Name," . $report->report_name . "\n";
        $content .= "Generated On," . $report->created_at->format('M d, Y H:i:s') . "\n";
        $content .= "Description," . $report->description . "\n";
        
        foreach ($data as $key => $value) {
            $content .= $key . "," . (is_array($value) ? json_encode($value) : $value) . "\n";
        }
        
        return $content;
    }

    /**
     * Generate CSV report
     */
    private function generateCsvReport(Report $report, array $data): string
    {
        $content = "Report Name,Value\n";
        $content .= "Report Name," . $report->report_name . "\n";
        $content .= "Generated On," . $report->created_at->format('M d, Y H:i:s') . "\n";
        $content .= "Description," . $report->description . "\n";
        
        foreach ($data as $key => $value) {
            $content .= $key . "," . (is_array($value) ? json_encode($value) : $value) . "\n";
        }
        
        return $content;
    }

    /**
     * Get export file URL
     */
    public function getExportUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Clean up old export files
     */
    public function cleanupOldExports(int $daysOld = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($daysOld);
        $deletedCount = 0;
        
        $directories = ['reports/pdf', 'reports/excel', 'reports/csv'];
        
        foreach ($directories as $directory) {
            $files = Storage::disk('public')->files($directory);
            
            foreach ($files as $file) {
                $lastModified = Storage::disk('public')->lastModified($file);
                
                if ($lastModified < $cutoffDate->timestamp) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                }
            }
        }
        
        return $deletedCount;
    }
}
