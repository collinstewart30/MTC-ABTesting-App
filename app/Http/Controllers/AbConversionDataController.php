<?php

namespace App\Http\Controllers;

use App\Models\AbConversionData;
use Illuminate\Http\Request;

class AbConversionDataController extends Controller
{
    public function index()
    {
        return response()->json(AbConversionData::all());
    }

    public function show($id)
    {
        return response()->json(AbConversionData::findOrFail($id));
    }
}
