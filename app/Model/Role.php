<?php
namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
	protected $table = 'roles';

	public function users() {
		return $this->belongsToMany(User::class);
	}
}