<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CspLog;

class CspController extends Controller
{
    // RECEIVE CSP REPORTS
public function report(Request $request)
{
    // Read raw input
    $raw = $request->getContent();

    $data = json_decode($raw, true);

    // Handle CSP format
    $report = $data['csp-report'] ?? $data;

    if (!$report || !is_array($report)) {
        return response()->json(['status' => 'invalid']);
    }

    CspLog::create([
        'document_uri'       => $report['document-uri'] ?? null,
        'blocked_uri'        => $report['blocked-uri'] ?? null,
        'violated_directive' => $report['violated-directive'] ?? null,
        'effective_directive'=> $report['effective-directive'] ?? null,
        'ip_address'         => $request->ip(),
    ]);

    return response()->json(['status' => 'logged']);
}
    // DASHBOARD
    public function dashboard(Request $request)
    {
        $logs = CspLog::query()->orderBy('id', 'asc');

        if ($request->filled('ip')) {
            $logs->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        if ($request->filled('directive')) {
            $logs->where('violated_directive', 'like', '%' . $request->directive . '%');
        }

        return view('csp.dashboard', [
            'logs' => $logs->paginate(10)
        ]);
    }

    // CLEAR LOGS
    public function clear()
    {
        CspLog::truncate();

        return redirect()->back()->with('success', 'Logs cleared successfully');
    }
}