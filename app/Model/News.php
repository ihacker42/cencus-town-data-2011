<?php
namespace App\Model;

use App\Model\NewsSubCategory;
use App\Model\NewsNewsSubcategory;
use Illuminate\Database\Eloquent\Model;

class News extends Model {
	protected $table = 'users';

	public function newsSubcategory() {
		return $this->belongsToMany(NewsSubCategory::class)->using(NewsNewsSubcategory::class);
	}
}