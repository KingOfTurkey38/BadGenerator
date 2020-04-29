<?php
/**
 * Copyright (c) KingOfTurkey38 2019.
 * Last Modified 30/03/19 12:00
 *
 * This plugin is developed and maintained by KingOfTurkey38.
 *
 * This plugin is distributed under a commercial license and you are only allowed to use it in the way and
 * extent and under conditions explicitly agreed on with KingOfTurkey38 in writing.
 *
 * By the license, you're not allowed to remove this documented snippet.
 *
 */

declare(strict_types=1);

namespace KingOfTurkey38\Generator\libs\jojoe77777\FormAPI;

use pocketmine\form\Form as IForm;
use pocketmine\Player;

abstract class Form implements IForm
{

    /** @var array */
    protected $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @see Player::sendForm()
     *
     * @deprecated
     */
    public function sendToPlayer(Player $player): void
    {
        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data): void
    {
    }

    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    public function setCallable(?callable $callable)
    {
        $this->callable = $callable;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
