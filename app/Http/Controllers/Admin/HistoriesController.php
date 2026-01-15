<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = History::with(['event'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('histories.index', compact('histories'));
    }

    public function show(History $history)
    {
        // biar user tidak bisa buka history orang lain
        abort_if($history->user_id !== auth()->id(), 403);

        $history->load(['event', 'details.tiket']);

        return view('histories.show', compact('history'));
    }
}
