<?php

declare(strict_types=1);

namespace KingOfTurkey38\Generator;

use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat as C;

class HologramEntity extends Entity
{

    public const NETWORK_ID = Entity::BAT;

    public $width = 1;

    public $height = 1;

    /** @var GeneratorTile */
    private $tile = null;

    private $cd = 0;

    public function onUpdate(int $currentTick): bool
    {
        if ($this->isClosed() || $this->isFlaggedForDespawn()) {
            return true;
        }

        if ($this->cd >= 20) {
            if (!$this->tile->isClosed()) {
                $seconds = round($this->tile->getCd() / 20);
                $speed = round($this->tile->getSpeed() / 20) - $seconds;
                $this->setNameTag(C::AQUA . "Next spawn in: " . C::RED . $speed . C::AQUA . " seconds" . C::EOL . C::AQUA . "Level: " . C::LIGHT_PURPLE . $this->tile->getGenLevel());
                $this->respawnToAll();
                $this->cd = 0;
            } else {
                $this->flagForDespawn();
            }
        }

        $this->cd++;


        return true;
    }

    /**
     * @param GeneratorTile $tile
     */
    public function setTile(GeneratorTile $tile): void
    {
        $this->tile = $tile;
    }

    protected function initEntity(): void
    {
        parent::initEntity();

        $this->setScale(0.001);
        $this->setNameTagAlwaysVisible(true);
    }
}