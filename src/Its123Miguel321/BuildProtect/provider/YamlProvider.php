<?php

namespace Its123Miguel321\BuildProtect\provider;

use Its123Miguel321\BuildProtect\Area;
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
	 * @param Area $area
	 * 
	 */
	public function areaExists(Area $area) : bool
	{
		$areas = $this->yaml->get("areas", []);
		
		return isset($areas[$area->getId()][$areas->getName()]);
	}
	
	public function open() : void
	{
		$this->yaml = new Config($this->getMain()->getDataFolder() . "areas.yml", Config::YAML, array("areas" => []));
	}
	
	public function save() : void
	{
		$this->yaml->save();
	}
	
	public function close() : void
	{
		$this->save();
	}
}
