<?php

declare(strict_types=1);

namespace LobbySystem\forms;

use LobbySystem\database\YamlConfig;
use LobbySystem\forms\pmforms\MenuForm;
use LobbySystem\forms\pmforms\MenuOption;
use LobbySystem\MainClass;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class NavigatorForm
{
    private array $buttons = [];

    public function __construct(private MainClass $plugin)
    {
    }

    public function sendNavigatorForm(): MenuForm
    {
        return new MenuForm(
            $this->getTitle(),
            $this->getDescription(),
            $this->sendOptions(),
            function (Player $player, int $selected): void {
                $selected2 = $selected + 1;

                $buttons = $this->buttons;

                $x = $buttons[$selected2]["x"];
                $y = $buttons[$selected2]["y"];
                $z = $buttons[$selected2]["z"];

                $player->teleport(new Vector3($x, $y, $z));
            }
        );
    }

    public function getTitle(): string
    {
        $yaml = new YamlConfig($this->plugin);
        $forms = $yaml->get("forms");
        foreach ($forms as $form => $formData) {
            if ($form === "navigator") {
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
            if ($form === "navigator") {
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
            if ($form === "navigator") {
                if (isset($formData["buttons"])) {
                    $button = $formData["buttons"];
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($button[(string)$i])) {
                            $count = $button[(string)$i];
                            $x = $count["x"];
                            $y = $count["y"];
                            $z = $count["z"];

                            $this->buttons[$i] = ["x" => $x, "y" => $y, "z" => $z];
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
            if ($form === "navigator") {
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