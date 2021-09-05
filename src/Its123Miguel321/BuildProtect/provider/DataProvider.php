<?php

namespace Its123Miguel321\BuildProtect\provider;

use Its123Miguel321\BuildProtect\Area;
use Its123Miguel321\BuildProtect\BuildProtect;

abstract class DataProvider
{
	public $plugin;
	
	public function __construct(BuildProtect $plugin)
	{
		$this->plugin = $plugin;
	}
	
	public abstract function areaExists(Area $area) : bool;
	
	public abstract function countAreas() : int;
	
	public abstract function saveArea(Area $area) : void;
	
	public abstract function deleteArea(Area $area) : void;
	
	public abstract function getArea(int $id) : Area;
	
	public abstract function getAreas() : array;
	
	public abstract function getAreaCommands(Area $area) : array;
	
	public abstract function getAreaId(string $name) : int;
	
	public abstract function getAreaLevel(Area $area) : string;
	
	public abstract function getAreaPermissions(Area $area) : array;
	
	public abstract function getAreaPos1(Area $area) : array;
	
	public abstract function getAreaPos2(Area $area) : array;
	
	public abstract function open() : void;
	
	public abstract function save() : void;
	
	public abstract function close() : void;
	
	public abstract function getMain() : BuildProtect;
}
