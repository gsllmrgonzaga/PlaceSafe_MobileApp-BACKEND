<?php

namespace App\Http\Controllers;

use App\Models\MapManagement;
use App\Models\PatientManagement;
use App\Models\LocationsManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MapController extends Controller
{
    public function searchPlace(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'locations_name'=>'required',          
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()->first(),
                'success' => false
            ],422);  
        } 
        
        $searchVal = strtolower($request->locations_name);
       
        $result = LocationsManagement::select('patients.pat_location_name','locations.locations_latitude','locations.locations_longitude', 'locations.locations_name as pat_location_name')
        ->leftJoin('patients', 'patients.pat_location_name', 'LIKE', 'locations.locations_name')
        ->addSelect(['total_confirmed_cases_no' => DB::table('patients as patientsB')->selectRaw('COUNT(*)')
            ->whereColumn('patients.pat_location_name', '=', 'patientsB.pat_location_name')])
        ->distinct('patients.pat_location_name')
        ->where('locations.locations_name','like', "%".$searchVal."%")
        ->get();       
  
        return response()->json([
            'message' => 'Location successfully locate.',
            'data' => $result
        ],200);     
    }

    public function searchPlaceInfoActive(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'locations_name'=>'required',          
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()->first(),
                'success' => false
            ],422);  
        } 
        
        $searchVal = strtolower($request->locations_name);
       
        $result = LocationsManagement::select('patients.pat_location_name','locations.locations_latitude','locations.locations_longitude', 'locations.locations_name as pat_location_name')
        ->leftJoin('patients', 'patients.pat_location_name', 'LIKE', 'locations.locations_name')
        ->addSelect(['total_active_cases_no' => DB::table('patients')
            ->selectRaw('COUNT(*)')      
            ->where('patients.case_status','=','active')      
            ->whereColumn('locations.locations_name', '=', 'patients.pat_location_name')        
            ]) 
        ->where('locations.locations_name','like', "%".$searchVal."%")
        ->distinct('patients.pat_location_name') 
        ->get();       

        return response()->json([
            'message' => 'Number of active cases in this area successfully retrieved.',
            'data' => $result
        ],200);       
   
    }

    public function searchPlaceInfoRecovered(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'locations_name'=>'required',          
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()->first(),
                'success' => false
            ],422);  
        } 
        
        $searchVal = strtolower($request->locations_name);
       
        $result = LocationsManagement::select('patients.pat_location_name','locations.locations_latitude','locations.locations_longitude', 'locations.locations_name as pat_location_name')
        ->leftJoin('patients', 'patients.pat_location_name', 'LIKE', 'locations.locations_name')
        ->addSelect(['total_recovered_cases_no' => DB::table('patients')
            ->selectRaw('COUNT(*)')      
            ->where('patients.case_status','=','recovered')      
            ->whereColumn('locations.locations_name', '=', 'patients.pat_location_name')        
            ]) 
        ->where('locations.locations_name','like', "%".$searchVal."%")
        ->distinct('patients.pat_location_name') 
        ->get();       

            return response()->json([
                'message' => 'Number of recovered cases in this area successfully retrieved.',
                'data' => $result
            ],200);     
    }

    public function searchPlaceInfoDead(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'locations_name'=>'required',          
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()->first(),
                'success' => false
            ],422);  
        } 
        
        $searchVal = strtolower($request->locations_name);
       
        $result = LocationsManagement::select('patients.pat_location_name','locations.locations_latitude','locations.locations_longitude', 'locations.locations_name as pat_location_name')
        ->leftJoin('patients', 'patients.pat_location_name', 'LIKE', 'locations.locations_name')
        ->addSelect(['total_deaths_cases_no' => DB::table('patients')
            ->selectRaw('COUNT(*)')      
            ->where('patients.case_status','=','died')      
            ->whereColumn('locations.locations_name', '=', 'patients.pat_location_name')        
            ]) 
        ->where('locations.locations_name','like', "%".$searchVal."%")
        ->distinct('patients.pat_location_name') 
        ->get();  

        return response()->json([
            'message' => 'Number of death cases in this area successfully retrieved.',
            'data' => $result
        ],200);       
    }
}
