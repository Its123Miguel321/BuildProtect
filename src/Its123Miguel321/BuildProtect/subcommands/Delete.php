<?php

namespace Its123Miguel321\BuildProtect\subcommands;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use Its123Miguel321\BuildProtect\subcommands\SubCommand;

class Delete extends SubCommand
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
	 * Executes the Delete command
	 * 
	 * @param CommandSender $sender
	 * @param array $args
	 * 
	 * @return bool
	 * 
	 */
	public function execute(CommandSender $sender, array $args) : bool
	{
		if($this->getMain()->getManager()->countBuilds() === 0)
		{
			$sender->sendMessage('§l§c(!) §r§7Can not open UI, there are no protected builds to delete!');
			return false;
		}
		
		$this->deleteUI($sender, '§7Hello §e' . $sender->getName() . '§7, fill out the form below to delete an area!');
		return true;
	}
	
	
	
	/**
	 * This is the delete UI
	 * 
	 * @param CommandSender $sender
	 * @param string $label
	 * 
	 */
	public function deleteUI(CommandSender $sender, string $label) : void
	{
		$builds = [];
		
		foreach($this->getMain()->getManager()->getBuilds() as $build)
		{
			$builds[] = $build->getName();
		}
		
		
		
		$form = new CustomForm(function(Player $player, $data) use($builds)
		{
			if($data === null) return;
			
			$name = $builds[$data[1]];
			
			if(!($this->getMain()->getManager()->buildExists($name)))
			{
				$this->deleteUI($player, '§cA build with the name §e' . $name . ' does not exist!');
				return;
			}
			
			$this->confirmationUI($player, $name);
		});
		$form->setTitle('');
		$form->addLabel($label);
		$form->addDropdown('§6Select a build:', $builds);
		$sender->sendForm($form);
	}
	
	
	
	/**
	 * Sends a UI to confirm they want to delete that protected build!
	 * 
	 * @param Player $player
	 * @param string $buildName
	 * 
	 */
	public function confirmationUI(Player $player, string $buildName) : void
	{
		$build = $this->getMain()->getManager()->getBuildByName($buildName);
		
		$form = new SimpleForm(function(Player $player, $data) use($build)
		{
			if($data === null) return;
			
			switch($data)
			{
				case 0:
					if($this->getMain()->getManager()->countBuilds() === 0)
					{
						$sender->sendMessage('§l§c(!) §r§7Can not open UI, there are no protected builds to delete!');
						return;
					}
					
					$this->getMain()->getManager()->deleteBuildByName($build->getName());
					
					$player->sendMessage('§l§a(!) §r§7You successfully deleted a protected build with the name §e' . $build->getName() . '§7!');
					break;
				
				case 1:
					$this->deleteUI($sender, '§7Hello §e' . $sender->getName() . '§7, fill out the form below to delete an area!');
					break;
			}
		});
		$form->setTitle('');
		$form->setContent("§7Are you sure you want to delete this build named §e" . $build->getName() . "§7?\n\nIt has a priority of §e" . $build->getPriority() . "§7!");
		$form->addButton('§aConfirm');
		$form->addButton('§cCancel');
		$player->sendForm($form);
	}
}