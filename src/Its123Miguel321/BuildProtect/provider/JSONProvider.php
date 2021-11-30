<?php

namespace Its123Miguel321\BuildProtect\provider;

use pocketmine\utils\Config;

use Its123Miguel321\BuildProtect\Build;
use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\provider\DataProvider;

class JSONProvider extends DataProvider
{
	/** @var Config $config */
	public $config;
	
	/**
	 * YAMLProvider constructor
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
		
		$this->config = new Config($this->getMain()->getDataFolder() . 'builds.yml', Config::JSON, array('builds' => []));
	}
	/**
	 * Checks if build exists
	 * 
	 * @param string $name
	 * 
	 * @return bool
	 * 
	 */
	public function buildExists(string $name) : bool
	{
		$build = $this->getBuildByName($name);
		
		if($build === null) return false;
		
		return true;
	}
	
	
	
	/**
	 * Creates a build
	 * 
	 * @param Build $build
	 * 
	 */
	public function createBuild(Build $build) : void
	{
		$this->data[$build->getId()] = $build;
	}
	
	
	
	/**
	 * Deletes a build
	 * 
	 * @param Build $build
	 * 
	 */
	public function deleteBuild(Build $build) : void
	{
		unset($this->data[$build->getId()]);
	}
	
	
	
	/**
	 * Gets a build by its name
	 * 
	 * @param string $name
	 * 
	 * @return Build|null
	 * 
	 */
	public function getBuildByName(string $name) : Build|null
	{
		foreach($this->data as $build)
		{
			if($build->getName() === $name)
			{
				return $build;
			}
		}
		
		return null;
	}
	
	
	
	/**
	 * Gets a build by its id
	 * 
	 * @param int $id
	 * 
	 * @return Build|null
	 * 
	 */
	public function getBuildById(int $id) : Build|null
	{
		foreach($this->data as $build)
		{
			if($build->getId() === $id)
			{
				return $build;
			}
		}
		
		return null;
	}
	
	
	
	/**
	 * Gets all protected builds
	 * 
	 * @return array
	 * 
	 */
	public function getBuilds() : array
	{
		return $this->data;
	}
	
	
	
	/**
	 * Counts how many protected builds there are
	 * 
	 * @return int
	 * 
	 */
	public function countBuilds() : int
	{
		return count($this->data);
	}
	
	
	
	/**
	 * Return the Config
	 * 
	 * @return Config
	 * 
	 */
	public function getConfig() : Config
	{
		return $this->config;
	}
	
	
	
	/**
	 * Opens data
	 * 
	 */
	public function open() : void
	{
		$this->data = [];
		
		foreach($this->getConfig()->get('builds', []) as $build)
		{
			$this->data[$build['Id']] = new Build(
				$build['Id'], 
				$build['Name'], 
				$build['Creator'], 
				$build['Priority'], 
				$build['Pos1'], 
				$build['Pos2'], 
				$build['Breaking'], 
				$build['Placing'], 
				$build['PvP'], 
				$build['Flight']
			);
		}
	}
	
	
	
	/**
	 * Saves data
	 * 
	 */
	public function save() : void
	{
		$config = $this->getConfig()->get('builds', []);
		
		foreach($this->data as $build)
		{
			$config[$build->getId()] = [
				'Id' => $build->getId(), 
				'Name' => $build->getName(), 
				'Creator' => $build->getCreator(), 
				'Priority' => $build->getPriority(), 
				'Pos1' => $build->getPos1(),
				'Pos2' => $build->getPos2(),
				'Breaking' => $build->getSetting('breaking'),
				'Placing' => $build->getSetting('placing'),
				'PvP' => $build->getSetting('pvp'),
				'Flight' => $build->getSetting('flight')
			];
			
			
		}
		
		$this->getConfig()->setAll(['builds' => $config]);
		$this->getConfig()->save();
	}
	
	
	
	/**
	 * Closes data
	 * 
	 */
	public function close() : void
	{
		$this->data = [];
		
		$this->getConfig()->save();
	}
}
