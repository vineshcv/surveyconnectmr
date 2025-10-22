<?php

use App\Models\Participant;
use App\Models\Project;

if (!function_exists('participants_progress_report')) {
    /**
     * Get participant count by status for a project
     */
    function participants_progress_report($project_id, $status, $vendor_id = '')
    {
        $query = Participant::where('project_id', $project_id)
                           ->where('status', $status);
        
        if ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        }
        
        return $query->count();
    }
}

if (!function_exists('participants_progress_report_avg')) {
    /**
     * Get average LOI for completed participants
     */
    function participants_progress_report_avg($project_id, $status, $vendor_id = '')
    {
        $query = Participant::where('project_id', $project_id)
                           ->where('status', $status)
                           ->whereNotNull('loi');
        
        if ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        }
        
        $result = $query->selectRaw('COUNT(*) as totalcount, SUM(loi) as sumLoi')->first();
        
        return $result;
    }
}

if (!function_exists('get_loi')) {
    /**
     * Get total LOI for a project
     */
    function get_loi($project_id)
    {
        return Participant::where('project_id', $project_id)
                         ->where('status', 1)
                         ->sum('loi') ?? 0;
    }
}

if (!function_exists('get_total_project_status')) {
    /**
     * Get total participants for a project
     */
    function get_total_project_status($project_id, $status = null)
    {
        $query = Participant::where('project_id', $project_id);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->count();
    }
}

if (!function_exists('get_vendor_count')) {
    /**
     * Get vendor count for a project
     */
    function get_vendor_count($project_id)
    {
        return \App\Models\VendorQuota::where('project_id', $project_id)->count();
    }
}
