<?php

namespace Its123Miguel321\BuildProtect\commands;

use Its123Miguel321\BuildProtect\BuildProtect;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class Protect extends Command implements PluginIdentifiableCommand{

	private $plugin;
	
	public function __construct(BuildProtect $plugin){
		
		$this->plugin = $plugin;
		parent::__construct("buildprotect");
		$this->setDescription("get the build protect wand!");
		$this->setUsage("/buildprotect");
		$this->setPermission("buildprotect.wand");
		$this->setAliases(["bp"]);
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		
		if(!($sender->hasPermission("buildprotect.wand") || $sender->hasPermission("buildprotect.admin"))){
			$sender->sendMessage("§l§c(!) §r§7You do not have permission to run this command!");
			return;
		}
		
		if(!$sender instanceof Player){
			$sender->sendMessage("You must use this command IN-GAME!");
			return;
		}
		
		$item = Item::get($this->plugin->config->get("ItemID"));
		$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(100), 1));
		$item->setCustomName($this->plugin->config->get("WandName"));
		
		if(!$sender->getInventory()->canAddItem($item)){
			$sender->sendMessage("§l§c(!) §r§7Can not add item to your inventory!");
			return;
		}
		
		$sender->getInventory()->addItem($item);
		$sender->sendMessage("§l§a(!) §r§7Added §l§6Protection §fFeather §r§7to your inventory!");
	}
	
	public function getPlugin() : Plugin{
		return $this->plugin;
	}
}
