<?php

declare(strict_types=1);

namespace dungeonlands\mynpc;

use dungeonlands\mynpc\entity\FloatingTextEntity;
use dungeonlands\mynpc\entity\HumanEntity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemTypeIds;
use pocketmine\player\Player;

class Events implements Listener{
	public function onInteract(EntityDamageEvent $event) : void{
		$entity = $event->getEntity();

		if(!$entity instanceof FloatingTextEntity and !$entity instanceof HumanEntity){
			return;
		}

		if(!$event instanceof EntityDamageByEntityEvent){
			$event->cancel();
			return;
		}

		$damager = $event->getDamager();

		if(!$damager instanceof Player){
			return;
		}

		if($damager->getInventory()->getItemInHand()->getTypeId() === ItemTypeIds::WOODEN_AXE and $damager->hasPermission("mynpc.admin.all")){
			$entity->kill();
			return;
		}

		$event->cancel();

		if($entity instanceof HumanEntity){
			$command = $entity->getCommand();
			if(!empty($command)){
				$damager->getServer()->dispatchCommand($damager, $command);
			}
		}
	}
}