<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NewsNewsSubcategory extends Pivot {
	protected $table = 'news_news_subcategory';
	public $timestamps = false;
}