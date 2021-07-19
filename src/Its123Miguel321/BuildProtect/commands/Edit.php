<?php

namespace Its123Miguel321\BuildProtect\commands;

use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\EventListener;
use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Edit extends Command{

    public $plugin;
    
    public function __construct(BuildProtect $plugin){
        $this->plugin = $plugin;
        parent::__construct("protectedit");
        $this->setDescription("edit a protect area!");
        $this->setUsage("/protectedit");
        $this->setPermission("build.protect.edit");
        $this->setAliases(["pe"]);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        
		if(!($sender->hasPermission("build.protect.delete") || $sender->hasPermission("build.protect.build.protect.admin"))){
			$sender->sendMessage("§l§c(!) §r§7You do not have permission to run this command!");
			return;
		}
		
		if(!$sender instanceof Player){
			$sender->sendMessage("You must use this command IN-GAME!");
			return;
		}
		
		$builds = $this->plugin->builds->get("builds", []);
		$names = [];
		
		foreach(array_values($builds) as $build){
		    array_push($names, $build["name"]);
		}
		
		if(count($names) == 0){
		    $sender->sendMessage("§l§c(!) §r§7There are no protected areas!");
		    return;
		}
		
		$form = new CustomForm(function(Player $player, $data) use($names, $builds){
			
			if($data === null){
				return;
			}
			
			if(!$this->plugin->bpExists($names[$data[1]])){
			    $player->sendMessage("§l§c(!) §r§7That area no longer exists!");
			    return;
			}
			
			if($data[8] == true){
			    if(!($this->plugin->hasSelections($player->getName(), "pos1") && $this->plugin->hasSelections($player->getName(), "pos2"))){
			        $player->sendMessage("§l§c(!) §r§7You must have 2 positions selected!");
			        return;
		        }
		
		        if(EventListener::$selections[$player->getName()]["pos1"]["level"] !== EventListener::$selections[$player->getName()]["pos2"]["level"]){
			        $player->sendMessage("§l§c(!) §r§7Selections must be on the same level!");
			        return;
		        }
		        
		        $this->plugin->deleteBP($names[$data[1]]);
		        $this->plugin->createBP($names[$data[1]], EventListener::$selections[$player->getName()]["pos1"], EventListener::$selections[$player->getName()]["pos2"], $data[3], $data[4], $data[5], $data[6]);
		        $player->sendMessage("§l§a(!) §r§7You edited an area named §6" . $names[$data[1]] . "§7!");
		        return;
			}
			
			$this->plugin->deleteBP($names[$data[1]]);
			$this->plugin->createBP($names[$data[1]], $builds[$names[$data[1]]]["pos1"], $builds[$names[$data[1]]]["pos2"], $data[3], $data[4], $data[5], $data[6]);
			$player->sendMessage("§l§a(!) §r§7You edited an area named §6" . $names[$data[1]] . "§7!");
			
		});
		$form->setTitle("");
		$form->addLabel("§7Hello §e" . $sender->getName() ."§7, fill out the form to edit an area.\n\n");
		$form->addDropDown("§6Select an area:", $names);
		$form->addLabel("\n§7Toggle any button below to the left to set them as false/disabled.");
		$form->addToggle("§6Block Breaking", true);
		$form->addToggle("§6Block Placing", true);
		$form->addToggle("§6PvP", true);
		$form->addToggle("§6Flight", true);
		$form->addLabel("\n§7Toggle the button to the right to use the current selections as the new coordinates.");
		$form->addToggle("§6New Coords", false);
		$sender->sendForm($form);
    }
}
