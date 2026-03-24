<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/DisputeController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $query = Dispute::with(['booking.customer', 'booking.provider.user', 'raisedBy'])
            ->latest();

        if ($request->filled('status'))
            $query->where('status', $request->status);

        $disputes = $query->paginate(20)->withQueryString();

        $counts = [
            'open'         => Dispute::where('status', 'open')->count(),
            'under_review' => Dispute::where('status', 'under_review')->count(),
            'resolved'     => Dispute::where('status', 'resolved')->count(),
            'closed'       => Dispute::where('status', 'closed')->count(),
        ];

        return view('admin.disputes.index', compact('disputes', 'counts'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load([
            'booking.customer',
            'booking.provider.user',
            'booking.service',
            'raisedBy',
            'resolvedBy',
        ]);

        return view('admin.disputes.show', compact('dispute'));
    }

    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate([
            'resolution'  => 'required|string',
            'admin_notes' => 'nullable|string',
        ]);

        $dispute->update([
            'status'      => 'resolved',
            'resolution'  => $request->resolution,
            'admin_notes' => $request->admin_notes,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Dispute resolved successfully.');
    }

    public function close(Dispute $dispute)
    {
        $dispute->update(['status' => 'closed']);
        return back()->with('success', 'Dispute closed.');
    }
}