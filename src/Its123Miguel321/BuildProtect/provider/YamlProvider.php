<?php

namespace Its123Miguel321\BuildProtect\provider;

use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\provider\DataProvider;

use pocketmine\utils\Config;

class YamlProvider extends DataProvider
{
	/** @var BuildProtect $plugin */
	public $plugin;
	/** @var Config $yaml */
	public $yaml;
	
	
	/**
	 * YamlProvider constructer.
	 * 
	 * @param BuildProtect $plugin;
	 *
	 */
	public function __construct(BuildProtect $plugin)
	{
		$this->plugin = $plugin;
		
		$this->open();
	}
	
	
	
	/**
	 * Checks if Area name already exists
	 * 
	 * @param string $area
	 * 
	 */
	public function areaExists(string $area) : bool
	{
		$areas = $this->yaml->get("areas", []);
		
		return isset(array_keys($areas, ["Name" => $name], true));
	}
}
