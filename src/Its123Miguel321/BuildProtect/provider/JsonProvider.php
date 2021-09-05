<?php

namespace Its123Miguel321\BuildProtect\provider;

use Its123Miguel321\BuildProtect\Area;
use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\provider\DataProvider;

use pocketmine\utils\Config;

class JsonProvider extends DataProvider
{
	/** @var BuildProtect $plugin */
	public $plugin;
	/** @var Config $json */
	public $json;
	
	
	
	/**
	 * JsonProvider constructer.
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
	 * Checks if Area name already exists.
	 * 
	 * @param Area $area
	 * 
	 */
	public function areaExists(Area $area) : bool
	{
		$areas = $this->json->get("builds", []);
		
		return isset($areas[$area->getId()][$areas->getName()]);
	}
	
	
	
	/**
	 * Counts how many areas there are.
	 * 
	 * @return int
	 * 
	 */
	public function countAreas() : int
	{
		return count($this->json->get("builds", []));
	}
	
	
	
	/**
	 * Saves an area.
	 * 
	 * @param Area $area
	 * 
	 */
	public function saveArea(Area $area) : void
	{
		$areas = $this->json->get("builds", []);
		
		$areas[$area->getId()] = array("Name" => $area->getName(), "Creator" => $area->getCreator(), "Pos1" => $area->getPos1(), "Pos2" => $area->getPos2(), "Commands" => $area->getCommands(), "Permissions" => $area->getPermissions(), "BlockBreaking" => $area->getSetting("Breaking"), "BlockPlacing" => $area->getSetting("Placing"), "PvP" => $area->getSetting("PvP"), "Flight" => $area->getSetting("Flight"));
		
		$this->json->set("builds", $areas);
		$this->save();
	}
	
	
	
	/**
	 * Deletes an area.
	 * 
	 * @param Area $area
	 * 
	 */
	public function deleteArea(Area $area) : void
	{
		$areas = $this->json->get("builds", []);
		
		unset($areas[$area->getId()]);
		
		$this->json->set("builds", $areas);
		$this->save();
	}
	
	
	
	/**
	 * Returns an area
	 * 
	 * @param int $id
	 * 
	 * @return Area
	 * 
	 */
	public function getArea(int $id) : Area
	{
		$areas = $this->json->get("builds", []);
		
		if(isset($areas[$id])) {
			$name = $areas[$id]["Name"];
			$creator = $areas[$id]["Creator"];
			$pos1 = $areas[$id]["Pos1"];
			$pos2 = $areas[$id]["Pos2"];
			$commands = $areas[$id]["Commands"];
			$permissions = $areas[$id]["Permissions"];
			$breaking = $areas[$id]["BlockBreaking"];
			$placing = $areas[$id]["BlockPlacing"];
			$pvp = $areas[$id]["PvP"];
			$flight = $areas[$id]["Flight"];
			
			return new Area($id, $name, $creator, $pos1, $pos2, $commands, $permissions, $breaking, $placing, $pvp, $flight);
		}
		
		return new Area();
	}
	
	
	
	/**
	 * Returns all areas.
	 * 
	 * @return array
	 * 
	 */
	public function getAreas() : array
	{
		return $this->json->get("builds", []);
	}
	
	
	
	/**
	 * Returns an area's commands.
	 * 
	 * @param Area $area
	 * 
	 * @return array
	 * 
	 */
	public function getAreaCommands(Area $area) : array
	{
		return $area->getCommands();
	}
	
	
	
	/**
	 * Returns an area's id by it's name.
	 * 
	 * @param string $name
	 * 
	 * @return int
	 * 
	 */
	public function getAreaId(string $name) : int
	{
		$count = 0;
		
		foreach(array_keys($this->json->get("builds", [])) as $areas)
		{
			$count++;
			if($areas["Name"] === $name) {
				return $count;
			}
		}
		return -1;
	}
	
	
	
	/**
	 * Returns an area's level.
	 * 
	 * @param Area $area
	 * 
	 * @return string
	 *
	 */
	public function getAreaLevel(Area $area) : string
	{
		$pos1 = $area->getPos1();
		$pos2 = $area->getPos2();
		
		return $pos1[3] ?? $pos2[3];
	}
	
	/**
	 * Returns an area's permissions
	 * 
	 * @param Area $area
	 * 
	 * @return array
	 * 
	 */
	public function getAreaPermissions(Area $area) : array
	{
		return $area->getPermissions();
	}
	
	
	
	/**
	 * Returns the area's first position
	 * 
	 * @param Area $area
	 * 
	 * @return array
	 * 
	 */
	public function getAreaPos1(Area $area) : array
	{
		return $area->getPos1();
	}
	
	/**
	 * Returns the area's second position
	 *
	 * @param Area $area
	 *
	 * @return array
	 *
	 */
	public function getAreaPos2(Area $area) : array
	{
		return $area->getPos2();
	}
	
	public function open() : void
	{
		$this->json = new Config($this->getMain()->getDataFolder() . "builds.yml", Config::JSON, array("builds" => []));
	}
	
	public function save() : void
	{
		$this->json->save();
	}
	
	public function close() : void
	{
		$this->save();
	}
	
	public function getMain() : BuildProtect
	{
		return $this->main;
	}
}
