<?php
namespace App\Traits;

trait EhdaPostTrait
{

	public static function getAllEvents($domain = 'auto')
	{
		return self::selector([
			'type' => "event" ,
		     'domain' => $domain ,
		])->orderBy('published_at' , 'desc')->get() ;
	}
}