<?php

namespace ayd1ndemirci\SellSystem\form;

use ayd1ndemirci\SellSystem\event\PlayerSellItemEvent;
use ayd1ndemirci\SellSystem\Main;
use ayd1ndemirci\SellSystem\manager\SellManager;
use dktapps\pmforms\ModalForm;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class SellAllForm extends ModalForm
{

    public function __construct()
    {
        parent::__construct(
            "Tümünü Sat",
            "§7Envanterinde ki herşeyi satmayı onaylıyor musun?",
            function (Player $player, bool $data): void
            {
                if ($data) {
                    $totalPrice = 0;

                    foreach (Main::getInstance()->getConfig()->get("Items") as $items) {
                        $exp = explode(":", $items);
                        $item = StringToItemParser::getInstance()->parse($exp[0]);
                        $price = $exp[1];
                        foreach ($player->getInventory()->getContents() as $content) {
                            if ($content->getTypeId() === $item->getTypeId()) {
                                $totalPrice += $price * $content->getCount();
                                $player->getInventory()->removeItem($content);
                                $sellItems[$content->getName()] = $price * $content->getCount();
                            }
                        }
                    }

                    if ($totalPrice <= 0) {
                        $player->sendForm(new InformationForm("Sat", "§cEnvanterinde satılabilicek eşya yok.", "Tamam", "Kapat"));
                        return;
                    }

                    if (empty($sellItems)) return;
                    $count = 0;
                    foreach ($sellItems as $itemName => $itemPrice) {
                         $count++;
                    }
                    $player->sendForm(new InformationForm("Sat", "§aEnvanterindeki toplam §2{$count} §aeşya satıldı. Kazanç: §2".SellManager::getNumberFormatter($totalPrice). " TL", "Tamam", "Kapat"));
                    SellManager::addMoney($player, $totalPrice);
                }else $player->removeCurrentWindow();
            },
            "Evet, Onaylıyorum",
            "Hayır"
        );
    }
}