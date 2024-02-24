<?php

declare(strict_types=1);

namespace dungeonlands\mynpc\entity;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;

class FloatingTextEntity extends Entity{
	public string $_customNameTag = "";

	private const CUSTOM_NAMETAG = "customNameTag";

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		$this->_customNameTag = $nbt->getString(self::CUSTOM_NAMETAG, "");

		$this->setCanSaveWithChunk(true);
		$this->setNameTagVisible();
		$this->setNameTagAlwaysVisible();
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();

		$nbt->setString(self::CUSTOM_NAMETAG, $this->_customNameTag);

		return $nbt;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.0, 1.0);
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);
		$properties->setInt(EntityMetadataProperties::VARIANT, TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId(VanillaBlocks::AIR()->getStateId()));
	}

	public static function getNetworkTypeId() : string{
		return EntityIds::FALLING_BLOCK;
	}

	public function getName() : string{
		return "FALLING_BLOCK";
	}

	protected function getInitialDragMultiplier() : float{
		return 0.0;
	}

	protected function getInitialGravity() : float{
		return 0.0;
	}
}