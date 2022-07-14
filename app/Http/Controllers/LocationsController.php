<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocationsManagement;
use App\Models\PatientManagement;
use Illuminate\Support\Facades\DB;
use App\Models\MapManagement;
use Illuminate\Support\Facades\Validator;


class LocationsController extends Controller
{

    public function getListFilterLeastCases()
    {
        $leastCasesAreas = DB::table('patients')
             ->select(DB::raw('count(*) AS total_cases, pat_location_name'))
             ->having('total_cases','<=',50)
             ->groupBy('pat_location_name')
             ->get();

        return response()->json([
            'message' => 'Areas with least cases of coronavirus.',
            'data' => $leastCasesAreas
        ],200); 
    }
    
    public function getListFilterMostCases()
    {
        $mostCasesAreas = DB::table('patients')
             ->select(DB::raw('count(*) AS total_cases, pat_location_name'))
             ->having('total_cases','>',50)
             ->groupBy('pat_location_name')
             ->get();

        return response()->json([
            'message' => 'Areas with most cases of coronavirus.',
            'data' => $mostCasesAreas
        ],200); 
    
    }

    function getLatestDate()
    {
        $getLatest = PatientManagement::select('created_at')
            ->latest('created_at')
            ->orderBy('created_at')
            ->first();

        $formattedDate = $getLatest->created_at->isoFormat('MMMM DD, Y');

            return response()->json([
                'message' => 'Date retrieved.',
                'data' => $formattedDate
            ],200);     

    }

    public function getPlace()
    {
        $result = PatientManagement::select('patients.pat_location_name','locations.locations_latitude','locations.locations_longitude', 'locations.locations_name as pat_location_name')
                ->leftJoin('locations', 'locations.locations_name', 'LIKE', 'patients.pat_location_name')
                ->addSelect(['active_cases_no' => DB::table('patients')
                ->selectRaw('COUNT(*)')
                    ->where('patients.case_status','=','active') 
                    ->whereColumn('patients.pat_location_name', '=', 'locations.locations_name')])
                
                ->addSelect(['recovered_cases_no' => DB::table('patients')
                ->selectRaw('COUNT(*)')
                    ->where('patients.case_status','=','recovered') 
                    ->whereColumn('patients.pat_location_name', '=', 'locations.locations_name')])
                
                ->addSelect(['death_cases_no' => DB::table('patients')
                    ->selectRaw('COUNT(*)')
                        ->where('patients.case_status','=','died') 
                        ->whereColumn('patients.pat_location_name', '=', 'locations.locations_name')])

                ->addSelect(['total_confirmed_cases_no' => DB::table('patients as patientsB')->selectRaw('COUNT(*)')
                        ->whereColumn('patients.pat_location_name', '=', 'patientsB.pat_location_name')])
                    ->distinct('patients.pat_location_name')
                ->get();       

        return response()->json([
            'message' => 'Data displayed successfully.',
            'data' => $result
        ],200);      
    }


}