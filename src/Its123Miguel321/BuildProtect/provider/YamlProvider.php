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
	 * Checks if Area name already exists.
	 * 
	 * @param Area $area
	 * 
	 */
	public function areaExists(Area $area) : bool
	{
		$areas = $this->yaml->get("areas", []);
		
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
		return count($this->yaml->get("areas", []));
	}
	
	/**
	 * Saves an area.
	 * 
	 * @param Area $area
	 * 
	 */
	public function saveArea(Area $area) : void
	{
		$areas = $this->yaml->get("areas", []);
		
		$areas[$area->getId()] = array("Name" => $area->getName(), "Creator" => $area->getCreator(), "Pos1" => $area->getPos1(), "Pos2" => $area->getPos2, "Commands" => $area->getCommands(), "Permissions" => $area->getPermissions(), "BlockBreaking" => $area->getSetting("Breaking"), "BlockPlacing" => $area->getSetting("Placing"), "PvP" => $area->getSetting("PvP"), "Flight" => $area->getSetting("Flight"));
		
		$this->yaml->set("areas", $areas);
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
		$areas = $this->yaml->get("areas", []);
		
		unset($areas[$area->getId()]);
		
		$this->yaml->set("areas", $areas);
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
		$areas = $this->yaml->get("areas", []);
		
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
		$areas = $this->yaml->get("areas", []);
		$key = array_keys($areas, ["Name" => $name], true);
		
		return $key;
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
		return $area->getPos1;
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
		return $area->getPos2;
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
