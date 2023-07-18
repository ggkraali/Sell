<?php

namespace ayd1ndemirci\SellSystem\command;

use ayd1ndemirci\SellSystem\form\SellForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SellCommand extends Command
{

    public function __construct()
    {
        parent::__construct("sat", "Eşya satma menüsü");
        $this->setPermission("sell.permission");
        $this->setAliases(["sell"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) return;
        $sender->sendForm(new SellForm());
    }
}