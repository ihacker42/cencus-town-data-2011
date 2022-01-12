<?php

namespace App\Http\Controllers;

use DB;
use Str;
use App\Model\CensusData;
use Illuminate\Http\Request;

class CencusController extends Controller
{
	public function __construct() {
		ini_set('max_execution_time', 3600);
	}

	public function index() {
		// $this->updateVillageSlug();
		return response()->json([]);
	}

	public function deleteDuplicateRows() {
		$data   =   DB::table("census_data")->select(DB::raw("count(*) as count, name, state_code, district_code, tehsil_code"))->where("status",1)->where("is_deleted",0)->havingRaw(DB::raw("count(*) > 1"))->where("village_code","!=",0)->groupBy("name")->groupBy("tehsil_code")->groupBy("district_code")->groupBy("state_code")->orderBy("count", "desc")->get();

		foreach($data as $row) {
			$rows   =   DB::table("census_data")->where("name",$row->name)->where("tehsil_code",$row->tehsil_code)->where("district_code",$row->district_code)->where("state_code",$row->state_code)->get();

			foreach($rows as $key => $r) {
				if($key > 0) {
					DB::table("census_data")->where("id",$r->id)->update([ "is_deleted" => 1 ]);
				}
			}
		}

		return $data;
	}

	public function insertStates() {
		$states =   CensusData::where("state_code","!=",0)->where("district_code",0)->where("tehsil_code",0)->where("village_code",0)->where("status",0)->where("is_deleted",0)->get();
		
		foreach($states as $row) {
			DB::table("census_states")->insertGetId([
				"name"      =>  $row->name,
				"census_id" =>  $row->state_code,
			]);
			CensusData::where("id",$row->id)->update(["status" => 1]);
		}
	}

	public function statesDataCheck() {
		$states  =   DB::table("census_states")->where("status",0)->get();

		foreach($states as $state) {
			$check  =   DB::table("census_states_bak")->where("status",0)->where("name","like",$state->name)->first();
			if($check) {
				DB::table("census_states_bak")->where("id",$check->id)->update(["status" => 1]);
				DB::table("census_states")->where("id",$state->id)->update(["old_id" => $check->id,"status" => 1]);
			}
		}

		return $states;
	}

	public function addNewStates() {
		$old_states  =   DB::table("census_states_bak")->where("status",0)->get();
		
		foreach($old_states as $state) {
			$state_id   =   DB::table("census_states")->insertGetId([
				"name"      =>  $state->name,
				"old_id"    =>  $state->id,
				"status"    =>  1,
			]);
			DB::table("census_states_bak")->where("id",$state->id)->update(["status" => 1]);
		}

		return $old_states;
	}

	public function updateStateSlug() {
		$states  =   DB::table("census_states")->whereNull("slug")->orderBy("id")->get();
		
		foreach($states as $row) {
			$slug	=	Str::slug($row->name);
			
			$count	=	DB::table("census_states")->where("name",$row->name)->where("id","<",$row->id)->count();
			if($count)
				$slug	=	"$slug-$count";
			
			DB::table("census_states")->where("id",$row->id)->update([ "slug" => $slug ]);
		}
	}

	public function insertDistricts() {
		$districts =   CensusData::where("state_code","!=",0)->where("district_code","!=",0)->where("tehsil_code",0)->where("village_code",0)->where("status",0)->where("is_deleted",0)->get();
		
		foreach($districts as $row) {
			DB::table("census_districts")->insertGetId([
				"name"      =>  $row->name,
				"state_id"  =>  $row->state_code,
				"census_id" =>  $row->district_code,
			]);
			CensusData::where("id",$row->id)->update(["status" => 1]);
		}
	}

	public function districtsDataCheck() {
		$districts  =   DB::table("census_districts")->where("status",0)->get();

		foreach($districts as $dist) {
			$old_districts  =   DB::table("census_districts_bak")->where("status",0)->where("name","like",$dist->name)->get();
			foreach($old_districts as $check) {
				if($check) {
					$old_state_id   =   DB::table("census_states_bak")->where("id",$check->state_id)->pluck("id")->first();
					$state_id       =   DB::table("census_states")->where("old_id",$old_state_id)->pluck("id")->first();
					
					if($dist->state_id == $state_id) {
						DB::table("census_districts_bak")->where("id",$check->id)->update(["status" => 1]);
						DB::table("census_districts")->where("id",$dist->id)->update(["old_id" => $check->id,"status" => 1]);
					}
				}
			}
		}

		return $districts;
	}

	public function addNewDistricts() {
		$old_districts  =   DB::table("census_districts_bak")->where("status",0)->get();
		
		foreach($old_districts as $dist) {
			$old_state_id   =   DB::table("census_states_bak")->where("id",$dist->state_id)->first()->id;
			$state_id       =   DB::table("census_states")->where("old_id",$old_state_id)->first()->id;

			$dist_id   =   DB::table("census_districts")->insertGetId([
				"name"      =>  $dist->name,
				"state_id"  =>  $state_id,
				"old_id"    =>  $dist->id,
				"status"    =>  1,
			]);
			DB::table("census_districts_bak")->where("id",$dist->id)->update(["status" => 1]);
		}

		return $old_districts;
	}

	public function updateDistrictSlug() {
		$districts  =   DB::table("census_districts")->whereNull("slug")->orderBy("id")->get();
		
		foreach($districts as $row) {
			$slug	=	Str::slug($row->name);
			
			$count	=	DB::table("census_districts")->where("name",$row->name)->where("id","<",$row->id)->count();
			if($count)
				$slug	=	"$slug-$count";
			
			DB::table("census_districts")->where("id",$row->id)->update([ "slug" => $slug ]);
		}
	}

