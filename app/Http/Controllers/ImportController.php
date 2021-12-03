<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\DefaultImport;
use Config;
use Excel;
use Auth;
use DB;

class ImportController extends Controller
{
    public function importFile() {
        Excel::import(new DefaultImport, 'location.xlsx');

        return;
    }
}