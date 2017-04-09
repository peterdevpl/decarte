<?php

namespace AppBundle\Menu;

class Menu
{
    public function getEntries()
    {
        return [
            new MenuEntry('shop_list_collections', 'Zaproszenia ślubne', ['type' => 'zaproszenia-slubne']),
            new MenuEntry('shop_order_samples', 'Zamów próbki'),
            new MenuEntry('shop_list_collections', 'Winietki', ['type' => 'winietki']),
            new MenuEntry('shop_list_collections', 'Etykiety, zawieszki', ['type' => 'etykiety-i-zawieszki']),
            new MenuEntry('shop_list_collections', 'Menu', ['type' => 'menu-weselne']),
            new MenuEntry('shop_list_collections', 'Podziękowania', ['type' => 'podziekowania']),
            new MenuEntry('shop_list_collections', 'Księgi gości', ['type' => 'ksiegi-gosci']),
            new MenuEntry('static_page', 'Candy table', ['slugName' => 'candy-table']),
            new MenuEntry('shop_list_collections', 'Poduszki pod obrączki', ['type' => 'poduszki-pod-obraczki']),
            new MenuEntry('shop_list_collections', 'Gadżety weselne', ['type' => 'gadzety-weselne']),
            new MenuEntry('shop_list_collections', 'Dekoracje samochodowe', ['type' => 'dekoracje-samochodowe']),
        ];
    }
}
