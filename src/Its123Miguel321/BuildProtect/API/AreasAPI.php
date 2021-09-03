<?php

namespace Its123Miguel321\BuildProtect\API;

use Its123Miguel321\BuildProtect\Area;
use Its123Miguel321\BuildProtect\BuildProtect;

use poc

class AreasAPI
{
	/** @var BuildProtect $main */
	public $main;
	/** @var EventListener $events */
	public $events;
	
	/**
	 * @param BuildProtect $main
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
	}
	
	/**
	 * @param string $name
	 * @param string $creator
	 * @param Position $pos1
	 * @param Position $pos2
	 * @param string[] $commands
	 * @param string[] $permissions
	 * @param bool $breaking
	 * @param bool $placing
	 * @param bool $pvp
	 * @param bool $flight
	 */
	public function createArea(string $name = "", string $creator = "", Position $pos1 = [], Position $pos2 = [], array $commands = [], array $permissions = [], bool $breaking = true, bool $placing = true, bool $pvp = true, bool $flight = true) : void
	{	
		$id = $this->countAreas() + 1;
		if($id < 0)
		{
			$id++;
		}
		
		$x1 = $pos1->x;
		$x2 = $pos2->x;
		$y1 = $pos1->y;
		$y2 = $pos2->y;
		$z1 = $pos1->z;
		$z2 = $pos2->z;
		$level1 = $pos1->level;
		$level2 = $pos2->level;
		
		$this->saveArea(new Area($id, $name, $creator, array($x1, $y1, $z1, $level1), array($x2, $y2, $z2), $commands, $permissions, $breaking, $placing, $pvp, $flight));
		
	}
	
	public function countAreas() : void
	{
		return count($this->getMain()->getConfig()->get("areas", []));
	}
	
	public function saveArea(Area $area) : void
	{
		$areas = $this->getMain()->getData()->get("areas", []);
		
		$areas[$id] = array();
		
		$this->getMain()->getConfig()->set("areas", $areas);
		$this->getMain()->getConfig()->save();
	}
	
	public function getMain() : BuildProtect
	{
		return $this->main;
	}
		
}
