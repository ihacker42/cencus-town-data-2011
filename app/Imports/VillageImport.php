<?php 
namespace App\Imports;

use App\Model\CensusData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VillageImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // $name   =   explode("(",$row[4])[0];
            // $name   =   explode("*",$name)[0];
            // $name   =   ltrim($name);
            // $name   =   rtrim($name);
            // $name   =   trim($name);
            // $name   =   str_replace("  "," ",$name);

            $name   =   $row[4];
            $name   =   str_replace("–","-",$name);
            $name   =   str_replace("‘","'",$name);
            $name   =   str_replace("’","'",$name);

            CensusData::create([
                'state_code'    =>  $row[0],
                'district_code' =>  $row[1],
                'tehsil_code'   =>  $row[2],
                'village_code'  =>  $row[3],
                'name'          =>  ucwords(strtolower($name)),
            ]);
        }

        return;
    }
}