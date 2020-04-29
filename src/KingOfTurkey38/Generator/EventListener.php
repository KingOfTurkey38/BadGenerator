<?php

declare(strict_types=1);

namespace KingOfTurkey38\Generator;

use KingOfTurkey38\Generator\Forms\GeneratorUpgradeForm;
use pocketmine\block\Block;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;

class EventListener implements Listener
{

    private $plugin;

    private $cd = [];

    private $allowedBlocks = [Block::IRON_BLOCK, Block::DIAMOND_BLOCK, Block::GOLD_BLOCK];

    private $blocks = [Block::IRON_BLOCK => GeneratorTile::TYPE_IRON, Block::GOLD_BLOCK => GeneratorTile::TYPE_GOLD, Block::DIAMOND_BLOCK => GeneratorTile::TYPE_DIAMOND];


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }


    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $block = $event->getBlock();

        if (in_array($block->getId(), $this->allowedBlocks)) {
            $nbt = new CompoundTag("", [
                new StringTag(Tile::TAG_ID, GeneratorTile::NAME),
                new IntTag(Tile::TAG_X, (int)$block->x),
                new IntTag(Tile::TAG_Y, (int)$block->y),
                new IntTag(Tile::TAG_Z, (int)$block->z)
            ]);
            $nbt->setInt(GeneratorTile::SPEED_TAG, $this->plugin->getConfigData()["generator-settings"]["speed"]["level1"]);
            $nbt->setString(GeneratorTile::TYPE_TAG, $this->blocks[$block->getId()]);

            $tile = new GeneratorTile($block->getLevel(), $nbt);
            $block->getLevel()->addTile($tile);
        }
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $block = $event->getBlock();
        $action = $event->getAction();
        if ($action === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            if (in_array($block->getId(), $this->allowedBlocks)) {
                $player = $event->getPlayer();
                $name = $player->getName();
                if (isset($this->cd[$name])) {
                    if (time() - $this->cd[$name] > 1) {
                        $tile = $block->getLevel()->getTile($block);
                        if ($tile instanceof GeneratorTile) {
                            $player->sendForm(new GeneratorUpgradeForm($tile));
                        }
                        unset($this->cd[$name]);
                    }
                } else {
                    $this->cd[$name] = time();
                    $tile = $block->getLevel()->getTile($block);
                    if ($tile instanceof GeneratorTile) {
                        $player->sendForm(new GeneratorUpgradeForm($tile));
                    }
                }
            }
        }
    }

}