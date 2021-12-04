<?php

namespace Its123Miguel321\BuildProtect;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

use Its123Miguel321\BuildProtect\BuildProtect;

class EventListener implements Listener
{
	/** @var BuildProtect $plugin */
	public $plugin;
	/** @var array $wandClicks */
	private $wandClicks = [];
	
	
	
	/**
	 * EventListener constructor
	 * 
	 * @param BuildProtect $plugin
	 * 
	 */
	public function __construct(BuildProtect $plugin)
	{
		$this->plugin = $plugin;
	}
	
	
	
	/**
	 * Selects first position
	 * 
	 * @param BlockBreakEvent $event
	 * 
	 */
	public function onPos1(BlockBreakEvent $event) : void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$item = $event->getItem();
		
		if($item->getId() . ':' . $item->getMeta() !== $this->getPlugin()->getConfig()->get('ItemID') && $item->getCustomName() !== $this->getPlugin()->getConfig()->get('WandName')) return;
		if($event->isCancelled()) return;
		
		$event->cancel();
		
		$x = $block->getPosition()->getX();
		$y = $block->getPosition()->getY();
		$z = $block->getPosition()->getZ();
		$world = $block->getPosition()->getWorld()->getFolderName();
		
		$this->getPlugin()->getApi()->setSelection($player, 'pos1', array($x, $y, $z, $world));
		
