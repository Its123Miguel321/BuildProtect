<?php

namespace Its123Miguel321\BuildProtect\API;

use Its123Miguel321\BuildProtect\Area;
use Its123Miguel321\BuildProtect\BuildProtect;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\Position;
use pocketmine\Player;

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
		
		$this->getMain()->getProvider()->saveArea(new Area($id, $name, $creator, $pos1, $pos2, $commands, $permissions, $breaking, $placing, $pvp, $flight));
	}
	
	
	
	/**
	 * Deletes an area from its name
	 * 
	 * @param string $name
	 * 
	 */
	public function deleteArea(string $name) : void
	{
		$id = $this->getMain()->getProvider()->getAreaId($name);
		$area = $this->getMain()->getProvider()->getArea($id);
		
		$this->getMain()->getProvider()->deleteArea($area);
	}
	
	
	
	/**
	 * Checks if player or block is inside an area.
	 * 
	 * @param Position $pos
	 * 
	 * @return bool
	 * 
	 */
	public function isInside(Position $pos) : bool
	{
		foreach($this->getMain()->getProvider()->getAreas() as $areas)
		{
			$area = $this->getMain()->getProvider()->getArea(array_values($areas));
			$pos1 = $area->getPos1();
			$pos2 = $area->getPos2();
			$x = array_flip(range($pos1[0], $pos2[0]));
			$y = array_flip(range($pos1[1], $pos2[1]));
			$z = array_flip(range($pos1[2], $pos2[2]));
			$level = $this->getMain()->getProvider()->getAreaLevel($area);
			if($pos->getLevel()->getName() === $level)
			{
				if(isset($x[$pos->getX()]))
				{
					if(isset($y[$pos->getY()]))
					{
						if(isset($z[$pos->getZ()]))
						{
							return true;
						}
					}
				}
			}
			return false;
		}
		return false;
	}
	
	
	
	/**
	 * Runs all area commands once a player goes into one.
	 * 
	 * @param string $areaName
	 * @param Player $player
	 * 
	 */
	public function runAreaCommands(string $areaName, Player $player) : void
	{
		$id = $this->getMain()->getProvider()->getAreaId($areaName);
		$area = $this->getMain()->getProvider()->getArea($id);
		
		foreach($area->getCommands() as $command)
		{
			if(strpos($command, "/player ") !== false) {
				$this->getMain()->getServer()->dispatchCommand($player, str_replace("{player}", $player->getName(), $command));
			} else {
				$this->getMain()->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", $player->getName(), $command));
			}
		}
	}
	
	
	
	/**
	 * Checks if a player has a permission in an area they go into.
	 * 
	 * @param string $areaName
	 * 
	 */
	public function playerHasAreaPermissons(Player $player, string $areaName) : bool
	{
		$id = $this->getMain()->getProvider()->getAreaId($areaName);
		$area = $this->getMain()->getProvider()->getArea($id);
		
		foreach($this->getMain()->getProvider()->getAreaPermissions($area) as $permission)
		{
			if($player->hasPermission($permission))
			{
				return true;
			}
		}
		return false;
	}
	
	
	
	/**
	 * Returns a players selections.
	 *
	 * @param Player $player
	 * 
	 * @return array
	 */
	public function getSelections(Player $player) : array
	{
		return $this->getMain()->getEventListener()->selections[$player->getName()];
	}
	
	
	
	/**
	 * Checks if a player has a selection.
	 *
	 * @param Player $player
	 * @param string $selection
	 *
	 * @return bool
	 *
	 */
	public function hasSelection(Player $player, string $selection) : bool
	{
		return isset($this->getMain()->getEventListener()->selections[$player->getName()][$selection]);
	}
	
	
	
	/**
	 * Returns the Wand ID
	 *
	 * @return string
	 *
	 */
	public function getWandId() : string
	{
		return $this->getMain()->getConfig()->get("ItemID");
	}
	
	
	
	/**
	 * Returns the Wand Name.
	 *
	 * @return string
	 *
	 */
	public function getWandName() : string
	{
		return $this->getMain()->getConfig()->get("WandName");
	}
	
	
	
	/**
	 * Returns the areas array
	 *
	 * @return array
	 *
	 */
	public function getAreas() : array
	{
		return $this->getMain()->getProvider()->getAreas();
	}
	
	
	
	/**
	 * Returns an areas id by its name.
	 *
	 * @param string $name
	 *
	 * @return int
	 * 
	 */
	public function getAreaId(string $name) : int
	{
		return $this->getMain()->getProvider()->getAreaId($name);
	}
	
	
	
	/**
	 * Returns an area by its id
	 *
	 * @param int $id
	 *
	 * @return Area
	 *
	 */
	public function getArea(int $id) : Area
	{
		return $this->getMain()->getProvider()->getArea($id);
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
