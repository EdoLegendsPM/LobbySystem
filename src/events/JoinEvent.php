<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use LobbySystem\Manager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();

        $manager = new Manager($this->plugin);
        $manager->clearAll($player);
        $manager->giveItems($player);

        $yaml = new YamlConfig($this->plugin);
        if ($yaml->get("joinMessage") !== "") {
            $message = $yaml->get("joinMessage");
            $editedMessage = str_replace(["{PREFIX}", "{PLAYER}"], [$yaml->get("prefix"), $player->getName()], $message);
            $event->setJoinMessage($editedMessage);
        }

        if ($yaml->get("welcomeTitle") !== "") {
            $message = $yaml->get("welcomeTitle");
            $editedMessage = str_replace(["{PREFIX}"], [$player->getName()], $message);
            $player->sendTitle($editedMessage);
        }
    }
}