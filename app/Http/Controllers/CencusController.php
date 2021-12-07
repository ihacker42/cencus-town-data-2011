<?php

namespace App\Http\Controllers;

use DB;
use Str;
use App\Model\CensusData;
use Illuminate\Http\Request;

class CencusController extends Controller
{
    public function __construct() {}

    public function index() {
        // $villages =   CensusData::where("state_code","!=",0)->where("district_code","!=",0)->where("tehsil_code","!=",0)->where("village_code","!=",0)->where("status",0)->take(50000)->get();
        
        // foreach($villages as $row) {
        //     DB::table("cencus_villages")->insertGetId([
        //         "name"          =>  $row->name,
        //         "tehsil_id"     =>  $row->tehsil_code,
        //         "district_id"   =>  $row->district_code,
        //         "state_id"      =>  $row->state_code,
        //     ]);
        //     CensusData::where("id",$row->id)->update(["status" => 1]);
        // }

        return response()->json([]);

        // $data       =   CensusData::select("*")->where("status",0)->chunk(10000, function($row) {
        //     foreach($data as $t) {
        //         if($t->state_code > 0 && $t->district_code == 0 && $t->tehsil_code == 0 && $t->village_code == 0) {
        //             "";
        //         }
        //         else if($t->state_code > 0 && $t->district_code > 0 && $t->tehsil_code == 0 && $t->village_code == 0) {
        //             "";
        //         }
        //         else if($t->state_code > 0 && $t->district_code > 0 && $t->tehsil_code > 0 && $t->village_code == 0) {
        //             "";
        //         }
        //         else if($t->state_code > 0 && $t->district_code > 0 && $t->tehsil_code > 0 && $t->village_code > 0) {
        //             "";
        //         }
        //     }
        // });
        
        return;
    }
}