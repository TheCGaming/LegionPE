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

namespace legionpe\theta\classic\kit\power;

use legionpe\theta\classic\ClassicSession;

abstract class ClassicKitPower{
	/** @var string */
	protected $name;
	/** @var int */
	protected $level;
	/** @var string */
	protected $description;

	/**
	 * @param $name
	 */
	protected function setName($name){
		$this->name = $name;
	}
	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}
	/**
	 * @param $level
	 */
	protected function setLevel($level){
		$this->level = $level;
	}
	/**
	 * @return int
	 */
	public function getLevel(){
		return $this->level;
	}
	/**
	 * @param string $description
	 */
	protected function setDescription($description){
		$this->description = $description;
	}
	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->description;
	}
	public abstract function onDamage(ClassicSession $damager, ClassicSession $damaged, &$damage);
	public abstract function onAttack(ClassicSession $attacker, ClassicSession $victim, &$damage);
	public abstract function onHeal(ClassicSession $owner, &$health);
}
