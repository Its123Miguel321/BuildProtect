<?php

namespace Its123Miguel321\BuildProtect\API;

use pocketmine\player\Player;
use pocketmine\world\Position;

use Its123Miguel321\BuildProtect\BuildProtect;

class BuildsAPI
{
	/** @var BuildProtect $main */
	public $main;
	/** @var array $selections */
	public $selections = [];
	/** @var array $mode */
	public $mode = [];
	
	
	
	/**
	 * BuildsAPI constructor
	 * 
	 * @param BuildProtect $main
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
	}
	
	
	
	/**
	 * Checks if the player is in the protection mode
	 * 
	 * @param Player|string $player
	 *
	 * @return bool
	 *
	 */
	public function inMode(Player|string $player) : bool
	{
		if($player instanceof Player) $player = $player->getName();
		
		return in_array($player, $this->mode);
	}
	
	
	
	/**
	 * Set in the protection mode
	 *
	 * @param Player|string $player
	 * @param bool $value
	 *
	 */
	public function setInMode(Player|string $player, bool $value = true) : void
	{
		if($player instanceof Player) $player = $player->getName();
		
		if($value)
		{
			$this->mode[] = $player;
		}else{
			unset($this->mode[array_search($player, $this->mode)]);
		}
	}
	
	
	
	/**
	 * Gets a player's selection
	 * 
	 * @param $player
	 * @param string $selection
	 * 
	 * @return array
	 * 
	 */
	public function getSelection($player, string $selection) : array
	{
		if($player instanceof Player) $player = $player->getName();
		if(!(isset($this->selections[$player]))) return [];
		
		return $this->selections[$player][$selection];
	}
	
	
	
	/**
	 * Sets a player's selection
	 * 
	 * @param $player
	 * @param string $selection
	 * @param array $pos
	 * 
	 */
	public function setSelection($player, string $selection, array $pos) : void
	{
		if($player instanceof Player) $player = $player->getName();
		
		$this->selections[$player][$selection] = $pos;
	}
	
	
	
	/**
	 * Checks if a player has a selection
	 * 
	 * @param $player
	 * @param string $selection
	 * 
	 * @return bool
	 * 
	 */
	public function hasSelections($player, string $selection) : bool
	{
		if($player instanceof Player) $player = $player->getName();
		if(!(isset($this->selections[$player]))) return false;
		
		return isset($this->selections[$player][$selection]);
		
	}
	
	
	
	/**
	 * Checks if a player is inside an Area
	 * 
	 * @param Position $pos
	 * 
	 * @return bool
	 * 
	 */
	public function isInside(Position $pos) : bool
	{
		$builds = $this->getMain()->getManager()->getBuilds();
		
		foreach($builds as $build)
		{
			$pos1 = $build->getPos1();
			$pos2 = $build->getPos2();
			
			if(!($pos->getWorld()->getFolderName() === $pos1[3])) continue;
			
			$minX = $pos1[0];
			$maxX = $pos2[0];
			
			if($minX > $maxX)
			{
				$mminX = $maxX;
				$maxX = $minX;
				$minX = $mminX;
			}
			
			$minY = $pos1[1];
			$maxY = $pos2[1];
			
			if($minY > $maxY)
			{
				$mminY = $maxY;
				$maxY = $minY;
				$minY = $mminY;
			}
			
			$minZ = $pos1[2];
			$maxZ = $pos2[2];
			
			if($minZ > $maxZ)
			{
				$mminZ = $maxZ;
				$maxZ = $minZ;
				$minZ = $mminZ;
			}

			if($pos->getX() >= $minX && $maxX >= $pos->getX())
			{
				if($pos->getY() >= $minY && $maxY >= $pos->getY())
				{
					if($pos->getZ() >= $minZ && $maxZ >= $pos->getZ())
					{
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	
	
	/**
	 * Gets the areas a player/block is in
	 * 
	 * @param Position $pos
	 * 
	 * @return array
	 * 
	 */
	public function getAreasIn(Position $pos) : array
	{
		$areas = [];
		$builds = $this->getMain()->getManager()->getBuilds();
		
		foreach($builds as $build)
		{
			$pos1 = $build->getPos1();
			$pos2 = $build->getPos2();

			if($pos->getWorld()->getFolderName() != $pos1[3]) continue;

			$minX = $pos1[0];
			$maxX = $pos2[0];
			
			if($minX > $maxX)
			{
				$mminX = $maxX;
				$maxX = $minX;
				$minX = $mminX;
			}
			
			$minY = $pos1[1];
			$maxY = $pos2[1];
			
			if($minY > $maxY)
			{
				$mminY = $maxY;
				$maxY = $minY;
				$minY = $mminY;
			}
			
			$minZ = $pos1[2];
			$maxZ = $pos2[2];
			
			if($minZ > $maxZ)
			{
				$mminZ = $maxZ;
				$maxZ = $minZ;
				$minZ = $mminZ;
			}

			if($pos->getX() >= $minX && $maxX >= $pos->getX())
			{
				if($pos->getY() >= $minY && $maxY >= $pos->getY())
				{
					if($pos->getZ() >= $minZ && $maxZ >= $pos->getZ())
					{
						$areas[] = $build;
					}
				}
			}
		}
		
		return $areas;
	}
	
	
	
	/**
	 * Returns BuildProtect
	 * 
	 * @return BuildProtect
	 * 
	 */
	public function getMain() : BuildProtect
	{
		return $this->main;
	}
}
