<?php

declare(strict_types=1);

namespace LobbySystem\database;

use LobbySystem\MainClass;
use pocketmine\utils\Config;

class YamlConfig
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function initConfig(): void
    {
        if (!file_exists($this->getConfigFile())) {
            $config = $this->getConfig();

            $config->set("safeWorld", true); //Nobody can die in the lobby
            $config->set("buildMode", false); //Nobody can build in the lobby just users with op
            $config->set("prefix", "§eMyServer §8»§7"); //PREFIX
            $config->set("joinMessage", "{PREFIX} {PLAYER} has §ajoined §7the server!"); //"" = no message0
            $config->set("quitMessage", "{PREFIX} {PLAYER} has §cleft §7the server!"); //"" = no message
            $config->set("welcomeTitle", "§eWelcome to\n§8» §eMyServer §8«"); //\n will split the message and MyServer will be a subtitle yeah - "" = no message

            $config->setNested("items.playerhider.item", "stick");
            $config->setNested("items.playerhider.nametagg", "§cAll players are hidden");
            $config->setNested("items.playerhider.slot", 1);
            $config->setNested("items.playerhider.action", "lobby:show");

            $config->setNested("items.playerhider2.item", "blaze_rod");
            $config->setNested("items.playerhider2.nametagg", "§aYou can see all players");
            $config->setNested("items.playerhider2.slot", 1);
            $config->setNested("items.playerhider2.action", "lobby:hide");

            $config->setNested("items.teleporter.item", "compass");
            $config->setNested("items.teleporter.nametagg", "§7Navigator");
            $config->setNested("items.teleporter.slot", 4);
            $config->setNested("items.teleporter.action", "lobby:teleporter");

            $config->setNested("items.gadgets.item", "chest");
            $config->setNested("items.gadgets.nametagg", "§gGadgets");
            $config->setNested("items.gadgets.slot", 7);
            $config->setNested("items.gadgets.action", "lobby:gadgets");


            $config->setNested("forms.navigator.title", "§8» §7Navigator §8«");
            $config->setNested("forms.navigator.description", "§8» §7Select a game");
            $config->setNested("forms.navigator.count", 2);

            $config->setNested("forms.navigator.buttons.1.text", "§cSPAWN");
            $config->setNested("forms.navigator.buttons.1.x", 150);
            $config->setNested("forms.navigator.buttons.1.y", 250);
            $config->setNested("forms.navigator.buttons.1.z", 100);

            $config->setNested("forms.navigator.buttons.2.text", "§7FFA");
            $config->setNested("forms.navigator.buttons.2.x", 100);
            $config->setNested("forms.navigator.buttons.2.y", 250);
            $config->setNested("forms.navigator.buttons.2.z", 100);


            $config->setNested("forms.gadgets.title", "§8» §gGadgets §8«");
            $config->setNested("forms.gadgets.description", "§8» §7Select a gadget");
            $config->setNested("forms.gadgets.buttons.1.text", "§cJet Pack");
            $config->setNested("forms.gadgets.buttons.1.permissions", "");
            $config->setNested("forms.gadgets.buttons.1.action", "gadget:jetpack");
            $config->save();
        }
    }

    public function get(string $colum): mixed
    {
        $config = $this->getConfig();
        if ($config->exists($colum) === true) {
            return $config->get($colum);
        } else {
            $this->plugin->getServer()->getLogger()->warning("{$colum} has a error, contact me on discord");
            return null;
        }
    }

    public function getNested(string $colum): mixed
    {
        $config = $this->getConfig();
        if ($config->exists($colum) === true) {
            return $config->getNested($colum);
        } else {
            $this->plugin->getServer()->getLogger()->warning("{$colum} has a error, contact me on discord");
            return null;
        }
    }

    public function getConfig(): Config
    {
        return new Config($this->getConfigFile());
    }

    public function getConfigFile(): string
    {
        return $this->plugin->getDataFolder() . "config.yml";
    }
}