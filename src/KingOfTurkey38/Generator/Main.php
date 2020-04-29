<?php

declare(strict_types=1);

namespace KingOfTurkey38\Generator;

use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use ReflectionException;

class Main extends PluginBase
{

    /** @var Main */
    private static $instance;
    /** @var array */
    private $configData = [];

    /**
     * @return Main
     */
    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public function onEnable()
    {
        self::$instance = $this;

        try {
            Tile::registerTile(GeneratorTile::class, [GeneratorTile::NAME]);
            Entity::registerEntity(HologramEntity::class);
        } catch (ReflectionException $e) {
            echo "caught exception: " . $e->getMessage();
        }
        $this->configData = $this->getConfig()->getAll();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function onDisable()
    {
        foreach ($this->getServer()->getLevels() as $level) {
            foreach ($level->getEntities() as $entity) {
                if ($entity instanceof HologramEntity) {
                    $entity->close();
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getConfigData(): array
    {
        return $this->configData;
    }
}
