<?php

declare(strict_types=1);

namespace dungeonlands\mynpc\entity;

use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;

class HumanEntity extends Human{
	public string $_customNameTag = "";
	public string $_customScoreTag = "";
	public string $_command = "";

	private const string CUSTOM_NAMETAG = "customNameTag";
	private const string CUSTOM_SCORETAG = "customScoreTag";
	private const string COMMAND = "command";

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		$this->_customNameTag = $nbt->getString(self::CUSTOM_NAMETAG, "");
		$this->_customScoreTag = $nbt->getString(self::CUSTOM_SCORETAG, "");
		$this->_command = $nbt->getString(self::COMMAND, "");

		$this->setNameTag($this->_customNameTag);
		$this->setScoreTag($this->_customScoreTag);

		$this->setHealth(69);
		$this->setMaxHealth(69);
		$this->setGravity(0.0);
		$this->setHasGravity(false);

		$this->setCanSaveWithChunk(true);
		$this->setNameTagVisible();
		$this->setNameTagAlwaysVisible();
	}

	public function getDrops() : array{
		return [];
	}

	public function getCommand() : string{
		return $this->_command;
	}

	public function saveNBT() : CompoundTag{
		$properties = [
			self::CUSTOM_NAMETAG => $this->_customNameTag,
			self::CUSTOM_SCORETAG => $this->_customScoreTag,
			self::COMMAND => $this->_command,
		];

		$nbt = parent::saveNBT();

		foreach($properties as $prefix => $string){
			$nbt->setString($prefix, $string);
		}

		return $nbt;
	}
}