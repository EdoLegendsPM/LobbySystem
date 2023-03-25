<?php

declare(strict_types=1);

namespace LobbySystem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class ITransactionEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $event->cancel();
    }
}