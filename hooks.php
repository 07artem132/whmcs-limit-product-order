<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 08.11.19 1:18
 *
 */

/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 06.11.19 23:07
 *
 */


use WHMCS\Module\Addon\LimitProductOrder\Configs\ModuleConfig;
use WHMCS\Product\Product;
use WHMCS\Service\Service;

add_hook('ClientAreaPageCart', 1, function ($vars) {
    if (empty(WHMCS\Session::get("uid")) || $_GET['a'] == 'view') {
        return [];
    }

    $products = collect($vars['products'])->keyBy('pid');
    $userId = WHMCS\Session::get("uid");
    $onlyOneServicesOrdered = explode("\r\n", ModuleConfig::getModuleSetting('product_ids'));

    foreach ($onlyOneServicesOrdered as $onlyOneServiceOrdered) {
        if (!$products->has($onlyOneServiceOrdered)) {
            continue;
        }

        $CountUserActiveService = Service::where('packageid', $onlyOneServiceOrdered)->Active()->UserId($userId)->count();

        if ($CountUserActiveService > 0) {
            $products->offsetUnset($onlyOneServiceOrdered);
        }
    }

    return [
        'products' => $products->toArray()
    ];
});

add_hook('ShoppingCartValidateCheckout', 1, function ($vars) {
    $userId = $vars['userid'];
    $products = collect($_SESSION['cart']['products']);
    $productIds = $products->pluck('pid');
    $onlyOneServicesOrdered = explode("\r\n", ModuleConfig::getModuleSetting('product_ids'));

    foreach ($onlyOneServicesOrdered as $onlyOneServiceOrdered) {
        if (!$productIds->contains($onlyOneServiceOrdered)) {
            continue;
        }

        $CountUserActiveService = Service::where('packageid', $onlyOneServiceOrdered)->Active()->UserId($userId)->count();

        if ($CountUserActiveService > 0 || $products->where('pid', $onlyOneServiceOrdered)->count() > 1) {
            $product = Product::find($onlyOneServiceOrdered);
            return [
                'К сожалению вы не можете заказать более одной услуги: ' . $product->name . '<br/>' .
                '<a href="cart.php?a=view">Нажмите здесь дабы перейти в корзину и удалить продукт</a>'
            ];
        }

    }
    return [];
});