		$player->sendMessage('§l§e(!) §r§7Selected first position at §6' . $x . '§7, §6' . $y . '§7, §6' . $z . ' §7in §6' . $world . '§7!');
	}
	
	
	
	/**
	 * Selects second position
	 * 
	 * @param PlayerInteractEvent $event
	 * 
	 */
	public function onPos2(PlayerInteractEvent $event) : void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$item = $event->getItem();
		
		if($item->getId() . ':' . $item->getMeta() !== $this->getPlugin()->getConfig()->get('ItemID') && $item->getCustomName() !== $this->getPlugin()->getConfig()->get('WandName')) return;
		if($event->isCancelled()) return;
		
		$event->cancel();
		
		if($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
		if(isset($this->wandClicks[$player->getName()]) && microtime(true) - $this->wandClicks[$player->getName()] < 0.5) return;

		$this->wandClicks[$player->getName()] = microtime(true);
		
		$x = $block->getPosition()->getX();
		$y = $block->getPosition()->getY();
		$z = $block->getPosition()->getZ();
		$world = $block->getPosition()->getWorld()->getFolderName();
		
		$this->getPlugin()->getApi()->setSelection($player, 'pos2', array($x, $y, $z, $world));
		
		$player->sendMessage('§l§e(!) §r§7Selected second position at §6' . $x . '§7, §6' . $y . '§7, §6' . $z . ' §7in §6' . $world . '§7!');
	}
	
	
	
	/**
	 * Cancels block breaking if in a area that does not allow it
	 * 
	 * @param BlockBreakEvent $event
	 * 
	 */
	public function onBreak(BlockBreakEvent $event) : void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		
		if($event->isCancelled()) return;
		if($player->getGamemode() === GameMode::CREATIVE() || $player->hasPermission('buildprotect.bypass')) return;
		if(!($this->getPlugin()->getApi()->isInside($block->getPosition()))) return;
		
		$areas = $this->getPlugin()->getApi()->getAreasIn($block->getPosition());
		$allow = true;
		$highest = -1;
		
		foreach($areas as $build)
		{
			if($build->getPriority() > $highest)
			{
				$allow = $build->getSetting('breaking');
				$highest = $build->getPriority();
			}
		}
		
		if(!($allow))
		{
			if($this->getPlugin()->getConfig()->get('Messages')) $player->sendMessage($this->getPlugin()->getConfig()->get('BreakingDisabled'));
			
			$event->cancel();
		}
			
	}
	
	
	
	/**
	 * Cancels block placing if in a area that does not allow it
	 * 
	 * @param BlockPlaceEvent $event
	 * 
	 */
	public function onPlace(BlockPlaceEvent $event) : void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		
		if($event->isCancelled()) return;
		if($player->getGamemode() === GameMode::CREATIVE() || $player->hasPermission('buildprotect.bypass')) return;
		if(!($this->getPlugin()->getApi()->isInside($block->getPosition()))) return;
		
		$areas = $this->getPlugin()->getApi()->getAreasIn($block->getPosition());
		$allow = true;
		$highest = -1;
		
		foreach($areas as $build)
		{
			if($build->getPriority() > $highest)
			{
				$allow = $build->getSetting('placing');
				$highest = $build->getPriority();
			}
		}
		
		if(!($allow))
		{
			if($this->getPlugin()->getConfig()->get('Messages')) $player->sendMessage($this->getPlugin()->getConfig()->get('PlacingDisabled'));
			
			$event->cancel();
		}
	}
	
	
	
	/**
	 * Cancels pvp if in a area that does not allow it
	 * 
	 * @param EntityDamageByEntityEvent $event
	 * 
	 */
	public function onAttack(EntityDamageByEntityEvent $event) : void
	{
		$victim = $event->getEntity();
		$attacker = $event->getDamager();
		
		if($event->isCancelled()) return;
		if(!($victim instanceof Player) || !($attacker instanceof Player)) return;
		if($player->getGamemode() === GameMode::CREATIVE() || $attacker->hasPermission('buildprotect.bypass')) return;
		if(!($this->getPlugin()->getApi()->isInside($victim->getPosition())) || !($this->getPlugin()->getApi()->isInside($attacker->getPosition()))) return;
		
		$areas1 = $this->getPlugin()->getApi()->getAreasIn($victim->getPosition());
		$areas2 = $this->getPlugin()->getApi()->getAreasIn($attacker->getPosition());
		$highest1 = -1;
		$highest2 = -1;
		$a1 = null;
		$a2 = null;

		foreach($areas1 as $area1)
		{
		  	if($area1->getPriority() > $highest1)
		  	{
		      $a1 = $area1;
		      $highest1 = $area1->getPriority();
		   	}  
		}

		foreach($areas2 as $area2)
		{
		  	if($area2->getPriority() > $highest2)
		  	{
		      $a2 = $area2;
		      $highest2 = $area2->getPriority();
		   	}  
		}

		$allow = ($a1->getSetting('pvp') === true && $a2->getSetting('pvp') === true);
		
		if(!($allow))
		{
			if($this->getPlugin()->getConfig()->get('Messages')) $attacker->sendMessage($this->getPlugin()->getConfig()->get('AttackingDisabled'));
			
			$event->cancel();
		}
	}
	
	
	
	/**
	 * Cancels flight if in a area that does not allow it
	 * 
	 * @param PlayerMoveEvent $event
	 * 
	 */
	public function onMove(PlayerMoveEvent $event) : void
	{
		$player = $event->getPlayer();
		
		if($event->isCancelled()) return;
		if($player->getGamemode() === GameMode::CREATIVE() || $player->hasPermission('buildprotect.bypass')) return;
		if(!($this->getPlugin()->getApi()->isInside($player->getPosition()))) return;
		
		$areas = $this->getPlugin()->getApi()->getAreasIn($player->getPosition());
		$allow = true;
		$highest = -1;
		
		foreach($areas as $build)
		{
			if($build->getPriority() > $highest)
			{
				$allow = $build->getSetting('flight');
				$highest = $build->getPriority();
			}
		}
		
		if(!($allow))
		{
			if($this->getPlugin()->getConfig()->get('Messages')) $player->sendMessage($this->getPlugin()->getConfig()->get('FlyingDisabled'));
			
			$player->setFlying(false);
			$player->setAllowFlight(false);
		}
	}
	
	
	
	/**
	 * Returns BuildProtect
	 * 
	 * @return BuildProtect
	 * 
	 */
	public function getPlugin() : BuildProtect
	{
		return $this->plugin;
	}
}
