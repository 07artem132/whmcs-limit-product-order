<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 08.11.19 1:18
 *
 */

use WHMCS\Module\Addon\LimitProductOrder\Configs\ModuleConfig;
use WHMCS\Module\Addon\LimitProductOrder\Controllers\InstallController;
use WHMCS\Module\Addon\LimitProductOrder\Controllers\PageController;
use WHMCS\Module\Addon\LimitProductOrder\Controllers\UninstallController;
use WHMCS\Module\Addon\LimitProductOrder\Menu\AdminAreaMenu;
use WHMCS\Module\Addon\Setting;

/**
 * @return array
 */
function LimitProductOrder_config()
{

    $config = [
        "name" => "Ограничить кол-во активных услуг продукта",
        "description" => "Данный модуль позволяет ввести ограничение на кол-во активных услуг продукта",
        "version" => "1",
        "author" => "service-voice",
        "language" => "russian",
        "fields" => [
            "DeleteTableWhenDisabled" => [
                "FriendlyName" => "Удалять данные модуля при отключении ?",
                "Type" => "yesno",
                "Description" => " Отметьте здесь дабы удалить данные модуля при отключении оного.",
            ]
        ]
    ];

    return $config;
}

function LimitProductOrder_output($vars)
{
    $PageController = new PageController($vars);
    $PageController->setDefaultAction('index');
    $PageController->setSuffixTemplate('admin');
    $PageController->setMenuTemplate('include\navbar.tpl');
    $PageController->setBreadcrumbTemplate('include\breadcrumb.tpl');
    $PageController->setMenu((new AdminAreaMenu())->navbar());
    $PageController->run();
}

function LimitProductOrder_activate()
{

    if (!empty($error = InstallController::createTableLimits())) {
        return $error;
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно активирован',
    );

}


function LimitProductOrder_deactivate()
{
    if (!empty($dropTable = Setting::Module(ModuleConfig::getModuleName())->where('setting', '=', 'DeleteTableWhenDisabled')->first())) {
        if ($dropTable->value === 'on') {
            if (!empty($error = UninstallController::dropTable('mod_addon_limit_product_order_limits'))) {
                return $error;
            }
        }
    }

    return array(
        'status' => 'success',
        'description' => 'Модуль успешно деактивирован'
    );

}