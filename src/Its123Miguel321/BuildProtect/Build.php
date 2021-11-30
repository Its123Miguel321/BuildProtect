<?php

namespace Its123Miguel321\BuildProtect;

class Build
{
	/** @var int $id */
	public $id = -1;
	/** @var string $name */
	public $name = " ";
	/** @var string $creator */
	public $creator;
	/** @var int $priority */
	public $priority = -1;
	/** @var array $pos1 */
	public $pos1 = [];
	/** @var array $pos2 */
	public $pos2 = [];
	/** @var bool $breaking */
	public $breaking = false;
	/** @var bool $placing */
	public $placing = false;
	/** @var bool $pvp */
	public $pvp = false;
	/** @var bool $flight */
	public $flight = false;
	
	
	
	/**
	 * Build constructor
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $creator
	 * @param int $priority
	 * @param array $pos1
	 * @param array $pos2
	 * @param bool $breaking
	 * @param bool $placing
	 * @param bool $pvp
	 * @param bool $flight
	 * 
	 */
	public function __construct(int $id = -1, string $name = " ", string $creator = " ", int $priority = -1, array $pos1 = [], array $pos2 = [], bool $breaking = false, bool $placing = false, bool $pvp = false, bool $flight = false)
	{
		$this->id = $id;
		$this->name = $name;
		$this->creator = $creator;
		$this->priority = $priority;
		$this->pos1 = $pos1;
		$this->pos2 = $pos2;
		$this->breaking = $breaking;
		$this->placing = $placing;
		$this->pvp = $pvp;
		$this->flight = $flight;
	}
	
	
	
	/**
	 * Gets id
	 * 
	 * @return int
	 * 
	 */
	public function getId() : int
	{
		return $this->id;
	}
	
	
	
	/**
	 * Gets name
	 * 
	 * @return string
	 * 
	 */
	public function getName() : string
	{
		return $this->name;
	}
	
	
	
	/**
	 * Gets creator
	 * 
	 * @return string
	 * 
	 */
	public function getCreator() : string
	{
		return $this->creator;
	}
	
	
	
	/**
	 * Gets priority
	 * 
	 * @return int
	 * 
	 */
	public function getPriority() : int
	{
		return $this->priority;
	}
	
	
	
	/**
	 * Gets first position
	 * 
	 * @return array
	 * 
	 */
	public function getPos1() : array
	{
		return $this->pos1;
	}
	
	
	
	/**
	 * Gets second position
	 * 
	 * @return array
	 * 
	 */
	public function getPos2() : array
	{
		return $this->pos2;
	}
	
	
	
	/**
	 * Gets a setting value(Breaking, Placing, PvP, Flight)
	 * 
	 * @return bool
	 * 
	 */
	public function getSetting(string $setting) : bool
	{
		$setting = strtolower($setting);
		
		if($setting === 'breaking') return $this->breaking;
		if($setting === 'placing') return $this->placing;
		if($setting === 'pvp') return $this->pvp;
		if($setting === 'flight') return $this->flight;
		
		return false;
	}
	
	
	
	/**
	 * Sets id
	 * 
	 * @param int $id
	 * 
	 */
	public function setId(int $id) : void
	{
		$this->id = $id;
	}
	
	
	
	/**
	 * Sets name
	 * 
	 * @param string $name
	 * 
	 */
	public function setName(string $name) : void
	{
		$this->name = $name;
	}
	
	
	
	/**
	 * Sets creator
	 * 
	 * @param string $creator
	 * 
	 */
	public function setCreator(string $creator) : void
	{
		$this->creator = $creator;
	}
	
	
	
	/**
	 * Sets priority
	 * 
	 * @param int $priority
	 * 
	 */
	public function setPriority(int $priority) : void
	{
		$this->priority = $priority;
	}
	
	
	
	/**
	 * Sets first position
	 * 
	 * @param array $pos1
	 * 
	 */
	public function setPos1(array $pos1) : void
	{
		$this->pos1 = $pos1;
	}
	
	
	
	/**
	 * Sets second position
	 * 
	 * @param array $pos2
	 * 
	 */
	public function setPos2(array $pos2) : void
	{
		$this->pos2 = $pos2;
	}
	
	
	
	/**
	 * Sets the value of a setting(Breaking, Placing, PvP, Flight)
	 * 
	 * @param string $setting
	 * @param bool $value
	 * 
	 */
	public function settingValue(string $setting, bool $value = false) : void
	{
		$setting = strtolower($setting);
		
		if($setting === 'breaking') $this->breaking = $value;
		if($setting === 'placing') $this->placing = $value;
		if($setting === 'pvp') $this->pvp = $value;
		if($setting === 'flight') $this->flight = $value;
		
		return;
	}
}