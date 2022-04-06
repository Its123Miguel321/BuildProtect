<?php

namespace Its123Miguel321\BuildProtect;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Its123Miguel321\BuildProtect\API\BuildsAPI;
use Its123Miguel321\BuildProtect\Commands;
use Its123Miguel321\BuildProtect\EventListener;
use Its123Miguel321\BuildProtect\manager\BuildsManager;
use Its123Miguel321\BuildProtect\provider\DataProvider;
use Its123Miguel321\BuildProtect\provider\JSONProvider;
use Its123Miguel321\BuildProtect\provider\YAMLProvider;

class BuildProtect extends PluginBase
{
	/** @var DataProvider $provider */
	public $provider;
	/** @var BuildsManager $manager */
	public $manager;
	/** @var BuildsAPI $api */
	public $api;
	
	
	
	/**
	 * Registers the Commands, EventListener, API, Manager, and Provider once plugin is enabled!
	 * 
	 */
	public function onEnable() : void
	{
		$this->setProvider();
		
		$this->manager = new BuildsManager($this);
		$this->api = new BuildsAPI($this);
		
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getServer()->getCommandMap()->register('BuildProtect', new Commands($this));
	}
	
	
	
	/**
	 * Saves data in the provider
	 * 
	 */
	public function onDisable() : void
	{
		$this->getProvider()->save();
	}
	
	
	
	/**
	 * Sets provider
	 * 
	 */
	public function setProvider() : void
	{
		$provider = $this->getConfig()->get('DataProvider');
		
		switch(strtolower($provider))
		{
				
			case 'json':
				$this->provider = new JSONProvider($this);
				break;
			
			default:
			case 'yaml':
				$this->provider = new YAMLProvider($this);
				break;

		}
	}
	
	
	
	/**
	 * Returns the provider
	 * 
	 * @return DataProvider
	 * 
	 */
	public function getProvider() : DataProvider
	{
		return $this->provider;
	}
	
	
	
	/**
	 * Returns the manager
	 * 
	 * @return BuildsManager
	 * 
	 */
	public function getManager() : BuildsManager
	{
		return $this->manager;
	}
	
	
	
	/**
	 * Returns the api
	 * 
	 * @return BuildsAPI
	 * 
	 */
	public function getApi() : BuildsAPI
	{
		return $this->api;
	}
}
