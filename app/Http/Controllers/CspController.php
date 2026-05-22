<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CspLog;
use App\Models\BlockedDomain;
use Illuminate\Support\Facades\DB;

class CspController extends Controller
{
    // Receive CSP violation reports
    public function report(Request $request)
    {
        $raw = $request->getContent();
        $data = json_decode($raw, true);
        $report = $data['csp-report'] ?? $data;

        if (!$report || !is_array($report)) {
            return response()->json(['status' => 'invalid'], 400);
        }

        $blockedUri = $report['blocked-uri'] ?? null;
        
        if ($blockedUri) {
            $parsed = parse_url($blockedUri);
            $domain = $parsed['host'] ?? null;
            
            if ($domain) {
                $blockedDomain = BlockedDomain::where('domain', $domain)->first();
                if ($blockedDomain && $blockedDomain->action === 'block') {
                    $blockedDomain->incrementHitCount();
                }
            }
        }

        CspLog::create([
            'document_uri' => $report['document-uri'] ?? null,
            'blocked_uri' => $blockedUri,
            'violated_directive' => $report['violated-directive'] ?? null,
            'effective_directive' => $report['effective-directive'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_read' => false,
        ]);

        return response()->json(['status' => 'logged'], 200);
    }

    // Dashboard with statistics
    public function dashboard(Request $request)
    {
        $query = CspLog::query()->orderBy('id', 'desc');

        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        if ($request->filled('directive')) {
            $query->where('violated_directive', 'like', '%' . $request->directive . '%');
        }

        if ($request->filled('domain')) {
            $query->where('blocked_uri', 'like', '%' . $request->domain . '%');
        }

        $logs = $query->paginate(15);
        
        $blockedDomains = BlockedDomain::orderBy('hit_count', 'desc')->get();

        $stats = [
            'total' => CspLog::count(),
            'today' => CspLog::whereDate('created_at', today())->count(),
            'unique_ips' => CspLog::distinct('ip_address')->count('ip_address'),
            'unread' => CspLog::where('is_read', false)->count(),
            'blocked_domains' => BlockedDomain::count(),
        ];

        $topDomains = BlockedDomain::orderBy('hit_count', 'desc')->limit(5)->get();

        return view('csp.dashboard', compact('logs', 'blockedDomains', 'stats', 'topDomains'));
    }

    // Clear all logs
    public function clear()
    {
        CspLog::truncate();
        return redirect()->back()->with('success', 'All logs cleared successfully');
    }

    // Delete single log
    public function deleteLog($id)
    {
        $log = CspLog::findOrFail($id);
        $log->delete();
        return redirect()->back()->with('success', 'Log deleted successfully');
    }

    // Bulk delete logs
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No logs selected');
        }
        
        CspLog::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', count($ids) . ' logs deleted');
    }

    // Mark as read
    public function markRead(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            CspLog::where('is_read', false)->update(['is_read' => true]);
            return redirect()->back()->with('success', 'All logs marked as read');
        }
        
        CspLog::whereIn('id', $ids)->update(['is_read' => true]);
        return redirect()->back()->with('success', count($ids) . ' logs marked as read');
    }

    // Add blocked domain
    public function addDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|unique:csp_blocked_domains,domain',
            'action' => 'required|in:block,allow',
        ]);
        
        BlockedDomain::create($request->only(['domain', 'action', 'reason']));
        
        $message = $request->action === 'block' ? 'blocked' : 'allowed';
        return redirect()->back()->with('success', "Domain {$request->domain} has been {$message}");
    }

    // Remove blocked domain
    public function removeDomain($id)
    {
        $domain = BlockedDomain::findOrFail($id);
        $domainName = $domain->domain;
        $domain->delete();
        
        return redirect()->back()->with('success', "Domain {$domainName} removed");
    }

    // Export logs as CSV
    public function export()
    {
        $logs = CspLog::orderBy('id', 'desc')->get();
        
        $filename = 'csp_logs_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');
        
        fputcsv($handle, ['ID', 'Blocked URI', 'Directive', 'IP Address', 'Time']);
        
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->blocked_uri,
                $log->violated_directive,
                $log->ip_address,
                $log->created_at,
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}