<?php

namespace ayd1ndemirci\SellSystem\form;

use ayd1ndemirci\SellSystem\Main;
use dktapps\pmforms\ModalForm;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class SellHandForm extends ModalForm
{

    /**
     * @param Player $player
     */
    public function __construct(\pocketmine\player\Player $player)
    {
        parent::__construct(
            "Sat",
            "§aElindeki eşyayı satmayı onaylıyor musun?",
            function (Player $player, bool $data): void
            {
                if ($data) {
                    $hand = $player->getInventory()->getItemInHand();
                    if ($hand->getTypeId() !== VanillaItems::AIR()->getTypeId()) {
                        $result = false;
                        foreach (Main::getInstance()->getConfig()->get("Items") as $items) {
                            $exp = explode(":", $items);
                            $item = StringToItemParser::getInstance()->parse($exp[0]);
                            $price = $exp[1];
                            if ($hand->getTypeId() === $item->getTypeId()) {
                                $result = true;
                                $player->sendForm(new SellHandSelectForm($player, $hand, $price));
                            }
                        }
                        if (!$result) {
                            $player->sendForm(new InformationForm("Sat", "§cBu eşya satılabilir eşya değil.", "Tamam", "Kapat"));
                            return;
                        }
                    }else $player->sendForm(new InformationForm("Sat", "§cEline bir eşya al", "Tamam", "Kapat"));
                }
            },
            "Evet",
            "Hayır"
        );
    }
}