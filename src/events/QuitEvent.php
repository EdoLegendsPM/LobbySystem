<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class QuitEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        $yaml = new YamlConfig($this->plugin);
        if ($yaml->get("quitMessage") !== "") {
            $message = $yaml->get("quitMessage");
            $editedMessage = str_replace(["{PREFIX}", "{PLAYER}"], [$yaml->get("prefix"), $player->getName()], $message);
            $event->setQuitMessage($editedMessage);
        }
    }
}