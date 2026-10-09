<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankReconciliationController extends Controller
{
    public function index()
    {
        return view('reconciliations.index');
    }

    public function process(Request $request)
    {
        return redirect()->route('reconciliations.index')->with('success', 'Rekonsiliasi bank berhasil diproses.');
    }
}
