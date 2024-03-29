<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {
	protected $table = 'locations';
    protected $fillable = [
        'state', 'district', 'town', 'level', 'name',
    ];
}