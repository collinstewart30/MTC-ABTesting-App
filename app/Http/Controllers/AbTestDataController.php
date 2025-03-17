<?php

namespace App\Http\Controllers;

use App\Models\AbTestData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbTestDataController extends Controller
{
    public function index()
    {
        return response()->json(AbTestData::all());
    }

    public function show($user_session_id)
    {
        return response()->json(AbTestData::findOrFail($user_session_id));
    }

    public function getAbTestsDistinct()
    {
        return DB::table('ab_test_data')->select('test_id')->distinct()->get();
    }

    public function showAbTest($test_id)
    {
        return DB::table('ab_test_data')->select('*')->where('test_id', $test_id)->get();
    }
}

