<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\notification;
use App\Models\PatientManagement;
use Illuminate\Support\Facades\DB;


class MinuteUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $res = PatientManagement::where('province','=','CEBU')->count();
        $resRec = PatientManagement::where('case_status','=','RECOVERED')->where('province','=','CEBU')->count();
        $resDie = PatientManagement::where('case_status','=','DIED')->where('province','=','CEBU')->count();
        $resAct = PatientManagement::where('case_status','=','ACTIVE')->where('province','=','CEBU')->count();

        $res1 = PatientManagement::where('province','=','BOHOL')->count();
        $resRec1 = PatientManagement::where('case_status','=','RECOVERED')->where('province','=','BOHOL')->count();
        $resDie1 = PatientManagement::where('case_status','=','DIED')->where('province','=','BOHOL')->count();
        $resAct1 = PatientManagement::where('case_status','=','ACTIVE')->where('province','=','BOHOL')->count();

        $res2 = PatientManagement::where('province','=','SIQUIJOR')->count();
        $resRec2 = PatientManagement::where('case_status','=','RECOVERED')->where('province','=','SIQUIJOR')->count();
        $resDie2 = PatientManagement::where('case_status','=','DIED')->where('province','=','SIQUIJOR')->count();
        $resAct2 = PatientManagement::where('case_status','=','ACTIVE')->where('province','=','SIQUIJOR')->count();

        $res3 = PatientManagement::where('province','=','NEGROS ORIENTAL')->count();
        $resRec3 = PatientManagement::where('case_status','=','RECOVERED')->where('province','=','NEGROS ORIENTAL')->count();
        $resDie3 = PatientManagement::where('case_status','=','DIED')->where('province','=','NEGROS ORIENTAL')->count();
        $resAct3 = PatientManagement::where('case_status','=','ACTIVE')->where('province','=','NEGROS ORIENTAL')->count();

        $user= notification::all();
        foreach ($user as $all)
        {
            Mail::raw("\n-------------------------------------------------------------------------\n*******COVID-19 cases in Region 7*******\n-------------------------------------------------------------------------\nProvince: CEBU\nTotal number of cases: $res\nRecovered: $resRec\nDied: $resDie\nActive: $resAct
            \n-------------------------------------------------------------------------\nProvince: BOHOL\nTotal number of cases: $res1\nRecovered: $resRec1\nDied: $resDie1\nActive: $resAct1
            \n-------------------------------------------------------------------------\nProvince: SIQUIJOR\nTotal number of cases: $res2\nRecovered: $resRec2\nDied: $resDie2\nActive: $resAct2
            \n-------------------------------------------------------------------------\nProvince: NEGROS ORIENTAL\nTotal number of cases: $res3\nRecovered: $resRec3\nDied: $resDie3\nActive: $resAct3", function($message) use ($all)
            {
                $message->from('luxelmeninalucy@gmail.com');
                $message->to($all->email)->subject('PlaceSafe: Region 7 COVID-19 update');
            });
        }
        $this->info('Minute Update has been send successful');
    }
}