<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class BPlaceEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $yaml = new YamlConfig($this->plugin);

        if ($yaml->get("safeWorld") === true) {
            $event->cancel();
            if ($yaml->get("buildMode") === true) {
                if ($this->plugin->getServer()->isOp($player->getName()) === true) $event->uncancel();
            }
        }
    }
}