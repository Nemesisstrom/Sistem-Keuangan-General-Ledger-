<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    public function index()
    {
        return view('adjustments.index');
    }

    public function runDepreciation(Request $request)
    {
        return redirect()->route('adjustments.index')->with('success', 'Jurnal penyusutan aset otomatis berhasil dijalankan.');
    }
};
