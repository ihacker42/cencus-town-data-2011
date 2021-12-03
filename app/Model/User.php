<?php
namespace App\Model;

use App\Model\Role;
use App\Model\RoleUser;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $table = 'users';

	public function roles() {
		return $this->belongsToMany(Role::class)->using(RoleUser::class);
	}
}