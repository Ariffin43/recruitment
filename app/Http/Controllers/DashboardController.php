<?php

namespace App\Http\Controllers;

use App\Models\Fptk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function gm()
    {
        $summary = Fptk::selectRaw('status, COUNT(*) as total')
            ->whereIn('status', ['pending_gm', 'revisi_gm', 'approved_gm', 'ditolak'])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $recentFptks = Fptk::with('hod')
            ->latest()
            ->limit(5)
            ->get();

        return view('gm.pages.dashboard', compact('summary', 'recentFptks'));
    }

    public function hrd()
    {
        $summary = Fptk::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $recentFptks = Fptk::with(['hod', 'departemen'])
            ->latest()
            ->limit(5)
            ->get();

        $totalPelamar = User::where('role', 'pelamar')->count();

        return view('hrd.pages.dashboard', compact('summary', 'recentFptks', 'totalPelamar'));
    }

    public function hod()
    {
        $user = Auth::user();

        $myFptks = Fptk::where('hod_id', $user->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $recentFptks = Fptk::with('departemen')
            ->where('hod_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('hod.pages.dashboard', compact('myFptks', 'recentFptks'));
    }

    public function pelamar()
    {
        $user = Auth::user();

        return view('pelamar.pages.dashboard', compact('user'));
    }
}
