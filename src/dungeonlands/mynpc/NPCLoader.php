<?php

declare(strict_types=1);

namespace dungeonlands\mynpc;

use dungeonlands\mynpc\command\npcCommand;
use dungeonlands\mynpc\entity\FloatingTextEntity;
use dungeonlands\mynpc\entity\HumanEntity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class NPCLoader extends PluginBase
{
    public const PREFIX = "§r§cMyNPCs §8» §7";

    protected function onEnable(): void
    {
        $this->loadEntities();

        $this->registerEvents();
        $this->registerCommands();
    }

    private function registerEvents(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
    }

    private function registerCommands(): void
    {
        $this->getServer()->getCommandMap()->register("MyNPC", new npcCommand());
    }

    private function loadEntities(): void
    {
        EntityFactory::getInstance()->register(FloatingTextEntity::class, function (World $world, CompoundTag $nbt): FloatingTextEntity {
            return new FloatingTextEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, [FloatingTextEntity::class]);

        EntityFactory::getInstance()->register(HumanEntity::class, function (World $world, CompoundTag $nbt): HumanEntity {
            return new HumanEntity(EntityDataHelper::parseLocation($nbt, $world), HumanEntity::parseSkinNBT($nbt), $nbt);
        }, [HumanEntity::class]);
    }
}