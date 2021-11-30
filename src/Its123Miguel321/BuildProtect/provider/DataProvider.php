<?php

namespace Its123Miguel321\BuildProtect\provider;

use Its123Miguel321\BuildProtect\Build;
use Its123Miguel321\BuildProtect\BuildProtect;

abstract class DataProvider
{
	/** @var BuildProtect $main */
	public $main;
	/** @var array $data */
	public $data = [];
	
	
	
	/**
	 * DataProvider constructor
	 * 
	 * @param BuildProtect $main
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
		$this->open();
	}
	
	
	
	/**
	 * Returns BuildProtect
	 * 
	 * @return BuildProtect
	 * 
	 */
	final public function getMain() : BuildProtect
	{
		return $this->main;
	}
	
	abstract public function buildExists(string $name) : bool;
	
	abstract public function createBuild(Build $build) : void;
	
	abstract public function deleteBuild(Build $build) : void;
	
	abstract public function getBuildByName(string $name) : Build|null;
	
	abstract public function getBuildById(int $id) : Build|null;
	
	abstract public function getBuilds() : array;
	
	abstract public function countBuilds() : int;
	
	abstract public function open() : void;
	
	abstract public function save() : void;
	
	abstract public function close() : void;
}