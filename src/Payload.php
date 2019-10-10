<?php
declare(strict_types=1);

namespace EpicGameStoreRss;

class Payload
{
	private static $data = '';
	private static $init = false;
	
	private static function init() {
		if (!self::$init) {
			self::$data = file_get_contents(__DIR__ . '/config/payload.json');
		}
	}
	
	public function __construct() {
		self::init();
	}
	
	public static function getData($raw = false, $options = [false, 512, 0]) {
		self::init();
		$data = self::$data;
		
		if (!$raw) {
			list($assoc, $depth, $decode_options) = $options;
			$data = json_decode($data, $assoc, $depth, $decode_options);
		}
		
		return $data;
	}
}
