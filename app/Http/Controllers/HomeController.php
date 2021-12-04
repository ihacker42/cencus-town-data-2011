<?php

namespace App\Http\Controllers;

use DB;
use Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('home');
    }

    public function insertLocations() {
        $locations  =   DB::table("locations")->where("status",0)->get();

        $i  =   0;
        $state_id   =   0;
        $dist_id    =   0;
        $tehsil_id  =   0;
        foreach($locations as $location) {
            if($location->level == "STATE") {
                $slug   =   Str::slug($location->name);
                $count  =   DB::table("states")->where("slug",$slug)->count();
                if($count)
                    $slug   =   "$slug-$count";
                $state_id   =   DB::table("states")->insertGetId([
                    "name"  =>  $location->name,
                    "slug"  =>  $slug,
                ]);
                $i++;
            } else if($location->level == "DISTRICT") {
                $slug   =   Str::slug($location->name);
                $count  =   DB::table("districts")->where("slug",$slug)->count();
                if($count)
                    $slug   =   "$slug-$count";
                $dist_id   =   DB::table("districts")->insertGetId([
                    "name"      =>  $location->name,
                    "slug"      =>  $slug,
                    "state_id"  =>  $state_id,
                ]);
                $i++;
            } else if($location->level == "TOWN") {
                $slug   =   Str::slug($location->name);
                $count  =   DB::table("tehsils")->where("slug",$slug)->count();
                if($count)
                    $slug   =   "$slug-$count";
                $tehsil_id   =   DB::table("tehsils")->insertGetId([
                    "name"          =>  $location->name,
                    "slug"          =>  $slug,
                    "district_id"   =>  $dist_id,
                    "state_id"      =>  $state_id,
                ]);
                $i++;
            }
        }

        return $i;
    }

    public function statesDataCheck() {
        $states  =   DB::table("states")->where("status",0)->get();

        foreach($states as $state) {
            $check  =   DB::table("states_bak")->where("status",0)->where("name","like",$state->name)->first();
            if($check) {
                DB::table("states_bak")->where("id",$check->id)->update(["status" => 1]);
                DB::table("states")->where("id",$state->id)->update(["old_id" => $check->id,"status" => 1]);
            }
        }

        return $states;
    }

    public function districtsDataCheck() {
        $districts  =   DB::table("districts")->where("status",0)->get();

        foreach($districts as $dist) {
            $check  =   DB::table("districts_bak")->where("status",0)->where("name","like",$dist->name)->first();
            if($check) {
                DB::table("districts_bak")->where("id",$check->id)->update(["status" => 1]);
                DB::table("districts")->where("id",$dist->id)->update(["old_id" => $check->id,"status" => 1]);
            }
        }

        return $districts;
    }

    public function addNewDistricts() {
        $old_districts  =   DB::table("districts_bak")->where("status",0)->get();
        
        foreach($old_districts as $dist) {
            $old_state_id   =   DB::table("states_bak")->where("id",$dist->state_id)->first()->id;
            $state_id       =   DB::table("states")->where("old_id",$old_state_id)->first()->id;

            $slug   =   Str::slug($dist->name);
            $count  =   DB::table("districts")->where("slug",$slug)->count();
            if($count)
                $slug   =   "$slug-$count";
            $dist_id   =   DB::table("districts")->insertGetId([
                "name"      =>  $dist->name,
                "slug"      =>  $slug,
                "state_id"  =>  $state_id,
                "old_id"    =>  $dist->id,
                "status"    =>  1,
            ]);
            DB::table("districts_bak")->where("id",$dist->id)->update(["status" => 1]);
        }

        return $old_districts;
    }
}