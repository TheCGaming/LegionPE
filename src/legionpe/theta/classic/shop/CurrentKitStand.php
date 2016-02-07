<?php

/*
 * LegionPE Theta
 *
 * Copyright (C) 2015 PEMapModder and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace legionpe\theta\classic\shop;

use pocketmine\entity\Entity;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\MobArmorEquipmentPacket;
use pocketmine\network\protocol\MobEquipmentPacket;
use pocketmine\network\protocol\SetEntityDataPacket;
use pocketmine\utils\TextFormat;
use legionpe\theta\classic\ClassicSession;
use legionpe\theta\classic\kit\ClassicKit;
use pocketmine\level\Position;
use pocketmine\utils\UUID;

class CurrentKitStand extends KitStand{
	/**
	 * @param ClassicSession $session
	 * @param ClassicKit $kit
	 * @param Position $npcPos
	 * @param int $yaw
	 * @param Position[] $next
	 * @param Position[] $back
	 * @param Position[] $floatingPos
	 */
	public function __construct(ClassicSession $session, Position $npcPos, $yaw, $next, $back, Position $floatingPos){
		$this->session = $session;
		$this->kit = $session->currentKit;
		$this->yaw = $yaw;
		$this->level = $session->getPlayer()->getLevel();
		$this->npcPos = $npcPos;
		$this->nextPos = $next;
		$this->backPos = $back;

		$player = $session->getPlayer();
		$pk = new AddPlayerPacket();
		$this->npcEid = Entity::$entityCount++;
		$pk->uuid = UUID::fromData($this->npcEid, "", "");
		$pk->username = TextFormat::AQUA . TextFormat::GREEN . $this->kit->getName() . TextFormat::WHITE . " - " . TextFormat::AQUA . "level " . TextFormat::GREEN . $this->kit->level;
		$pk->eid = $this->npcEid;
		$pk->x = $npcPos->getX();
		$pk->y = $npcPos->getY();
		$pk->z = $npcPos->getZ();
		$pk->speedX = 0;
		$pk->speedY = 0;
		$pk->speedZ = 0;
		$pk->yaw = $yaw;
		$pk->pitch = 0;
		$pk->item = $this->kit->getItems()[0];
		$pk->metadata = [];
		$player->dataPacket($pk);
		$pk = new MobArmorEquipmentPacket();
		$pk->eid = $this->npcEid;
		$pk->slots = $this->kit->getArmorItems();
		$player->dataPacket($pk);
	}
	public function next(){
		if($this->kit->level < $this->session->getKitLevel($this->kit)){
			$this->kit->setLevel(++$this->kit->level);
			$this->update();
		}
	}
	public function back(){
		if($this->kit->level !== 1){
			$this->kit->setLevel(--$this->kit->level);
			$this->update();
		}
	}
	public function update(){
		$this->kit = $this->session->currentKit;
		$player = $this->session->getPlayer();
		$pk = new MobArmorEquipmentPacket();
		$pk->eid = $this->npcEid;
		$pk->slots = $this->kit->getArmorItems();
		$player->dataPacket($pk);
		$pk = new MobEquipmentPacket();
		$pk->eid = $this->npcEid;
		$pk->item = $this->kit->getItems()[0];
		$pk->slot = 0;
		$pk->selectedSlot = 0;
		$player->dataPacket($pk);
		$pk = new SetEntityDataPacket();
		$pk->eid = $this->npcEid;
		$pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, TextFormat::AQUA . TextFormat::GREEN . $this->kit->getName() . TextFormat::WHITE . " - " . TextFormat::AQUA . "level " . TextFormat::GREEN . $this->kit->level]];
		$player->dataPacket($pk);
	}
}
