<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\NewsSubcategory;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Role;
use App\Model\News;
use Config;
use Auth;
use DB;

class IndexController extends Controller
{
    public function hello() {
        return DB::table("Persons")->take(1)->get();

        $user = News::find(1);
        
        $user->newsSubcategory;
        
        return $user;
    }

    public function insertData() {
        $user = User::find(1);

        // $user->roles

        // $user->roles()->detach();

        $user->roles()->attach([
            1 => ['status' => 0],
            2 => ['status' => 1]
        ]);
    }
}