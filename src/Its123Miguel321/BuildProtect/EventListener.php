<?php

namespace Its123Miguel321\BuildProtect;

use Its123Miguel321\BuildProtect\BuildProtect;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EventListener implements Listener{

	private $plugin;
	
	private $wandClicks = [];
	
	public static $selections = [];
	
	public function __construct(BuildProtect $plugin){
		$this->plugin = $plugin;
	}
	
	public function onAttack(EntityDamageByEntityEvent $event){
	    
	    $victim = $event->getEntity();
	    $attacker = $event->getDamager();
	    
	    if($event->isCancelled()){
	        return;
	    }
	    
	    if(!($victim instanceof Player && $attacker instanceof Player)){
	        return;
	    }
	    
            if($attacker->hasPermission("buildprotect.bypass")){
            	return;
            }
	    
	    if(!$this->plugin->isInside(new Vector3($attacker->getX(), $attacker->getY(), $attacker->getZ()))){
	        return;
	    }
	    
	    if(!$this->plugin->isInside(new Vector3($victim->getX(), $victim->getY(), $victim->getZ()))){
	        return;
	    }
	    
	    foreach($this->plugin->builds->get("builds", []) as $areas){
	        if($areas["PvP"] == false){
	            $event->setCancelled();
	        }
	    }
	    
	}
	
	public function onBreak(BlockBreakEvent $event){
	    
	    $player = $event->getPlayer();
	    $block = $event->getBlock();
	    
	    if($event->isCancelled()){
	        return;
	    }
	    
	    if($player->hasPermission("buildprotect.bypass")){
                return;
            }
	    
	    if(!$this->plugin->isInside(new Vector3($block->getX(), $block->getY(), $block->getZ()))){
	        return;
	    }
	    
	    foreach($this->plugin->builds->get("builds", []) as $areas){
	        if($areas["BlockBreaking"] == false){
	            $event->setCancelled();
	        }
	    }
	}
	
	public function onPlace(BlockPlaceEvent $event){
	    
	    $player = $event->getPlayer();
	    $block = $event->getBlock();
	    
	    if($event->isCancelled()){
	        return;
	    }
	    
	    if($player->hasPermission("buildprotect.bypass")){
                return;
            }
	    
	    if(!$this->plugin->isInside(new Vector3($block->getX(), $block->getY(), $block->getZ()))){
	        return;
	    }
	    
	    foreach($this->plugin->builds->get("builds", []) as $areas){
	        if($areas["BlockPlacing"] == false){
	            $event->setCancelled();
	        }
	    }
	}
	
	public function onMove(PlayerMoveEvent $event){
	    
	    $player = $event->getPlayer();
	    
	    if($event->isCancelled()){
	        return;
	    }
	    
            if($player->hasPermission("buildprotect.bypass")){
            	return;
            }
	    
	    if(!$this->plugin->isInside(new Vector3($player->getX(), $player->getY(), $player->getZ()))){
	        return;
	    }
	    
	    foreach($this->plugin->builds->get("builds", []) as $areas){
	        if($areas["Flight"] == false){
	            if($player->isFlying()){
	                $player->sendMessage("§l§c(!) §r§7You entered a no fly zone, you have been taken out of flight mode!");
	                $player->setAllowFlight(false);
	                $player->setFlying(false);
	                return;
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
}
