<?php

namespace Its123Miguel321\BuildProtect\commands;

use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\EventListener;
use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Delete extends Command{

	private $plugin;
	
	public function __construct(BuildProtect $plugin){
		
		$this->plugin = $plugin;
		parent::__construct("protectdelete");
		$this->setDescription("delete a protected area");
		$this->setUsage("/protectdelete");
		$this->setPermission("build.protect.delete");
		$this->setAliases(["pd"]);
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
		
		$form = new CustomForm(function(Player $player, $data) use($names){
			
			if($data === null){
				return;
			}
			
			if(!$this->plugin->bpExists($names[$data[1]])){
			    $player->sendMessage("§l§c(!) §r§7That area no longer exists!");
			    return;
			}
			
			if($data[2] == false){
			    $player->sendMessage("§l§c(!) §r§7You must toggle the confirmation button to delete this area!");
			    return;
			}
			
			$this->plugin->deleteBP($names[$data[1]]);
			$player->sendMessage("§l§a(!) §r§7You delete an area named §6" . $names[$data[1]] . "§7!");
			
		});
		$form->setTitle("");
		$form->addLabel("§7Hello §e" . $sender->getName() ."§7, fill out the form to delete an area.\n\n§l§cNOTE: §r§7You must toggle the confirmation button to delete an area!\n\n");
		$form->addDropDown("§6Select an area:", $names);
		$form->addToggle("§6Confirmation", false);
		$sender->sendForm($form);
	}
}
