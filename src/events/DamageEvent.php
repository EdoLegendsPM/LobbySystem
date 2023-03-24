<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class DamageEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onDamage(EntityDamageEvent $event): void
    {
        $yaml = new YamlConfig($this->plugin);

        if ($yaml->get("safeWorld") === true) {
            $event->cancel();
        }
    }
}