<?php

declare(strict_types=1);

namespace LobbySystem\events;

use LobbySystem\database\YamlConfig;
use LobbySystem\MainClass;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\sound\BlazeShootSound;

class MoveEvent implements Listener
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $yaml = new YamlConfig($this->plugin);

        if ($yaml->get("safeWorld") === true) {
            if ($player->getPosition()->getFloorY() < 0) $player->teleport($this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        }

        //Gadgets
        if ($player->getArmorInventory()->getChestplate()->getCustomName() === "§cJetpack") {
            $player->setMotion($player->getDirectionVector()->multiply(0.5));

            $player->getWorld()->addParticle(new Vector3($player->getPosition()->getX(), $player->getPosition()->getY() + 1.0, $player->getPosition()->getZ() + 0.35), new FlameParticle());
            $player->getWorld()->addParticle(new Vector3($player->getPosition()->getX(), $player->getPosition()->getY() + 1.0, $player->getPosition()->getZ() + 0.15), new ExplodeParticle());

            $player->getWorld()->addParticle(new Vector3($player->getPosition()->getX() + 0.35, $player->getPosition()->getY() + 1.0, $player->getPosition()->getZ()), new FlameParticle());
            $player->getWorld()->addParticle(new Vector3($player->getPosition()->getX() + 0.15, $player->getPosition()->getY() + 1.0, $player->getPosition()->getZ()), new ExplodeParticle());

            $player->getWorld()->addSound($player->getPosition()->asPosition(), new BlazeShootSound());
        }
    }

    /**
     * It is a MOVE EVENT!!!!!!!!!!!!1
     */
    public function onSneak(PlayerToggleSneakEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player->getArmorInventory()->getChestplate()->getCustomName() === "§cJetpack") $player->getArmorInventory()->setChestplate(VanillaItems::AIR());
    }
}