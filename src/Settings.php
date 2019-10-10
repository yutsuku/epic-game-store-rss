<?php
declare(strict_types=1);

namespace EpicGameStoreRss;

class Settings
{
	private $settings = [];
	
	public function __construct() {
		$this->init();
	}
	
	public function init() {
		$data = require(__DIR__ . '/config/app.php');
		
		$default = [
			'feed_source' => 'https://graphql.epicgames.com/graphql',
			'feed_readtimeout' => 2.0,
			'feed_title' => 'Epic Games news',
			'feed_icon' => 'https://static-assets-prod.epicgames.com/epic-store/static/favicon.ico',
			'feed_default_author' => 'Epic Games',
			'feed_entry_link_base' => 'https://www.epicgames.com'
		];
		
		foreach ($default as $key => $value) {
			$default[$key] = ($data[$key] ? $data[$key] : $value);
		}
		
		$this->settings = $default;
	}
	
	public function feedSource() {
		return $this->settings['feed_source'];
	}
	
	public function feedReadTimeout() {
		return $this->settings['feed_readtimeout'];
	}
	
	public function feedTitle() {
		return $this->settings['feed_title'];
	}
	
	public function feedIcon() {
		return $this->settings['feed_icon'];
	}
	
	public function feedDefaultAuthor() {
		return $this->settings['feed_default_author'];
	}
	
	public function feedEntryBase() {
		return $this->settings['feed_entry_link_base'];
	}
}