<?php 
namespace App\Imports;

use App\Model\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DefaultImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // $states     =   [];
        // $districts  =   [];
        // $tehsils    =   [];
        $state      =   0;
        $district   =   0;
        $tehsil     =   0;
        foreach ($rows as $row) {
            if($row[3] == 'STATE') {
                if($row[4] == 'TRIPURA')
                    $state  =   $state + 2;
                else
                    $state++;
            }
            if($row[3] == 'DISTRICT') {
                $district++;
            }
            if($row[3] == 'TOWN') {
                $tehsil++;
            }

            $name   =   explode("(",$row[4])[0];
            $name   =   explode("*",$name)[0];
            $name   =   ltrim($name);
            $name   =   rtrim($name);
            $name   =   trim($name);
            $name   =   str_replace("  "," ",$name);

            Location::create([
                'state' => $state,
                'district' => $row[3] == 'STATE' ? 0 : $district,
                'town' => $row[3] == 'STATE' || $row[3] == 'DISTRICT' ? 0 : $tehsil,
                'level' => $row[3],
                'name' => ucwords(strtolower($name)),
            ]);
        }
        
        return;
    }
}