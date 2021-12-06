<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CensusData extends Model {
	protected $table = 'census_data';
    protected $fillable = [
        'state_code', 'district_code', 'tehsil_code', 'village_code', 'name',
    ];
}