<?php

namespace Its123Miguel321\BuildProtect;

use Its123Miguel321\BuildProtect\API\AreasAPI;
use Its123Miguel321\BuildProtect\commands\Save;
use Its123Miguel321\BuildProtect\commands\Delete;
use Its123Miguel321\BuildProtect\commands\Edit;
use Its123Miguel321\BuildProtect\commands\Protect;
use Its123Miguel321\BuildProtect\EventListener;
use Its123Miguel321\BuildProtect\provider\DataProvider;
use Its123Miguel321\BuildProtect\provider\YamlProvider;
use Its123Miguel321\BuildProtect\provider\JsonProvider;

use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\plugin\PluginBase;

class BuildProtect extends PluginBase implements Listener
{
	
	/** @var DataProvider $provider */
	public $provider;
	/** @var EventListener $events */
	public $events;
	/** @var AreasAPI $api */
	public $api;
	/** @var BuildProtect $instance */
	public static $instance;
	
	
	
	public function onEnable()
	{
		self::$instance = $this;
		
		$this->events = new EventListener($this);
		$this->api = new AreasAPI($this)
		
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		
		$this->getServer()->getCommandMap()->registerAll("BuildProtect", [new Protect($this), new Save($this), new Delete($this), new Edit($this)]);
		
		Enchantment::RegisterEnchantment(new Enchantment(100, "BuildProtect", Enchantment::RARITY_COMMON, 0, 0, 1));
		
		$this->saveResource("builds.yml");
	}
	
	
	
	/**
	 * Returns the AreasAPI
	 * 
	 * @return AreasAPI
	 *
	 */
	public function getApi() : AreasAPI
	{
		return $this->api;
	}
	
	
	
	/**
	 * Returns the EventListener
	 * 
	 * @return EventListener
	 *
	 */
	public function getEventListener() : EventListener
	{
		return $this->events;
	}
    
	
	
	/**
	 * Returns the DataProvider
	 * 
	 * @return DataProvider
	 *
	 */
	public function getProvider() : DataProvider
	{
		return $this->provider;
	}
	
	
	
	public function setProvider() : void
	{
		$provider = strtolower($this->getConfig()->get("DataProvider"));
		
		switch($provider)
		{
			case "json":
				$this->provider = new JsonProvider($this);
			break;
				
			case "yaml":
			default:
				$this->provider = new YamlProvider($this);
			break;
		}
	}
	
	
	
	/**
	 * Returns this file
	 * 
	 * @return BuildProtect
	 *
	 */
	public static function getInstance() : BuildProtect
	{
		return self::$instance;
	}
}
