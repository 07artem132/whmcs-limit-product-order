<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 30.03.2020, 12:19
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Menu;

use WHMCS\Module\Addon\LimitProductOrder\Configs\ModuleConfig;
use WHMCS\View\Menu\MenuFactory;

class AdminAreaMenu extends MenuFactory
{
    protected $rootItemName = "limit product order nav bar";

    public function navbar()
    {
        return $this->loader->load($this->buildMenuStructure($this->getNavBarStructure()));
    }

    protected function getNavBarStructure()
    {
        $menuItems = [
            [
                "name" => "index",
                "label" => 'Настройки ограничений',
                "uri" => ModuleConfig::getModuleLink() . "&action=index",
                "order" => 1,
                "attributes" => [
                    "class" => !array_key_exists('action', $_GET) || $_GET['action'] === 'index' ? 'active' : ''
                ]
            ]
        ];

        return $menuItems;
    }

}


