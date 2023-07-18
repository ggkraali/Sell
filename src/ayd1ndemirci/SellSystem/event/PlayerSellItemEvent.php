<?php

namespace ayd1ndemirci\SellSystem\event;

use pocketmine\event\Event;
use pocketmine\item\Item;
use pocketmine\player\Player;

class PlayerSellItemEvent extends Event
{
    public Player $player;

    public Item $item;

    public int $count;

    public float $price;

    public function __construct(Player $player, Item $item, int $count, float $price)
    {
        $this->player = $player;
        $this->item = $item;
        $this->count = $count;
        $this->price = $price;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

}