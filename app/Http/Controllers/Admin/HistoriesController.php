<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = Order::with(['user','event'])
            ->latest()
            ->get();

        return view('admin.history.index', compact('histories'));
    }

    public function show($id)
    {
        $history = Order::with(['user','event','details.tiket'])
            ->findOrFail($id);

        return view('admin.history.show', compact('history'));
    }
}
