<?php

namespace Its123Miguel321\BuildProtect\API;

use Its123Miguel321\BuildProtect\BuildProtect;

use pocketmine\level\Position;

class AreasAPI
{
	/** @var BuildProtect $main */
	public $main;
	
	
	
	/**
	 * AreasAPI constructer.
	 * 
	 * @param BuildProtect $main
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
	}
	
	
	
	/**
	 * Checks if an area exists.
	 *
	 * @param string $name
	 * 
	 * @return bool
	 * 
	 */
	public function areaExists(string $name) : bool
	{
		$id = $this->getMain()->getProvider()->getAreaId($name);
		$area = $this->getMain()->getProvider()->getArea($id);
		
		return $this->getMain()->getProvider()->areaExists($area);
	}
	
	
	
	/**
	 * Creates a new area.
	 * 
	 * @param string $name
	 * @param string $creator
	 * @param array $pos1
	 * @param array $pos2
	 * @param array $commands
	 * @param array $permissions
	 * @param bool $breaking
	 * @param bool $placing
	 * @param bool $pvp
	 * @param bool $flight
	 *
	 */
	public function createArea(string $name, string $creator, array $pos1, array $pos2, array $commands, array $permissions, bool $breaking, bool $placing, bool $pvp, bool $flight) : void
	{
		$id = $this->getMain()->getProvider()->countAreas() + 1;
		
		$this->getMain()->getProvider()->saveArea(new Area($id, $name, $creator, $pos1, $pos2, $commands, $permissons, $breaking, $placing, $pvp, $flight));
	}
	
	/**
	 * Return the Main file of this plugin
	 *
	 * @return BuildProtect
	 * 
	 */
	public function getMain() : BuildProtect
	{
		return $this->main;
	}
}