	public function insertTehsils() {
		$tehsils =   CensusData::where("state_code","!=",0)->where("district_code","!=",0)->where("tehsil_code","!=",0)->where("village_code",0)->where("status",0)->where("is_deleted",0)->get();
		
		foreach($tehsils as $row) {
			DB::table("census_tehsils")->insertGetId([
				"name"          =>  $row->name,
				"state_id"      =>  $row->state_code,
				"district_id"   =>  $row->district_code,
				"census_id"     =>  $row->tehsil_code,
			]);
			CensusData::where("id",$row->id)->update(["status" => 1]);
		}
	}

	public function tehsilsDataCheck() {
		$tehsils  =   DB::table("census_tehsils")->where("status",0)->get();

		foreach($tehsils as $tehsil) {
			$old_tehsils  =   DB::table("census_tehsils_bak")->where("status",0)->where("name","like",$tehsil->name)->get();
			foreach($old_tehsils as $check) {
				if($check) {
					$old_state_id   =   DB::table("census_states_bak")->where("id",$check->state_id)->pluck("id")->first();
					$state_id       =   DB::table("census_states")->where("old_id",$old_state_id)->pluck("id")->first();

					$old_district_id   =   DB::table("census_districts_bak")->where("id",$check->dist_id)->pluck("id")->first();
					$district_id       =   DB::table("census_districts")->where("old_id",$old_district_id)->pluck("id")->first();
					
					if($tehsil->state_id == $state_id && $tehsil->district_id == $district_id) {
						DB::table("census_tehsils_bak")->where("id",$check->id)->update(["status" => 1]);
						DB::table("census_tehsils")->where("id",$tehsil->id)->update(["old_id" => $check->id,"status" => 1]);
					}
				}
			}
		}

		return $tehsils;
	}

	public function addNewTehsils() {
		$old_tehsils  =   DB::table("census_tehsils_bak")->where("status",0)->get();
		
		foreach($old_tehsils as $tehsil) {
			$old_state_id   =   DB::table("census_states_bak")->where("id",$tehsil->state_id)->first()->id;
			$state_id       =   DB::table("census_states")->where("old_id",$old_state_id)->first()->id;

			$old_district_id   =   DB::table("census_districts_bak")->where("id",$tehsil->dist_id)->first()->id;
			$district_id       =   DB::table("census_districts")->where("old_id",$old_district_id)->first()->id;

			$tehsil_id   =   DB::table("census_tehsils")->insertGetId([
				"name"          =>  $tehsil->name,
				"district_id"   =>  $district_id,
				"state_id"      =>  $state_id,
				"old_id"        =>  $tehsil->id,
				"status"        =>  1,
			]);
			DB::table("census_tehsils_bak")->where("id",$tehsil->id)->update(["status" => 1]);
		}

		return $old_tehsils;
	}

	public function updateTehsilSlug() {
		$tehsils  =   DB::table("census_tehsils")->whereNull("slug")->orderBy("id")->get();
		
		foreach($tehsils as $row) {
			$slug	=	Str::slug($row->name);
			
			$count	=	DB::table("census_tehsils")->where("name",$row->name)->where("id","<",$row->id)->count();
			if($count)
				$slug	=	"$slug-$count";
			
			DB::table("census_tehsils")->where("id",$row->id)->update([ "slug" => $slug ]);
		}
	}

	public function insertVillages() {
		$villages =   CensusData::where("state_code","!=",0)->where("district_code","!=",0)->where("tehsil_code","!=",0)->where("village_code","!=",0)->where("status",0)->where("is_deleted",0)->take(200000)->get();
		
		foreach($villages as $row) {
			DB::table("census_villages")->insertGetId([
				"name"          =>  $row->name,
				"state_id"      =>  $row->state_code,
				"district_id"   =>  $row->district_code,
				"tehsil_id"     =>  $row->tehsil_code,
				"census_id"     =>  $row->village_code,
			]);
			CensusData::where("id",$row->id)->update(["status" => 1]);
		}
	}

	public function updateVillageData() {
		$villages   =   DB::table("census_villages")->select("id","tehsil_id","district_id","state_id")->where("status",0)->take(250000)->get();
		foreach($villages as $village) {
			$new_tehsil_id   =   DB::table("census_tehsils")->where("id",$village->tehsil_id)->pluck("old_id")->first();
			if($new_tehsil_id == 0)
				continue;
			
			$tehsil_id       =   DB::table("census_tehsils_bak")->where("id",$new_tehsil_id)->pluck("id")->first();
			
			$new_state_id   =   DB::table("census_states")->where("id",$village->state_id)->pluck("old_id")->first();
			$state_id       =   DB::table("census_states_bak")->where("id",$new_state_id)->pluck("id")->first();

			$new_district_id   =   DB::table("census_districts")->where("id",$village->district_id)->pluck("old_id")->first();
			$district_id       =   DB::table("census_districts_bak")->where("id",$new_district_id)->pluck("id")->first();

			DB::table("census_villages")->where("id",$village->id)->where("status",0)->update([
				"old_state_id"      =>  $state_id,
				"old_district_id"   =>  $district_id,
				"old_tehsil_id"     =>  $tehsil_id,
				"status"            =>  1,
			]);
		}

		return $villages;
	}

	public function updateVillageSlug() {
		$villages  =   DB::table("census_villages")->whereNull("slug")->orderBy("id")->take(300000)->get();
		
		foreach($villages as $row) {
			$slug	=	Str::slug($row->name);
			
			$count	=	DB::table("census_villages")->whereNotNull("slug")->where("id","<",$row->id)->where("name",$row->name)->count();
			if($count)
				$slug	=	"$slug-$count";
			
			DB::table("census_villages")->where("id",$row->id)->update([ "slug" => $slug ]);
		}
	}
}