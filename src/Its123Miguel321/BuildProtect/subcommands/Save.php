<?php

namespace Its123Miguel321\BuildProtect\subcommands;

use jojoe77777\FormAPI\CustomForm;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use Its123Miguel321\BuildProtect\subcommands\SubCommand;

class Save extends SubCommand
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
	 * Executes the Save command
	 * 
	 * @param CommandSender $sender
	 * @param array $args
	 * 
	 * @return bool
	 * 
	 */
	public function execute(CommandSender $sender, array $args) : bool
	{
		if(!($this->getMain()->getApi()->hasSelections($sender, 'pos1') && $this->getMain()->getApi()->hasSelections($sender, 'pos2')))
		{
			$sender->sendMessage('§l§c(!) §r§7You must have §e2 §7positions selected!');
			return false;
		}
		
		$pos1 = $this->getMain()->getApi()->getSelection($sender, 'pos1');
		$pos2 = $this->getMain()->getApi()->getSelection($sender, 'pos2');
		
		if($pos1[3] !== $pos2[3])
		{
			$sender->sendMessage('§l§c(!) §r§7Selections must be on the same level!');
			return false;
		}
		
		$this->saveUI($sender, '§7Hello §e' . $sender->getName() . '§7, fill out the form below to save an area!');
		return true;
	}
	
	
	
	/**
	 * This is the save UI
	 * 
	 * @param CommandSender $sender
	 * @param string $label
	 * 
	 */
	public function saveUI(CommandSender $sender, string $label) : void
	{
		$form = new CustomForm(function(Player $player, $data)
		{
			if($data === null) return;
			
			$name = trim($data[1], ' §');
			$creator = $player->getName();
			$priority = trim($data[2], ' §');
			$pos1 = $this->getMain()->getApi()->getSelection($creator, 'pos1');
			$pos2 = $this->getMain()->getApi()->getSelection($creator, 'pos2');
			$breaking = $data[4];
			$placing = $data[5];
			$pvp = $data[6];
			$flight = $data[7];
			
			if($this->getMain()->getManager()->buildExists($name)) return $this->saveUI($player, '§cA build with the name §e' . $name . ' already exists!');
			if($name == '') return $this->saveUI($player, '§cYou must pick a name!');
			if(!(is_numeric($priority))) return $this->saveUI($player, '§cPriority must be a number!');
			
			$this->getMain()->getManager()->createBuild($name, $creator, (int)$priority, $pos1, $pos2, $breaking, $placing, $pvp, $flight);
			
			$player->sendMessage('§l§a(!) §r§7You successfully protected a new build with the name §e' . $name . '§7!');
		});
		$form->setTitle('');
		$form->addLabel($label);
		$form->addInput('§6Enter your new protected build\'s name!', 'Test');
		$form->addInput('§6Enter the priority', '-1', '-1');
		$form->addLabel('§6Toggle a button below to the left to set as false/disabled! Toggle to them to the right to set as true/enabled!');
		$form->addToggle('Block Breaking', true);
		$form->addToggle('Block Placing', true);
		$form->addToggle('PvP', true);
		$form->addToggle('Flight', true);
		$sender->sendForm($form);
	}
}