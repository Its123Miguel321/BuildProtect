<?php

namespace Its123Miguel321\BuildProtect;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

use Its123Miguel321\BuildProtect\BuildProtect;
use Its123Miguel321\BuildProtect\subcommands\Delete;
use Its123Miguel321\BuildProtect\subcommands\Edit;
use Its123Miguel321\BuildProtect\subcommands\Help;
use Its123Miguel321\BuildProtect\subcommands\Save;
use Its123Miguel321\BuildProtect\subcommands\SubCommand;
use Its123Miguel321\BuildProtect\subcommands\Wand;

class Commands extends Command implements PluginOwned
{
	/** @var BuildProtect $main */
	public $main;
	/** @var array $subcommands */
	public static $subcommands = [];
	
	
	
	/**
	 * Commands constructor
	 * 
	 * @param BuildProtect $main
	 * 
	 */
	public function __construct(BuildProtect $main)
	{
		$this->main = $main;
		
		parent::__construct('buildprotect');
		$this->setDescription('BuildProtect commands');
		$this->setUsage('§cUnknown command. Try §f/buildprotect help §cfor a list of all commands!');
		$this->setAliases(['bp']);
		$this->setPermission('buildprotect.commands');
		
		self::loadSubCommand(new Delete($this->getOwningPlugin(), 'delete', 'Deletes a protected build'));
		self::loadSubCommand(new Edit($this->getOwningPlugin(), 'edit', 'Edits a protected build'));
		self::loadSubCommand(new Help($this->getOwningPlugin(), 'help', 'Shows help page'));
		self::loadSubCommand(new Save($this->getOwningPlugin(), 'save', 'Saves a new protected build'));
		self::loadSubCommand(new Wand($this->getOwningPlugin(), 'wand', 'Gets BuildProtect wand'));
	}
	
	
	
	/**
	 * Loads subcommand
	 * 
	 * @param SubCommand $command
	 * 
	 */
	public static function loadSubCommand(SubCommand $command) : void
	{
		self::$subcommands[$command->getName()] = $command;
	}
	
	
	
	/**
	 * Unloads subcommand
	 * 
	 * @param $command
	 * 
	 */
	public static function unloadSubCommand($command) : void
	{
		if($command instanceof SubCommand) $command = $command->getName();
		if(!(isset(self::$subcommands[$command]))) return;
		
		unset(self::$subcommands[$command]);
	}
	
	
	
	/**
	 * Gets a subcommand
	 * 
	 * @param string $command
	 * 
	 * @return SubCommand|null
	 * 
	 */
	public static function getSubCommand(string $command) : SubCommand|null
	{
		if(!(isset(self::$subcommands[$command]))) return null;
		
		return self::$subcommands[$command];
	}
	
	
	
	/**
	 * Gets all subcommands
	 * 
	 * @return array
	 * 
	 */
	public static function getSubCommands() : array
	{
		return self::$subcommands;
	}
	
	
	
	/**
	 * Executes command
	 * 
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * 
	 * @return bool
	 * 
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
	{
		if(!(isset($args[0])))
		{
			$args[0] = "help";
		}

		$command = strtolower((string)array_shift($args));

		if(isset(self::$subcommands[$command]))
		{
			$command = self::$subcommands[$command];
		}else
		{
			$sender->sendMessage($this->getUsage());
			return false;
		}

		if(!($command->canUse($sender)))
		{
			$sender->sendMessage("§l§c(!) §r§7You do not have permission to use this command!");
			return false;
		}

		return $command->execute($sender, $args);
	}
	
	
	
	/**
	 * Returns BuildProtect
	 * 
	 * @return BuildProtect
	 * 
	 */
	public function getOwningPlugin() : Plugin
	{
		return $this->main;
	}
}
