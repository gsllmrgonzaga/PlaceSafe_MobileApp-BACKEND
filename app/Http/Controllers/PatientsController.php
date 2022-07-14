<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientManagement;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{

    public function getTotalActiveCases()
    {
        $getActive = PatientManagement::where('case_status' , "active")->count();

        return response()->json([
            'message' => 'Active cases fetched successfully.',
            'data' => $getActive
        ],200);
    }

    public function getTotalRecoveredCases()
    {
        $getRecoveries = PatientManagement::where('case_status' , "recovered")->count();

        return response()->json([
            'message' => 'Recovered cases fetched successfully.',
            'data' => $getRecoveries
        ],200);
    }

    public function getTotalDeathCases()
    {
        $getDeaths = PatientManagement::where('case_status' , "died")->count();

        return response()->json([
            'message' => 'Death cases fetched successfully.',
            'data' => $getDeaths
        ],200);
    }

    public function getTotalConfirmedCases()
    {
        $getConfirmed = PatientManagement::all()->count();

        return response()->json([
            'message' => 'Confirmed cases fetched successfully.',
            'data' => $getConfirmed
        ],200);
    }

}