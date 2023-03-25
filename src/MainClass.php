<?php

declare(strict_types=1);

namespace LobbySystem;

use LobbySystem\database\YamlConfig;
use LobbySystem\events\BBreakEvent;
use LobbySystem\events\BPlaceEvent;
use LobbySystem\events\DamageEvent;
use LobbySystem\events\DropItemEvent;
use LobbySystem\events\InteractEvent;
use LobbySystem\events\JoinEvent;
use LobbySystem\events\MoveEvent;
use LobbySystem\events\QuitEvent;
use pocketmine\plugin\PluginBase;

/**
 * PREMIUM:
 * Scoreboard
 */
class MainClass extends PluginBase
{
    public function onEnable(): void
    {
        $yaml = new YamlConfig($this);
        $yaml->initConfig();

        $this->registerEvents();
    }

    public function onDisable(): void
    {
    }

    public function registerEvents(): void
    {
        $events = [
            new BBreakEvent($this), new BPlaceEvent($this),
            new DamageEvent($this), new DropItemEvent($this),
            new JoinEvent($this), new MoveEvent($this),
            new QuitEvent($this), new InteractEvent($this), new ITransactionEvent($this)
        ];

        foreach ($events as $event) {
            $this->getServer()->getPluginManager()->registerEvents($event, $this);
        }
    }
}