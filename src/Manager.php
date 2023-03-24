<?php

declare(strict_types=1);

namespace LobbySystem;

use LobbySystem\database\YamlConfig;
use LobbySystem\forms\GadgetsForm;
use LobbySystem\forms\NavigatorForm;
use pocketmine\color\Color;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class Manager
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function clearAll(Player $player): void
    {
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getEffects()->clear();
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
        $player->getHungerManager()->setEnabled(false);
        $player->setGamemode(GameMode::ADVENTURE());
    }

    public function giveItems(Player $player): void
    {
        $yaml = new YamlConfig($this->plugin);
        $items = $yaml->get("items");

        foreach ($items as $itemName => $itemData) {
            $vanilla = $this->convertItemToVanillaItem($itemData["item"], $itemData["nametagg"]);
            $player->getInventory()->setItem($itemData["slot"], $vanilla);
        }
    }

    public function giveJet(Player $player): void
    {
        $player->getArmorInventory()->setChestplate(VanillaItems::LEATHER_TUNIC()->setCustomName("§cJetpack")->setCustomColor(new Color(144, 32, 32)));
    }

    public function convertItemToVanillaItem(string $item, string $customName)
    {
        return StringToItemParser::getInstance()->parse($item)->setCustomName($customName);
    }

    public function action(Player $player, string $action): void
    {
        $yaml = new YamlConfig($this->plugin);
        switch ($action) {
            case "lobby:hide":
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
                    $onlinePlayer->hidePlayer($player);
                }
                $player->getInventory()->remove($player->getInventory()->getItemInHand());

                $items = $yaml->get("items");
                foreach ($items as $itemName => $itemData) {
                    if ($itemName === "playerhider") {
                        var_dump($itemData["item"]);
                        $vanilla = $this->convertItemToVanillaItem($itemData["item"], $itemData["nametagg"]);
                        $player->getInventory()->setItem($itemData["slot"], $vanilla);
                    }
                }
                break;
            case "lobby:show":
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
                    $onlinePlayer->showPlayer($player);
                }
                $player->getInventory()->remove($player->getInventory()->getItemInHand());

                $items = $yaml->get("items");
                foreach ($items as $itemName => $itemData) {
                    if ($itemName === "playerhider2") {
                        var_dump($itemData["item"]);
                        $vanilla = $this->convertItemToVanillaItem($itemData["item"], $itemData["nametagg"]);
                        $player->getInventory()->setItem($itemData["slot"], $vanilla);
                    }
                }
                break;
            case "lobby:teleporter":
                $nav = new NavigatorForm($this->plugin);
                $player->sendForm($nav->sendNavigatorForm());
                break;
            case "lobby:gadgets":
                $gad = new GadgetsForm($this->plugin);
                $player->sendForm($gad->sendGadgetsForm());
                break;
            case "gadget:jetpack":
                $this->giveJet($player);
                break;
            case "null":
                $player->sendMessage("");
                break;
        }
    }
}