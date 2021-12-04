<?php

namespace Its123Miguel321\BuildProtect\subcommands;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use Its123Miguel321\BuildProtect\subcommands\SubCommand;

class Edit extends SubCommand
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
	 * Executes the Edit command
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
			$sender->sendMessage('§l§c(!) §r§7Can not open UI, there are no protected builds to edit!');
			return false;
		}
		
		$this->buildsUI($sender, '§7Hello §e' . $sender->getName() . '§7, fill out the form below to edit an area!');
		return true;
	}
	
	
	
	/**
	 * This is the builds UI
	 * 
	 * @param CommandSender $sender
	 * @param string $label
	 * 
	 */
	public function buildsUI(CommandSender $sender, string $label) : void
	{
		if($this->getMain()->getManager()->countBuilds() === 0)
		{
			$sender->sendMessage('§l§c(!) §r§7Can not open UI, there are no protected builds to edit!');
			return;
		}
		
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
				$this->buildsUI($player, '§cA build with the name §e' . $name . ' does not exist!');
				return;
			}
			
			$this->editUI($player, '§7Hello §e' . $player->getName() . '§7, what would you like change from this area?', $name);
		});
		$form->setTitle('');
		$form->addLabel($label);
		$form->addDropdown('§6Select a build:', $builds);
		$sender->sendForm($form);
	}
	
	
	
	/**
	 * This is the edit UI
	 * 
	 * @param Player $player
	 * @param string $label
	 * @param string $buildName
	 * 
	 */
	public function editUI(Player $player, string $label, string $buildName) : void
	{
		$build = $this->getMain()->getManager()->getBuildByName($buildName);
		
		if($this->getMain()->getManager()->countBuilds() === 0)
		{
			$player->sendMessage('§l§c(!) §r§7Can not open UI, there are no protected builds to edit!');
			return;
		}
		
		$form = new CustomForm(function(Player $player, $data) use($build)
		{
			if($data === null) return;
			
			if(!($this->getMain()->getManager()->buildExists($build->getName())))
			{
				$this->buildsUI($player, '§cA build with the name §e' . $build->getName() . ' no longer exists!');
				return;
			}
			
			$priority = trim($data[1], ' ');
			
			if(!(is_numeric($priority)))
			{
				$this->editUI($player, '§cPriority must be numeric!', $build->getName());
				return;
			}
			
			$this->confirmationUI($player, $build->getName(), (int)$priority, $data[8], $data[3], $data[4], $data[5], $data[6]);
			
		});
		$form->setTitle('');
		$form->addLabel($label);
		$form->addInput('Change priority', '-1', (string)$build->getPriority());
		$form->addLabel('§6Toggle a button below to the left to set as false/disabled! Toggle to them to the right to set as true/enabled!');
		$form->addToggle('Block Breaking', $build->getSetting('breaking'));
		$form->addToggle('Block Placing', $build->getSetting('placing'));
		$form->addToggle('PvP', $build->getSetting('pvp'));
		$form->addToggle('Flight', $build->getSetting('flight'));
		$form->addLabel('§6Toggle the button below to the right to change the build coordinates to your current selections! Toggle to button to the left to leave the coordinates the same!');
		$form->addToggle('New Coordinates', false);
		$player->sendForm($form);
	}
	
	
	
	/**
	 * Sends a UI to confirm they want to delete that protected build!
	 * 
	 * @param Player $player
	 * @param string $buildName
	 * @param int $priority
	 * @param bool $newcoords
	 * @param bool $breaking
	 * @param bool $placing
	 * @param bool $pvp
	 * @param bool $flight
	 * 
	 */
	public function confirmationUI(Player $player, string $buildName, int $priority, bool $newcoords, bool $breaking, bool $placing, bool $pvp, bool $flight) : void
	{
		if($this->getMain()->getManager()->countBuilds() === 0)
		{
			$player->sendMessage('§l§c(!) §r§7Can not open UI, there are no protected builds to edit!');
			return;
		}
		
		$build = $this->getMain()->getManager()->getBuildByName($buildName);
		
		$form = new SimpleForm(function(Player $player, $data) use($build, $priority, $newcoords, $breaking, $placing, $pvp, $flight)
		{
			if($data === null) return;
			
			switch($data)
			{
				case 0:
					if($newcoords === true)
					{
						if(!($this->getMain()->getApi()->hasSelections($player, 'pos1') && $this->getMain()->getApi()->hasSelections($player, 'pos2')))
						{
							$player->sendMessage('§l§c(!) §r§7You must have §e2 §7positions selected, closing confirmation UI!');
							return;
						}
						
						$build->setPos1($this->getMain()->getApi()->getSelection($player, 'pos1'));
						$build->setPos2($this->getMain()->getApi()->getSelection($player, 'pos2'));
					}
					
					$this->getMain()->getManager()->createBuild(
						$build->getName(), 
						$build->getCreator(), 
						(int)$priority, 
						$build->getPos1(), 
						$build->getPos2(), 
						$breaking, 
						$placing, 
						$pvp, 
						$flight
					);
					
					$player->sendMessage('§l§a(!) §r§7You successfully edited a protected build with the name §e' . $build->getName() . '§7!');
					break;
				
				case 1:
					$this->buildsUI($player, '§7Hello §e' . $player->getName() . '§7, fill out the form below to delete an area!');
					break;
			}
		});
		$form->setTitle('');
		$form->setContent("§7Are you sure you want to edit this build named §e" . $build->getName() . "§7?");
		$form->addButton('§aConfirm');
		$form->addButton('§cCancel');
		$player->sendForm($form);
	}
}
