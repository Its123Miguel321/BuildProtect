<?php

namespace Its123Miguel321\BuildProtect\commands;

use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\EventListener;
use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Save extends Command{

	private $plugin;
	
	public function __construct(BuildProtect $plugin){
		
		$this->plugin = $plugin;
		parent::__construct("protectsave");
		$this->setDescription("protect area between selected areas");
		$this->setUsage("/protectsave");
		$this->setPermission("buildprotect.save");
		$this->setAliases(["ps"]);
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		
		if(!($sender->hasPermission("buildprotect.save") || $sender->hasPermission("build.protect.admin"))){
			$sender->sendMessage("§l§c(!) §r§7You do not have permission to run this command!");
			return;
		}
		
		if(!$sender instanceof Player){
			$sender->sendMessage("You must use this command IN-GAME!");
			return;
		}
		
		if(!($this->plugin->hasSelections($sender->getName(), "pos1") && $this->plugin->hasSelections($sender->getName(), "pos2"))){
			$sender->sendMessage("§l§c(!) §r§7You must have 2 positions selected!");
			return;
		}
		
		if(EventListener::$selections[$sender->getName()]["pos1"]["level"] !== EventListener::$selections[$sender->getName()]["pos2"]["level"]){
			$sender->sendMessage("§l§c(!) §r§7Selections must be on the same level!");
			return;
		}
		
		$form = new CustomForm(function(Player $player, $data){
			
			if($data === null){
				return;
			}
			
			var_dump($data);
			
			if($this->plugin->bpExists($data[1])){
				$player->sendMessage("§l§c(!) §r§7That area name already exists!");
				return;
			}
			
			if($data[1] == " "){
			    $player->sendMessage("§l§c(!) §r§7You must enter a name!");
				return;
			}
			
			$this->plugin->createBP($data[1], EventListener::$selections[$player->getName()]["pos1"], EventListener::$selections[$player->getName()]["pos2"], $data[3], $data[4], $data[5], $data[6]);
			$player->sendMessage("§l§a(!) §r§7Successfully created a new area named §6" . $data[1] . "§7!");
		});
		$form->setTitle("");
		$form->addLabel("§7Hello §e" . $sender->getName() ."§7, fill out the form to create an area.\n\n§l§cNOTE: §r§7You must add a name and make sure this area name does not already exist!\n\n");
		$form->addInput("§6Enter a name for your new area", "Area1");
		$form->addLabel("\n\n§7Toggle any button below to the left to set them as false/disabled.");
		$form->addToggle("§6Block Breaking", true);
		$form->addToggle("§6Block Placing", true);
		$form->addToggle("§6PvP", true);
		$form->addToggle("§6Flight", true);
		$sender->sendForm($form);
	}
}
