<?php

namespace Its123Miguel321\BuildProtect\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use Its123Miguel321\BuildProtect\Commands;
use Its123Miguel321\BuildProtect\subcommands\SubCommand;

class Help extends SubCommand
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
	 * Executes the Help command
	 * 
	 * @param CommandSender $sender
	 * @param array $args
	 * 
	 * @return bool
	 * 
	 */
	public function execute(CommandSender $sender, array $args) : bool
	{
		$help = "§l§cHelp Page§r\n";
		
		$help .= "§f" . str_repeat('=', 35) . "\n\n";
		
		foreach(Commands::getSubCommands() as $command)
		{
			$help .= "§6" . $command->getName() . " §7- " . $command->getDescription() . "!§r\n";
		}
		
		$help .= "§f" . str_repeat('=', 35) . "\n\n";
		
		$sender->sendMessage($help);
		return true;
	}
}