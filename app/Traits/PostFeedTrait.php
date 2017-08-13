<?php
namespace App\Traits;

use Carbon\Carbon;


trait PostFeedTrait
{

	public function getFeedItemId()
	{
		return $this->id;
	}

	public function getFeedItemTitle() : string
	{
		return $this->title;
	}

	public function getFeedItemSummary() : string
	{
		return $this->text;
	}

	public function getFeedItemUpdated() : Carbon
	{
		return $this->updated_at ;
		//return Carbon::createFromFormat( 'Y-m-d H:i:s' , $this->updated_at );
	}

	public function getFeedItemLink() : string
	{
		return $this->site_link;
	}

	public function getFeedItemAuthor() : string
	{
		return getSetting('site_title');
	}


	public function getFeedItems($locale)
	{
		return self::selector([
			'type'     => "feature:rss",
		     'locale' => $locale ,
		])->get();
	}
}