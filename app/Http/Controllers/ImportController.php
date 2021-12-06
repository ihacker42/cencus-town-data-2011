<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\VillageImport;
use Config;
use Excel;
use Auth;
use DB;

class ImportController extends Controller
{
    public function importFile() {
        // Excel::import(new DefaultImport, 'location.xlsx');

        return;
    }

    public function importCensusFile() {
        // Excel::import(new VillageImport, 'data-1.xlsx');
        // Excel::import(new VillageImport, 'data-2.xlsx');
        // Excel::import(new VillageImport, 'data-3.xlsx');
        // Excel::import(new VillageImport, 'data-4.xlsx');
        // Excel::import(new VillageImport, 'data-5.xlsx');
        // Excel::import(new VillageImport, 'data-6.xlsx');
        // Excel::import(new VillageImport, 'data-7.xlsx');
        // Excel::import(new VillageImport, 'data-8.xlsx');
        // Excel::import(new VillageImport, 'data-9.xlsx');
        // Excel::import(new VillageImport, 'data-10.xlsx');
        // Excel::import(new VillageImport, 'data-11.xlsx');
        // Excel::import(new VillageImport, 'data-12.xlsx');
        // Excel::import(new VillageImport, 'data-13.xlsx');

        return;
    }
}