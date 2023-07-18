<?php

namespace ayd1ndemirci\SellSystem;

use ayd1ndemirci\SellSystem\command\SellCommand;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\StringToItemParser;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\recipe\StringIdMetaItemDescriptor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    public static Main $main;

    public Config $config;

    public function onLoad(): void
    {
        self::$main = $this;
        $this->saveResource("sell.yml");
        $this->config = new Config($this->getDataFolder()."sell.yml", 2);
    }

    public function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register("sat", new SellCommand());
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public static function getInstance(): self
    {
        return self::$main;
    }
}