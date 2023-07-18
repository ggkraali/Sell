<?php

namespace ayd1ndemirci\SellSystem\manager;

use pocketmine\item\Item;
use pocketmine\player\Player;
use onebone\economyapi\EconomyAPI;

class SellManager
{
    public static function getItemCount(Player $player, Item $item): int
    {
        return array_sum(array_map(function(Item $items)
        {
            return $items->getCount();
        }, $player->getInventory()->all($item)));
    }

    public static function addMoney(Player $player, float $price): void
    {
        EconomyAPI::getInstance()->addMoney($player, $price);
    }

    public static function getNumberFormatter(float $price): string
    {
        return number_format($price, 2, ",", ".");
    }
}