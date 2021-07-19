<?php

namespace ReformedDevs\ReformedNetwork_Core\BuildProtect;

use Its123Miguel321\BuildProtect\commands\Save;
use Its123Miguel321\BuildProtect\commands\Delete;
use Its123Miguel321\BuildProtect\commands\Edit;
use Its123Miguel321\BuildProtect\commands\Protect;
use Its123Miguel321\BuildProtectEventListener;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class BuildProtect extends PluginBase implements Listener{
	
	public $config;
	
    public function onEnable(){
        
        $this->config = new Config($this->getDataFolder() . "builds.yml", Config::YAML, ["count" => 0, "builds" => []]);
        
        // Registers EventListener
        $plugin->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        
        // Registers all commands
        $plugin->getServer()->getCommandMap()->registerAll("BuildProtect", [new Protect($this), new Save($this), new Delete($this), new Edit($this)]);
        
        // Registers the BuildProtect enchantment
        Enchantment::RegisterEnchantment(new Enchantment(100, "BuildProtect", Enchantment::RARITY_COMMON, 0, 0, 1));
        
        $plugin->saveResource("builds.yml");
    }
    
	public function bpExists(string $build){
		$config = $this->config->get("builds", []);
		return isset($config[$build]);
	}
	
	public function createBP(string $build, array $pos1, array $pos2, bool $break = true, bool $place = true, bool $pvp = true, bool $fly = true){
		$config = $this->config->get("builds", []);
		if(!$this->bpExists($build)){
			$config[$build] = ["name" => $build, "pos1" => $pos1, "pos2" => $pos2, "PvP" => $pvp, "BlockPlacing" => $place, "BlockBreaking" => $break, "Flight" => $fly];
			$this->config->set("builds", $config);
			$this->config->set("count", $this->config->get("count") + 1);
			$this->config->save();
			return true;
		}
		return false;
	}
	
	public function deleteBP(string $build){
		$config = $this->config->get("builds", []);
		if($this->bpExists($build)){
			unset($config[$build]);
			$this->config->set("builds", $config);
			$this->config->save();
			return true;
		}
		return false;
	}
	
	public function getBP(string $build){
		$config = $this->config->get("builds", []);
		if($this->bpExists($build)){
			return $config[$build];
		}
		return false;
	}
	
	public function isInside(Vector3 $pos){
		foreach($this->config->get("builds", []) as $build){
			$x = array_flip(range($build["pos1"]["x"], $build["pos2"]["x"]));
			$y = array_flip(range($build["pos1"]["y"], $build["pos2"]["y"]));
			$z = array_flip(range($build["pos1"]["z"], $build["pos2"]["z"]));
			if(isset($x[$pos->getX()])){
				if(isset($y[$pos->getY()])){
					if(isset($z[$pos->getZ()])){
						return true;
					}
				}
			}
		}
		return false;
	}
	
	public function hasSelections(string $player, string $selection){
		return isset(EventListener::$selections[$player][$selection]);
	}
}
