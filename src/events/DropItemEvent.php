<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;

class DropItemEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onItemDrop(PlayerDropItemEvent $event): void
    {
        $yaml = new YamlConfig($this->plugin);

        if ($yaml->get("safeWorld") === true) {
            $event->cancel();
        }
    }
}