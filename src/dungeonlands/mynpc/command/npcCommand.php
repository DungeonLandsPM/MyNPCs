<?php

declare(strict_types=1);

namespace dungeonlands\mynpc\command;

use dungeonlands\mynpc\entity\FloatingTextEntity;
use dungeonlands\mynpc\entity\HumanEntity;
use dungeonlands\mynpc\NPCLoader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwnedTrait;

class npcCommand extends Command{
	use PluginOwnedTrait;

	private const string USAGE_MESSAGE = "§e/npc spawn [text=-1, human=0] [!nameTag] [!command] [!scoreTag]";

	public function __construct(){
		parent::__construct("npc", NPCLoader::PREFIX . "Manager");
		$this->setPermission("mynpc.admin.all");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!$sender instanceof Player){
			return;
		}

		if(!isset($args[0])){
			$sender->sendMessage(NPCLoader::PREFIX . self::USAGE_MESSAGE);
			return;
		}

		switch($args[0]){
			case "spawn":
				if(!isset($args[1])){
					$sender->sendMessage(NPCLoader::PREFIX . self::USAGE_MESSAGE);
					return;
				}

				switch($args[1]){
					case "-1":
						$this->spawnFloatingText($sender, $args);
						break;
					case "0":
						$this->spawnHuman($sender, $args);
						break;
					default:
						$sender->sendMessage(NPCLoader::PREFIX . self::USAGE_MESSAGE);
						break;
				}
				break;
			default:
				$sender->sendMessage(NPCLoader::PREFIX . self::USAGE_MESSAGE);
				break;
		}
	}

	private function spawnFloatingText(Player $sender, array $args) : void{
		if(count($args) < 2){
			$sender->sendMessage(NPCLoader::PREFIX . self::USAGE_MESSAGE);
			return;
		}

		$nameTag = $args[2] ?? null;

		if($nameTag === null){
			$sender->sendMessage(NPCLoader::PREFIX . "§cNameTag §7was not set?");
			return;
		}

		$entity = new FloatingTextEntity($sender->getLocation());
		$entity->_customNameTag = $nameTag;

		$entity->setNameTag($nameTag);
		$entity->spawnToAll();

		$sender->sendMessage(NPCLoader::PREFIX . "Spawned Entity §e(ID: {$entity->getId()})§7!");
	}

	private function spawnHuman(Player $sender, array $args) : void{
		if(count($args) < 2){
			$sender->sendMessage(NPCLoader::PREFIX . self::USAGE_MESSAGE);
			return;
		}

		$nameTag = $args[2] ?? null;

		if($nameTag === null){
			$sender->sendMessage(NPCLoader::PREFIX . "§cNameTag §7was not set?");
			return;
		}

		$command = $args[3] ?? "";
		$scoreTag = $args[4] ?? "";

		$entity = new HumanEntity($sender->getLocation(), $sender->getSkin());
		$entity->_customNameTag = $nameTag;
		$entity->_command = $command;
		$entity->_customScoreTag = $scoreTag;

		$entity->setNameTag($nameTag);
		$entity->getInventory()->setItemInHand($sender->getInventory()->getItemInHand());
		$entity->getArmorInventory()->setContents($sender->getArmorInventory()->getContents());
		$entity->spawnToAll();

		$sender->sendMessage(NPCLoader::PREFIX . "Spawned Entity §e(ID: {$entity->getId()})§7!");
	}
}