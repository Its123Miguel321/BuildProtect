<?php

namespace Its123Miguel321\BuildProtect\DataManager;

use pocketmine\player\Player;
use pocketmine\world\Position;

use Its123Miguel321\BuildProtect\Build;
use Its123Miguel321\BuildProtect\BuildProtect;

class BuildsManager
{
	/** @var BuildProtect $main */
	public $main;
	
	
	
	/**
	 * BuildsManager constructor
	 * 
	 * @param BuildProtect $main
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
	}
	
	
	
	/**
	 * Checks if build already exists by its name
	 * 
	 * @param string $name
	 * 
	 * @return bool
	 * 
	 */
	public function buildExists(string $name) : bool
	{
		return $this->getMain()->getProvider()->buildExists($name);
	}
	
	
	
	/**
	 * Creates an new Build
	 * 
	 * @param string $name
	 * @param $creator
	 * @param int $priority
	 * @param array $pos1
	 * @param array $pos2
	 * @param bool $breaking
	 * @param bool $placing
	 * @param bool $pvp
	 * @param bool $flight
	 * 
	 */
	public function createBuild(string $name, $creator, int $priority, array $pos1, array $pos2, bool $breaking, bool $placing, bool $pvp, bool $flight) : void
	{
		$id = $this->countBuilds() + 1;
		
		if($this->buildExists($name))
		{
			$build = $this->getBuildByName($name);
			
			$id = $build->getId();
		}
		
		if($priority < -1) $priority = -1;
		if($creator instanceof Player) $creator = $creator->getName();
		
		$this->getMain()->getProvider()->createBuild(new Build($id, $name, $creator, $priority, $pos1, $pos2, $breaking, $placing, $pvp, $flight));
	}
	
	
	
	/**
	 * Deletes a build by name
	 * 
	 * @param string $name
	 * 
	 */
	public function deleteBuildByName(string $name) : void
	{
		$build = $this->getBuildByName($name);
		
		$this->getMain()->getProvider()->deleteBuild($build);
	}
	
	
	
	/**
	 * Deletes a build by id
	 * 
	 * @param int $id
	 * 
	 */
	public function deleteBuildById(int $id) : void
	{
		$build = $this->getBuildById($id);
		
		$this->getMain()->getProvider()->deleteBuild($build);
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
		return $this->getMain()->getProvider()->getBuildByName($name);
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
		return $this->getMain()->getProvider()->getBuildById($id);
	}
	
	
	
	/**
	 * Gets all builds
	 * 
	 * @return array
	 * 
	 */
	public function getBuilds() : array
	{
		return $this->getMain()->getProvider()->getBuilds();
	}
	
	
	
	/**
	 * Counts how many builds there are!
	 * 
	 * @return int
	 * 
	 */
	public function countBuilds() : int
	{
		return $this->getMain()->getProvider()->countBuilds();
	}
	
	
	
	/**
	 * Returns BuildProtect
	 * 
	 * @return BuildProtect
	 * 
	 */
	public function getMain() : BuildProtect
	{
		return $this->main;
	}
}