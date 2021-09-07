<?php

namespace Its123Miguel321\BuildProtect;

use Its123Miguel321\BuildProtect\BuildProtect;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class EventListener implements Listener{

	/** @var BuildProtect $main */
	public $main;
	/** @var array $wandClicks */
	private $wandClicks = [];
	/** @var array $selections */
	public static $selections = [];
	
	
	
	/**
	 * EventListener constructer.
	 *
	 * @param BuildProtect $main
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->plugin = $plugin;
	}
	
	
	
	/**
	 * Checks if a player is getting hit in an area where pvp is disabled.
	 *
	 * @param EntityDamageEventByEntity $event
	 * 
	 */
	public function onAttack(EntityDamageByEntityEvent $event)
	{
		$victim = $event->getEntity();
		$attacker = $event->getDamager();
		
		if($this->getMain()->getApi()->isInside($attacker->asPosition()) || $this->getMain()->getApi()->isInside($victim->asPosition()))
		{
			foreach($this->getMain()->getProvider()->getAreas() as $area)
			{
				$areaName = $area["Name"];
				
				if($attacker->hasPermission("buildprotect.bypass") || $attacker->hasPermission("buildprotect.admin") || $this->getMain()->getApi()->playerHasAreaPermissions($attacker, $areaName)))
				{
					return;
				}
				
				if($areas["PvP"] == false)
				{
	            	if($this->getMain()->getConfig()->get("AreaMessages"))
					{
	            		$player->sendMessage($this->getMain()->getConfig()->get("AttackingDisabled"));
					}
						
					$event->setCancelled();
	        	}
			}
		}
	}
	
	
	
	/**
	 * Checks if a player is breaking a block in an area where it is disabled.
	 * 
	 * @param BlockBreakEvent $event
	 * 
	 */
	public function onBreak(BlockBreakEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		
		if($event->isCancelled())
		{
			return;
		}
		
		if($this->getMain()->getApi()->isInside($block->asPosition()))
		{
			foreach($this->getMain()->getProvider()->getAreas() as $area)
			{
				$areaName = $area["Name"];
				
				if($player->hasPermission("buildprotect.bypass") || $player->hasPermission("buildprotect.admin") || $this->getMain()->getApi()->playerHasAreaPermissions($player, $areaName)))
				{
					return;
				}
				
				if($areas["BlockBreaking"] == false)
				{
	            	if($this->getMain()->getConfig()->get("AreaMessages"))
					{
	            		$player->sendMessage($this->getMain()->getConfig()->get("BreakingDisabled"));
					}
						
					$event->setCancelled();
	        	}
			}
		}
	}
	
	
	
	/**
	 * Checks if a player is placing a block in an area where it is disabled.
	 * 
	 * @param BlockPlaceEvent $event
	 * 
	 */
	public function onPlace(BlockPlaceEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		
		if($event->isCancelled())
		{
			return;
		}
		
		if($this->getMain()->getApi()->isInside($block->asPosition()))
		{
			foreach($this->getMain()->getProvider()->getAreas() as $area)
			{
				$areaName = $area["Name"];
				
				if($player->hasPermission("buildprotect.bypass") || $player->hasPermission("buildprotect.admin") || $this->getMain()->getApi()->playerHasAreaPermissions($player, $areaName)))
				{
					return;
				}
				
				if($area["BlockPlacing"] == false)
				{
					if($this->getMain()->getConfig()->get("AreaMessages"))
					{
						$player->sendMessage($this->getMain()->getConfig()->get("PlacingDisabled"));
					}
				
					$event->setCancelled();
				}
			}
		}
	}
	
	
	
	/**
	 * Checks if a player has entered an area
	 * 
	 * @param PlayerMoveEvent $event
	 * 
	 */
	public function onMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		
		if($event->isCancelled())
		{
			return;
		}
		
		if($this->getMain()->getApi()->isInside($player->asPosition()))
		{
			foreach($this->getMain()->getProvider()->getAreas() as $area)
			{
				$areaName = $area["Name"];
				
				if($player->hasPermission("buildprotect.bypass") || $player->hasPermission("buildprotect.admin") || $this->getMain()->getApi()->playerHasAreaPermissions($player, $areaName)))
				{
					return;
				}
				
				// Note to self: fix this so it only runs once when the player enters the area!
				$this->getMain()->getApi()->runAreaCommands($areaName, $player);
				
				if($area["Flight"] == true)
				{
					if(!($player->getGamemode() == 0))
					{
						$player->setAllowFlight(false);
						$player->setFlying(false);
					}
				}
			}
		}
	}
	
	public function onPos1(BlockBreakEvent $event){
		
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$item = $event->getItem();
		
		if($item->getId() . ":" . $item->getDamage() !== $this->plugin->config->get("ItemID") && $item->getName() !== $this->plugin->config->get("WandName")){
			return;
		}
		
		if($event->isCancelled()){
			return;
		}
		
		$event->setCancelled();
		
		if(isset($this->wandClicks[$player->getName()]) && microtime(true) - $this->wandClicks[$player->getName()] < 0.5) {
            return;
        }
        
        $this->wandClicks[$player->getName()] = microtime(true);
		
		$x = $block->getX();
		$y = $block->getY();
		$z = $block->getZ();
		$level = $block->getLevel()->getName();
		
		self::$selections[$player->getName()]["pos1"] = ["x" => $x, "y" => $y, "z" => $z, "level" => $level];
		
		$player->sendMessage("§l§a(!) §r§7Selected first position at §6" . $x . "§7, §6" . $y . "§7, §6" . $z . " §7in §6" . $level . "§7!");
	}
	
	public function onPos2(PlayerInteractEvent $event){
		
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$item = $event->getItem();
		
		if($item->getId() . ":" . $item->getDamage() !== $this->plugin->config->get("ItemID") && $item->getName() !== $this->plugin->config->get("WandName")){
			return;
		}
		
		if($event->isCancelled()){
			return;
		}
		
		$event->setCancelled();
		
		if($event->getAction() !== $event::RIGHT_CLICK_BLOCK){
			return;
		}
		
		if(isset($this->wandClicks[$player->getName()]) && microtime(true) - $this->wandClicks[$player->getName()] < 0.5) {
            return;
        }
        
        $this->wandClicks[$player->getName()] = microtime(true);
		
		$x = $block->getX();
		$y = $block->getY();
		$z = $block->getZ();
		$level = $block->getLevel()->getName();
		
		self::$selections[$player->getName()]["pos2"] = ["x" => $x, "y" => $y, "z" => $z, "level" => $level];
		
		$player->sendMessage("§l§a(!) §r§7Selected second position at §6" . $x . "§7, §6" . $y . "§7, §6" . $z . " §7in §6" . $level . "§7!");
	}
		 
	public function getMain() : Plugin
	{
		return $this->main;
	}
}
