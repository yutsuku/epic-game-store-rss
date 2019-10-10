<?php
declare(strict_types=1);

namespace EpicGameStoreRss;

class NewsList
{
	private $blogList = array();
	
	public function store(array $blogList) {
		// dumb check
		foreach($blogList as $key => $value) {
			if ($value && $value === '') {
				$blogList[$key] = null;
			}
		}
		
		$this->blogList = $blogList;
	}
	
	public function get() {
		return $this->blogList;
	}
}