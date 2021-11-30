<?php

namespace Its123Miguel321\BuildProtect\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

use Its123Miguel321\BuildProtect\subcommands\SubCommand;

class Wand extends SubCommand
{
	/**
	 * Checks if the CommandSender can use this command
	 * 
	 * @param CommandSender $sender
	 * 
	 * @return bool
	 * 
	 */
	public function canUse(CommandSender $sender) : bool
	{
		return ($sender instanceof Player) && $sender->hasPermission('buildprotect.commands');
	}
	
	
	
	/**
	 * Executes the Wand command
	 * 
	 * @param CommandSender $sender
	 * @param array $args
	 * 
	 * @return bool
	 * 
	 */
	public function execute(CommandSender $sender, array $args) : bool
	{
		// Ty sn3akrr for this part :D
		$data = explode(":", $this->getMain()->getConfig()->get("ItemID"));
		$id = $data[0];
		$meta = $data[1];
		
		$item = ItemFactory::getInstance()->get($id, $meta);
		$item->setCustomName($this->getMain()->getConfig()->get('WandName'));
		$item->setLore(['§fBreak §6to select first position', '§fTap/Click §6to select second position']);
		$item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(100), 1));
		
		if(!($sender->getInventory()->canAddItem($item)))
		{
			$sender->sendMessage('§l§c(!) §r§7Your inventory is full!');
			return false;
		}
		
		$sender->getInventory()->addItem($item);
		$sender->sendMessage('§l§a(!) §r§7Added §l§6Protection §fFeather §r§7to your inventory!');
		return true;
	}
}