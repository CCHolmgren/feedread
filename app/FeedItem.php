<?php

namespace App;

use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
	protected $guarded = [];

	static function getFieldsFromFeedData($data, $mode = "rss")
	{	
		$foundCategories = object_get($data, 'category');
		$categories = null;
		if(is_array($foundCategories)) {
			$found = [];
			foreach($foundCategories as $category) {
				if(is_object($category) && object_get($category, '@attributes.term')) {
					$found[] = object_get($category, '@attributes.term');
				} else {
					$found[] = $category;
				}
			}
			$categories = implode(", ", $found);
		} elseif(is_object($foundCategories) && object_get($foundCategories, '@attributes.term')) {
			$categories = object_get($foundCategories, '@attributes.term');
		}

		if($mode == "rss") {
			$description = $data->children('content', true)->encoded ?? $data->description;
			
			if(isset($description->img)) {
				$src = $description->img["src"];
				$description = "<img src='{$src}'>";
			}

			$description = str_replace("\n", " ", $description);

			try {
				$dom = new \DOMDocument();
				$dom->preserveWhiteSpace = false;
				$dom->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . '<div>' . $description . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);

				$script = $dom->getElementsByTagName('script');
				$feedflare = $dom->getElementsByTagName('div');

				$remove = [];
				foreach($script as $item)
				{
				  $remove[] = $item;
				}

				foreach ($remove as $item)
				{
				  $item->parentNode->removeChild($item); 
				}

				$description = $dom->saveHTML();
			} catch(\Exception $e) {}

			return [
				'title' => object_get($data, 'title'),
				'link' => object_get($data, 'link'),
				'description' => substr($description, 0, 65000),
				'author' => object_get($data, 'author'),
				'category' => $categories,
				'comments' => object_get($data, 'comments'),
				'enclosure' => object_get($data, 'enclosure'),
				'guid' => (string) object_get($data, 'guid'),
				'pub_date' => Chronos::parse(object_get($data, 'pubDate'))->format('Y-m-d H:i:s'),
			];
		} elseif($mode == "feed") {
			$description = object_get($data, 'content', object_get($data, 'subtitle'));
			try {
				$dom = new \DOMDocument();
				$dom->loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);

				$script = $dom->getElementsByTagName('script');
				$feedflare = $dom->getElementsByTagName('div');

				$remove = [];
				foreach($script as $item)
				{
				  $remove[] = $item;
				}

				foreach($feedflare as $ff) {
				    if(!empty($ff->getAttribute("class")) && strpos("feedflare", $ff->getAttribute("class")) !== -1) {
				        $remove[] = $ff;
				    }
				}

				foreach ($remove as $item)
				{
				  $item->parentNode->removeChild($item); 
				}

				$description = $dom->saveHTML();
			} catch(\Exception $e) {}

			return [
                'title' => object_get($data, 'title'),
                'link' => object_get($data, 'id'),
                'description' => substr($description, 0, 65000),
                'author' => object_get($data, 'author.name'),
                'category' => $categories,
                'comments' => object_get($data, 'comments'),
                'enclosure' => object_get($data, 'enclosure'),
                'guid' => object_get($data, 'id'),
                'pub_date' => Chronos::parse(object_get($data, 'published'))->format('Y-m-d H:i:s'),
            ];
		}

	}
	
	function feed()
	{
		return $this->belongsTo(Feed::class);
	}

	function updateFromFeedData($data)
	{
		unset($data["pub_date"]);
		$this->fill($data)->save();

		return $this->refresh();
	}

	function nextItem()
	{
		return FeedItem::where('feed_id', $this->feed_id)->where('pub_date', '>', $this->pub_date)->orderBy('pub_date', 'asc')->first();
	}

	function previousItem()
	{
		return FeedItem::where('feed_id', $this->feed_id)->where('pub_date', '<', $this->pub_date)->orderBy('pub_date', 'desc')->first();
	}

	function nextItemSubscriptions()
	{
		return FeedItem::whereIn('feed_id', auth()->user()->feeds()->pluck('id'))->where('pub_date', '>', $this->pub_date)->orderBy('pub_date', 'asc')->first();
	}

	function previousItemSubscriptions()
	{
		return FeedItem::whereIn('feed_id', auth()->user()->feeds()->pluck('id'))->where('pub_date', '<', $this->pub_date)->orderBy('pub_date', 'desc')->first();
	}

	function nextItemAll()
	{
		return FeedItem::where('pub_date', '>', $this->pub_date)->orderBy('pub_date', 'asc')->first();
	}

	function previousItemAll()
	{
		return FeedItem::where('pub_date', '<', $this->pub_date)->orderBy('pub_date', 'desc')->first();
	}
}
