<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use LobbySystem\Manager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class InteractEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $manager = new Manager($this->plugin);
        $yaml = new YamlConfig($this->plugin);

        $usedItem = $player->getInventory()->getItemInHand()->getCustomName();
        $items = $yaml->getNested("items");

        foreach ($items as $itemName => $itemData) {
            if ($itemData["nametagg"] === $usedItem) {
                $action = $itemData["action"];
                $manager->action($player, (string)$action);
            }
        }
    }
}