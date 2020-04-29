<?php

declare(strict_types=1);

namespace KingOfTurkey38\Generator;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Tile;

class GeneratorTile extends Tile
{

    const NAME = "KingOfTurkey38:GeneratorTile";

    const SPEED_TAG = "SPEED_TAG";
    const TYPE_TAG = "TYPE_TAG";
    const LEVEL_TAG = "LEVEL_TAG";

    const TYPE_IRON = "TYPE_IRON";
    const TYPE_GOLD = "TYPE_GOLD";
    const TYPE_DIAMOND = "TYPE_DIAMOND";
    public $drops = [self::TYPE_IRON => Item::IRON_INGOT, self::TYPE_GOLD => Item::GOLD_INGOT, self::TYPE_DIAMOND => Item::DIAMOND];
    private $blocks = [self::TYPE_IRON => Block::IRON_BLOCK, self::TYPE_GOLD => Block::GOLD_BLOCK, self::TYPE_DIAMOND => Block::DIAMOND_BLOCK];
    /** @var int */
    private $cd = 1;
    /** @var int */
    private $speed = 100;
    /** @var string */
    private $type = "";
    /** @var HologramEntity */
    private $entity = null;
    /** @var int */
    private $genLevel = 1;

    public function onUpdate(): bool
    {
        if ($this->isClosed()) {
            return false;
        }

        if ($this->cd >= $this->speed) {

            if (!$this->getLevel()->getBlockAt($this->x, $this->y, $this->z)->getId() === $this->blocks[$this->type]) {
                $this->close();
                $this->entity->setNameTagAlwaysVisible(false);
                $this->entity->setNameTagVisible(false);
                $this->entity->setNameTag("");
                $this->entity->close();
                return false;
            }


            $this->getLevel()->dropItem($this->asVector3()->add(0, 1), Item::get($this->drops[$this->type]));

            $this->cd = 0;
        }

        $this->cd++;

        return true;
    }

    /**
     * @return int
     */
    public function getGenLevel(): int
    {
        return $this->genLevel;
    }

    /**
     * @param int $genLevel
     */
    public function setGenLevel(int $genLevel): void
    {
        $this->genLevel = $genLevel;
    }

    /**
     * @return int
     */
    public function getCd(): int
    {
        return $this->cd;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     */
    public function setSpeed(int $speed): void
    {
        $this->speed = $speed;
    }

    /**
     * @param CompoundTag $nbt
     */
    protected function readSaveData(CompoundTag $nbt): void
    {
        if (!$nbt->hasTag(self::TYPE_TAG)) {
            $this->close();
            return;
        }

        if (!$nbt->hasTag(self::LEVEL_TAG)) {
            $nbt->setInt(self::LEVEL_TAG, 1);
        }

        if (!$nbt->hasTag(self::SPEED_TAG)) {
            $speed = Main::getInstance()->getConfigData()["generator-settings"]["speed"]["level1"];
            $nbt->setInt(self::SPEED_TAG, $speed);
        }
        $this->speed = $nbt->getInt(self::SPEED_TAG);
        $this->type = $nbt->getString(self::TYPE_TAG);
        $this->genLevel = $nbt->getInt(self::LEVEL_TAG);

        $this->spawnHologram();

        $this->scheduleUpdate();
    }

    public function spawnHologram(): void
    {
        $nbt = Entity::createBaseNBT($this->asVector3()->add(0.5, 2, 0.5));
        $entity = new HologramEntity($this->getLevel(), $nbt);
        $entity->setTile($this);
        $entity->spawnToAll();

        $this->entity = $entity;
    }

    /**
     * @param CompoundTag $nbt
     */
    protected function writeSaveData(CompoundTag $nbt): void
    {
        $nbt->setInt(self::SPEED_TAG, $this->speed);
        $nbt->setString(self::TYPE_TAG, $this->type);
        $nbt->setInt(self::LEVEL_TAG, $this->genLevel);
    }
}