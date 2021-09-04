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
	
	public abstact function areaExists(Area $area) : bool;
	
	public abstact function saveArea(Area $area) : void;
	
	public abstact function deleteArea(Area $area) : void;
	
	public abstact function getArea(int $id) : Area;
	
	public abstact function getAreaCommands(Area $area) : void;
	
	public abstact function getAreaId(string $name) : int;
	
	public abstact function getAreaLevel(Area $area) : void;
	
	public abstact function getAreaPermissions(Area $area) : void;
	
	public abstact function getAreaPos1(Area $area) : void;
	
	public abstact function getAreaPos2(Area $area) : void;
}
