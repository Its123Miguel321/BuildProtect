<?php

namespace Its123Miguel321\BuildProtect;

use Its123Miguel321\BuildProtect\BuildProtect;

class Area
{
	/** @var BuildProtect $plugin */
	public $plugin;
	
	/** @var int $id */
	public $id;
	/** @var string $name */
	public $name;
	/** @var string $creator */
	public $creator;
	/** @var string[] $pos1 */
	public $pos1;
	/** @var string[] $pos2 */
	public $pos2;
	/** @var string[] $commands */
	public $commands;
	/** @var string[] $permissions */
	public $permissions;
	/** @var bool $blockBreaking */
	public $blockBreaking;
	/** @var bool $blockPlacing */
	public $blockPlacing;
	/** @var bool $pvp */
	public $pvp;
	/** @var bool $flight */
	public $flight;
	
	
	
	/**
	 * Area Constructer
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $creator
	 * @param array $pos1
	 * @param array $pos2
	 * @param array $commands
	 * @param array $permissions
	 * @param bool $blockBreaking
	 * @param bool $blockPlacing
	 * @param bool $pvp
	 * @param bool $flight
	 */
	public function __construct(int $id = -1, string $name = "", string $creator = "", array $pos1 = [], array $pos2 = [], array $commands = [], array $permissions = [], bool $blockBreaking = true, bool $blockPlacing = true, bool $pvp = true, bool $flight = true)
	{
		$this->plugin = BuildProtect::getInstance();
		
		$this->id = $id;
		$this->name = $name;
		$this->creator = $creator;
		$this->pos1 = $pos1;
		$this->pos2 = $pos2;
		$this->commands = $commands;
		$this->permissions = $permissions;
		$this->blockBreaking = $blockBreaking;
		$this->blockPlacing = $blockPlacing;
		$this->pvp = $pvp;
		$this->flight = $flight;
	}
	
	/** 
	 * @param string $command
	 */
	public function addCommand(string $command) : void
	{
		$this->commands[] = $command;
	}
	
	/** 
	 * @param string $permission 
	 */
	public function addPermission(string $permission) : void
	{
		$this->permissions[] = $permission;
	}
	
	public function getCreator() : string
	{
		return $this->creator;
	}
	
	public function getCommands() : array
	{
		return $this->commands;
	}
	
	public function getId() : int
	{
		return $this->id;
	}
	
	public function getName() : string
	{
		return $this->name;
	}
	
	public function getPermissions() : array
	{
		return $this->permissions;
	}
	
	public function getPos1() : array
	{
		return $this->pos1;
	}
	
	public function getPos2() : array
	{
		return $this->pos2;
	}
	
	/**
	 * Returns the setting
	 * 
	 * @param string $setting
	 *
	 * @return bool
	 * 
	 */
	public function getSetting(string $setting) : bool
	{
		$setting = strtolower($setting);
		
		if($setting === "breaking") {
			return $this->blockBreaking;
		} elseif($setting === "placing") {
			return $this->blockPlacing;
		} elseif($setting === "pvp") {
			return $this->pvp;
		} elseif($setting === "flight") {
			return $this->flight;
		} else {
			return false;
		}
	}
	
	
	
	/**
	 * @param string $command
	 */
	public function removeCommand(string $command) : void
	{
		$key = array_search($command, $this->commands);
		unset($this->commands[$key]);
	}
	
	/**
	 * @param string $permission
	 */
	public function removePermission(string $permission) : void
	{
		$key = array_search($permission, $this->permissions);
		unset($this->permissions[$key]);
	}
	
	/**
	 * @param string $creator
	 */
	public function setCreator(string $creator) : void
	{
		$this->creator = $creator;
	}
	
	/**
	 * @param string $name
	 */
	public function setName(string $name) : void
	{
		$this->name = $name;
	}
	
	/**
	 * @param array $pos
	 */
	public function setPos1(array $pos) : void
	{
		$this->pos1 = ["X" => $pos[0], "Y" => $pos[1], "Z" => $pos[2]];
	}
	
	/**
	 * @param array $pos
	 */
	public function setPos2(array $pos) : void
	{
		$this->pos2 = ["X" => $pos[0], "Y" => $pos[1], "Z" => $pos[2]];
	}
	
	/**
	 * @param bool $breaking
	 * @param bool $placing
	 * @param bool $pvp
	 * @param bool $flight
	 */
	public function toggleSettings(bool $breaking, bool $placing, bool $pvp, bool $flight) : void
	{
		$this->blockBreaking = $breaking;
		$this->blockPlacing = $placing;
		$this->pvp = $pvp;
		$this->flight = $flight;
	}
}
