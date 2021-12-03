<?php
namespace App\Model;

use App\Model\News;
use Illuminate\Database\Eloquent\Model;

class NewsSubcategory extends Model {
	protected $table = 'news_subcategories';

	public function news() {
		return $this->belongsToMany(News::class);
	}
}