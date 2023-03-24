<?php

declare(strict_types=1);

namespace LobbySystem\forms;

use LobbySystem\database\YamlConfig;
use LobbySystem\forms\pmforms\MenuForm;
use LobbySystem\forms\pmforms\MenuOption;
use LobbySystem\MainClass;
use LobbySystem\Manager;
use pocketmine\player\Player;

class GadgetsForm
{
    private array $buttons = [];

    public function __construct(private MainClass $plugin)
    {
    }

    public function sendGadgetsForm(): MenuForm
    {
        return new MenuForm(
            $this->getTitle(),
            $this->getDescription(),
            $this->sendOptions(),
            function (Player $player, int $selected): void {
                $selected2 = $selected + 1;

                $buttons = $this->buttons;
                $action = (string)$buttons[$selected2]["action"];
                $permissions = (string)$buttons[$selected2]["permissions"];

                if ($permissions !== "") {
                    if ($player->hasPermission($permissions) === true or $this->plugin->getServer()->isOp($player->getName()) === true) {
                        $manager = new Manager($this->plugin);
                        $manager->action($player, $action);
                    } else {
                        $player->sendMessage("§cNo Permissions");
                    }
                } else {
                    $manager = new Manager($this->plugin);
                    $manager->action($player, $action);
                }
            }
        );
    }

    public function getTitle(): string
    {
        $yaml = new YamlConfig($this->plugin);
        $forms = $yaml->get("forms");
        foreach ($forms as $form => $formData) {
            if ($form === "gadgets") {
                return $formData["title"];
            }
        }
        return "Error";
    }

    public function getDescription(): string
    {
        $yaml = new YamlConfig($this->plugin);
        $forms = $yaml->get("forms");
        foreach ($forms as $form => $formData) {
            if ($form === "gadgets") {
                return $formData["description"];
            }
        }
        return "Error";
    }

    public function setButtons(): void
    {
        $yaml = new YamlConfig($this->plugin);

        $forms = $yaml->get("forms");
        foreach ($forms as $form => $formData) {
            if ($form === "gadgets") {
                if (isset($formData["buttons"])) {
                    $button = $formData["buttons"];
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($button[(string)$i])) {
                            $count = $button[(string)$i];
                            $action = $count["action"];
                            $permissions = $count["permissions"];

                            $this->buttons[$i] = ["action" => $action, "permissions" => $permissions];
                        }
                    }
                }
            }
        }
    }

    public function sendOptions(): array
    {
        $yaml = new YamlConfig($this->plugin);

        $options = [];

        $forms = $yaml->get("forms");
        foreach ($forms as $form => $formData) {
            if ($form === "gadgets") {
                if (isset($formData["buttons"])) {
                    $button = $formData["buttons"];
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($button[(string)$i])) {
                            $options[] = new MenuOption($button[(string)$i]["text"]);
                        }
                    }
                }
            }
        }
        $this->setButtons();
        return $options;
    }
}