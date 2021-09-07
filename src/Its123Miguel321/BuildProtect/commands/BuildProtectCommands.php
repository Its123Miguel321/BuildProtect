<?php

namespace Its123Miguel321\BuildProtect\commands;

use Its123Miguel321\BuildProtect\BuildProtect;

use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class BuildProtectCommands extends Command implements PluginIdentifiableCommand
{
	/** @var BuildProtect $plugin */
	public $plugin;
	
	
	
	/**
	 * BuildProtectCommands constructer.
	 * 
	 * @param BuildProtect $main
	 *
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
		parent::__construct("BuildProtect");
		$this->setDescription("BuildProtectCommands");
		$this->setUsage("§cUnknown command, try /buildprotect help!");
		$this->setAliases(["bp"]);
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if(!($sender instanceof Player))
		{
			$sender->sendMessage("You must use this command in-game!");
			return;
		}
		
		if(!(isset($args[1])))
		{
			$sender->sendMessage($this->getUsage());
			return;
		}
		
		switch($args[1])
		{
			case "protect":
			case "p":
				if(!($sender->hasPermission("buildprotect.wand") || $sender->hasPermission("buildprotect.admin")))
				{
					$sender->sendMessage("§l§c(!) §r§7You do not have permission to use this command");
					return;
				}
				
				// Ty sn3akrr for this part :D
				$data = explode(":", $this->getMain()->getConfig()->get("ItemID"));
				$id = $data[0];
				$meta = $data[1];
				
				$item = Item::get($id, $meta);
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(100), 1));
				$item->setCustomName($this->getMain()->getConfig()->get("WandName"));
				
				if(!($sender->getInventory()->canAddItem($item)))
				{
					$sender->sendMessage("§l§c(!) §r§7Can not add item to your inventory!");
					return;
				}
				
				$sender->getInventory()->addItem($item);
				$sender->sendMessage("§l§a(!) §r§7Added " . $this->getMain()->getConfig("WandName") . " §r§7to your inventory!");
			break;
			break;
				
			case "save":
			case "s":
				if(!($sender->hasPermission("buildprotect.save") || $sender->hasPermission("buildprotect.admin")))
				{
					$sender->sendMessage("§l§c(!) §r§7You do not have permission to use this command");
					return;
				}
				
				if(!($this->getMain()->getApi()->hasSelection($sender, "pos1") && $this->getMain()->getApi()->hasSelection($sender, "pos2")))
				{
					$sender->sendMessage("§l§c(!) §r§7You must have 2 positions selected!");
					return;
				}
				
				$selections = $this->getMain()->getApi()->getSelections($sender);
				
				if(!($selections["pos1"][3] == $selections["pos2"][3] || $selections["pos2"][3] == $selections["pos1"][3]))
				{
					$sender->sendMessage("§l§c(!) §r§7Selections must be on the same level!");
				}
				
				$form = new CustomForm(function(Player $sender, $data) use($selections) {
					
					if($data === null)
					{
						return;
					}
					
					if($this->getMain()->getApi()->areaExists($data[1]))
					{
						$sender->sendMessage("§l§c(!) §r§7That area name already exists!");
						return;
					}
					
					if(trim($data[1]) == "")
					{
						$sender->sendMessage("§l§c(!) §r§7You must enter a name!");
						return;
					}
					
					$this->getMain()->getApi()->createArea($data[1], $sender->getName(), $selections["pos1"], $selections["pos2"], [], [], $data[3], $data[4], $data[5], $data[6]);
					$sender->sendMessage("§l§a(!) §r§7Successfully created a new area named §6" . $data[1] . "§7!");
				});
				$form->setTitle("");
				$form->addLabel("§7Hello §e" . $sender->getName() . "§7, fill out the form below to create a new area.\n§l§cNOTE: §r§eRemember to add a name and make sure an area with the same name does not exist!\n");
				$form->addInput("§6Enter a name for your area", "area1");
				$form->addLabel("\n§7Toggle any button to the left to set them as false/disabled. Toggle them to the left if you would like them to say true/enabled!\n");
				$form->addToggle("§6BlockBreaking", false);
				$form->addToggle("§6BlockPlacing", false);
				$form->addToggle("§6PvP", false);
				$form->addToggle("§6Flight", false);
				$sender->sendForm($form);
			break;
			break;
			
			case "delete":
			case "d":
				if(!($sender->hasPermission("buildprotect.delete") || $sender->hasPermission("buildprotect.admin")))
				{
					$sender->sendMessage("§l§c(!) §r§7You do not have permission to use this command");
					return;
				}
				
				$builds = $this->getMain()->getProvider()->getAreas();
				$names = [];
					
				foreach(array_values($builds) as $build)
				{
					array_push($names, $build["Name"]);
				}
				
				$form = new CustomForm(function(Player $sender, $data) use($names) {
					
					if($data === null)
					{
						return;
					}
					
					if(!($this->getMain()->getApi()->areaExists($names[$data[1]])))
					{
						$sender->sendMessage("§l§c(!) §r§7This area no longer exists!");
						return;
					}
					
					if(!($data[2]))
					{
						$sender->sendMessage("§l§c(!) §r§7You must toggle the confirmation button to delete this area!");
						return;
					}
					
					$this->getMain()->getApi()->deleteArea($names[$data[1]]);
					$sender->sendMessage("§l§a(!) §r§7You deleted an area named §6" . $names[$data[1]] . "§7!");
				});
				$form->setTitle("");
				$form->addLabel("§7Hello §e" . $sender->getName() . "§7, fill out the form below to delete an area.\n§l§cNote: §r§eToggle the confirmation button to successfully delete an area!\n");
				$form->addDropDown("§6Select an area:", $names);
				$form->addToggle("§6Confirmation", false);
				$sender->sendForm($form);
			break;
			break;
				
			case "edit":
			case "e":
				if(!($sender->hasPermission("buildprotect.edit") || $sender->hasPermission("buildprotect.admin")))
				{
					$sender->sendMessage("§l§c(!) §r§7You do not have permission to use this command");
					return;
				}
				
				$builds = $this->getMain()->getProvider()->getAreas();
				$names = [];
					
				foreach(array_values($builds) as $build)
				{
					array_push($names, $build["Name"]);
				}
				
				if(count($names) == 0)
				{
					$sender->sendMessage("§l§c(!) §r§7No protected areas exist!");
					return;	
				}
				
				$breaking = false;
				$placing = false;
				$pvp = false;
				$flight = false;
				
				$form = new CustomForm(function(Player $sender, $data) use($names){
					
					if($data === null)
					{
						return;
					}
					
					if(!($this->getMain()->getApi()->areaExists($names[$data[1]])))
					{
						$sender->sendMessage("§l§c(!) §r§7Can not edit area, it no longer exists!");
						return;
					}
					
					if($data[8] == true)
					{
						if(!($this->getMain()->getApi()->hasSelection($sender, "pos1") && $this->getMain()->getApi()->hasSelection($sender, "pos2")))
						{
							$sender->sendMessage("§l§c(!) §r§7You must have 2 positions selected!");
							return;
						}
						
						$selections = $this->getMain()->getApi()->getSelections();
						
						if(!($selections["pos1"][3] !== $selections["pos2"][3]))
						{
							$sender->sendMessage("§l§c(!) §r§7Selections must be on the same level!");
							return;
						}
					}
					
					
				});
			break;
			break;
				
			case "addpermission":
			case "ap":
			
			break;
			break;
				
			case "addcommand":
			case "ac":
				
			break;
			break;
				
			case "removepermission":
			case "rp":
			
			break;
			break;
				
			case "removecommand":
			case "rc":
				
			break;
			break;
				
			case "list":
			case "l":
			
			break;
			break;
				
			case "help":
			case "h":
				
			break;
			break;
		}
	}
	
	public function getMain() : Plugin
	{
		$this->main;
	}
	
}
