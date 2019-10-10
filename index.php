<?php
declare(strict_types=1);

require('src/bootstrap.php');

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use FeedWriter\ATOM;

/**
  * It is actually ATOM feed
  */

$client = new \GuzzleHttp\Client();
$payload = EpicGameStoreRss\Payload::getData();
$settings = new EpicGameStoreRss\Settings();

$requests = [
	new Request('POST', $settings->feedSource())
];

try {
$promises = [
	'url_a' => $client->sendAsync($requests[0],
		[
			'timeout' => $settings->feedReadTimeout(),
			\GuzzleHttp\RequestOptions::JSON => $payload
		]
	)
];

$results = Promise\unwrap($promises);
$results = Promise\settle($promises)->wait();
$json_results = json_decode((string) $results['url_a']['value']->getBody());

if (!$json_results->data->Blog->dieselBlogPosts->blogList) {
	throw new \Exception('missing json key in response');
}

$list = new EpicGameStoreRss\NewsList();
$list->store($json_results->data->Blog->dieselBlogPosts->blogList);

$feed = new ATOM();

$feed->setTitle($settings->feedTitle());
$feed->setImage($settings->feedIcon());
$feed->setDate($list->get()[0]->date);

foreach ($list->get() as $item) {
	$newItem = $feed->createNewItem();
	$newItem->setTitle($item->title);
	$newItem->setContent('<img src="'.$item->trendingImage.'">' . ($item->content ? '<br>'.$item->content : ''));
	$newItem->setLink($settings->feedEntryBase() . $item->urlPattern);
	$newItem->setId($settings->feedEntryBase() . $item->urlPattern . '#' . $item->_id);
	$newItem->setDate($item->date);
	$newItem->setAuthor(($item->author ? $item->author : $settings->feedDefaultAuthor()));
	
	$feed->addItem($newItem);
}

$feed->printFeed();
} catch (Throwable $e) {
	// do nothing
}
