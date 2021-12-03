<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot {
	protected $table = 'role_user';
	public $timestamps = false;
}