<?php

namespace Its123Miguel321\BuildProtect\subcommands;

use pocketmine\command\CommandSender;

use Its123Miguel321\BuildProtect\BuildProtect;

abstract class SubCommand
{
	/** @var BuildProtect $main */
	public $main;
	/** @var string $name */
	public $name;
	/** @var string $description */
	public $description;
	
	
	
	/**
	 * SubCommand constructor
	 * 
	 * @param BuildProtect $main
	 * @param string $name
	 * @param string $description
	 * 
	 */
	public function __construct(BuildProtect $main, string $name, string $description)
	{
		$this->main = $main;
		$this->name = $name;
		$this->description = $description;
	}
	
	
	
	/**
	 * Returns BuildProtect
	 * 
	 * @return BuildProtect
	 * 
	 */
	final public function getMain() : BuildProtect
	{
		return $this->main;
	}
	
	
	
	/**
	 * Returns SubCommand name
	 * 
	 * @return string
	 * 
	 */
	final public function getName() : string
	{
		return $this->name;
	}
	
	
	
	/**
	 * Returns SubCommand description
	 * 
	 * @return string
	 * 
	 */
	final public function getDescription() : string
	{
		return $this->description;
	}

	
	
	
	abstract public function canUse(CommandSender $sender) : bool;
	
	abstract public function execute(CommandSender $sender, array $args) : bool;
}