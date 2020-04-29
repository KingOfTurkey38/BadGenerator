<?php

declare(strict_types=1);

namespace KingOfTurkey38\Generator\Forms;

use KingOfTurkey38\Generator\GeneratorTile;
use KingOfTurkey38\Generator\libs\jojoe77777\FormAPI\ModalForm;
use KingOfTurkey38\Generator\Main;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class GeneratorUpgradeForm extends ModalForm
{

    /** @var GeneratorTile */
    private $tile;

    public function __construct(GeneratorTile $tile)
    {
        parent::__construct([$this, "onSubmit"]);
        $this->tile = $tile;

        $nextLevel = $tile->getGenLevel() + 1;
        if (!isset(Main::getInstance()->getConfigData()["generator-settings"]["speed"]["level" . $nextLevel])) {
            $nextSpeed = "MAX";
        } else {
            $nextSpeed = Main::getInstance()->getConfigData()["generator-settings"]["speed"]["level" . $nextLevel];
        }

        $this->setTitle("Generator Upgrade Form");
        $this->setContent("Current level: " . $tile->getGenLevel() .
            C::EOL . "Current speed: " . round($tile->getSpeed() / 20) .
            C::EOL . "Next speed: " . $nextSpeed);

        $this->setButton1(C::GREEN . "Upgrade");
        $this->setButton2(C::RED . "Exit");
    }

    public function onSubmit(Player $player, bool $response)
    {
        if ($response) {
            $required = Item::get($this->tile->drops[$this->tile->getType()], 0, 32 * $this->tile->getGenLevel() + 1);
            if ($player->getInventory()->contains($required)) {
                $nextLevel = $this->tile->getGenLevel() + 1;

                if (!isset(Main::getInstance()->getConfigData()["generator-settings"]["speed"]["level" . $nextLevel])) {
                    $nextSpeed = "MAX";
                } else {
                    $nextSpeed = Main::getInstance()->getConfigData()["generator-settings"]["speed"]["level" . $nextLevel];
                }

                if (is_int($nextSpeed)) {
                    $this->tile->setGenLevel($this->tile->getGenLevel() + 1);
                    $this->tile->setSpeed($nextSpeed);
                }
            }
        }
    }
}