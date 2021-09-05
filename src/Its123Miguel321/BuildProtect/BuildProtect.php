<?php

namespace Its123Miguel321\BuildProtect;

use Its123Miguel321\BuildProtect\commands\Save;
use Its123Miguel321\BuildProtect\commands\Delete;
use Its123Miguel321\BuildProtect\commands\Edit;
use Its123Miguel321\BuildProtect\commands\Protect;
use Its123Miguel321\BuildProtect\EventListener;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class BuildProtect extends PluginBase implements Listener{
	
	/** @var DataProvider $provider */
	public $provider;
	/** @var EventListener $eventListener */
	public $eventListener;
	/** @var AreasAPI $api */
	public $api;
	public static $instance;
	
    public function onEnable()
	{
		self::$instance = $this;
		
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		
		$this->getServer()->getCommandMap()->registerAll("BuildProtect", [new Protect($this), new Save($this), new Delete($this), new Edit($this)]);
		
		Enchantment::RegisterEnchantment(new Enchantment(100, "BuildProtect", Enchantment::RARITY_COMMON, 0, 0, 1));
		
		$this->saveResource("builds.yml");
	}
	
	public function getApi() : AreasAPI
	{
		return $this->api;
	}
    	
	public function getProvider() : DataProvider
	{
		return $this->provider;
	}
	
	public static function getInstance() : BuildProtect
	{
		return self::$instance;
	}
}
