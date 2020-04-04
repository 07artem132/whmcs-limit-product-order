<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 08.11.19 1:18
 *
 */


use WHMCS\Module\Addon\LimitProductOrder\Configs\ModuleConfig;
use WHMCS\Module\Addon\LimitProductOrder\Models\ProductLimits;
use WHMCS\Product\Addon as AddonInfo;
use WHMCS\Product\Product;
use WHMCS\Service\Addon;
use WHMCS\Service\Service;

add_hook('AdminAreaHeadOutput', 99999999, function ($vars) {
    try {
        if (!isset($_GET['module']) || $_GET['module'] != ModuleConfig::getModuleName()) {
            return null;
        }

        foreach (scandir(ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin') as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            echo '<link rel="stylesheet" type="text/css" href="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/css/admin/' . $item . '">';
        }

        foreach (scandir(ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin') as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            echo '<script type="text/javascript" charset="utf8" src="/modules/addons/' . ModuleConfig::getModuleName() . '/templates/js/admin/' . $item . '"></script>';
        }
    } catch (Exception $e) {
        logActivity(ModuleConfig::getModuleName() . ' [AdminAreaHeadOutput]:' . $e->getMessage(), 0);
    }
});


add_hook('ClientAreaPageCart', 1, function ($vars) {
    if (empty(WHMCS\Session::get("uid")) || $_GET['a'] == 'view') {
        return [];
    }

    $products = collect($vars['products'])->keyBy('pid');
    $userId = WHMCS\Session::get("uid");
    $onlyOneServicesOrdered = ProductLimits::where('type', '=', 1)->get();

    foreach ($onlyOneServicesOrdered as $onlyOneServiceOrdered) {
        if (!$products->has($onlyOneServiceOrdered->rel_id)) {
            continue;
        }

        $CountUserActiveService = Service::where(function ($query) use ($onlyOneServiceOrdered, $userId) {
            $query->where('packageid', '=', $onlyOneServiceOrdered->rel_id)
                ->where('userid', '=', $userId);
        })->where(function ($query) {
            $query->where('domainstatus', '=', 'Pending')
                ->orWhere('domainstatus', '=', 'Active')
                ->orWhere('domainstatus', '=', 'Suspended');
        })->count();

        if ($CountUserActiveService >= $onlyOneServiceOrdered->limit) {
            $products->offsetUnset($onlyOneServiceOrdered->rel_id);
        }
    }

    return [
        'products' => $products->toArray()
    ];
});

add_hook('ClientAreaPageCart', 1, function ($vars) {
    if (empty(WHMCS\Session::get("uid")) || $_GET['gid'] != 'addons') {
        return [];
    }
    $onlyOneServicesOrdered = ProductLimits::where('type', '=', 2)->get();
    $addons = collect($vars['addons'])->keyBy('id')->toArray();


    foreach ($onlyOneServicesOrdered as $onlyOneServiceOrdered) {
        if (!array_key_exists($onlyOneServiceOrdered->rel_id, $addons)) {
            continue;
        }

        for ($i = 0; $i < count($addons[$onlyOneServiceOrdered->rel_id]['productids']); $i++) {
            $hostingId = $addons[$onlyOneServiceOrdered->rel_id]['productids'][$i]['id'];

            $CountUserActiveAddons = Addon::where(function ($query) use ($onlyOneServiceOrdered, $hostingId) {
                $query->where('addonid', '=', $onlyOneServiceOrdered->rel_id)
                    ->where('hostingid', '=', $hostingId);
            })->where(function ($query) {
                $query->where('status', '=', 'Pending')
                    ->orWhere('status', '=', 'Active')
                    ->orWhere('status', '=', 'Suspended');
            })->count();

            if ($CountUserActiveAddons >= $onlyOneServiceOrdered->limit) {
                unset($addons[$onlyOneServiceOrdered->rel_id]['productids'][$i]);
            }
        }
    }

    return [
        'addons' => $addons
    ];
});

add_hook('ShoppingCartValidateCheckout', 1, function ($vars) {
    $userId = $vars['userid'];
    $products = collect($_SESSION['cart']['products']);
    if (empty($products)) {
        return [];
    }
    $onlyOneServicesOrdered = ProductLimits::where('type', '=', 1)->get();

    foreach ($onlyOneServicesOrdered as $onlyOneServiceOrdered) {
        if ($products->where('pid', $onlyOneServiceOrdered->rel_id)->count() == 0) {
            continue;
        }

        $CountUserActiveService = Service::where(function ($query) use ($onlyOneServiceOrdered, $userId) {
            $query->where('packageid', '=', $onlyOneServiceOrdered->rel_id)
                ->where('userid', '=', $userId);
        })->where(function ($query) {
            $query->where('domainstatus', '=', 'Pending')
                ->orWhere('domainstatus', '=', 'Active')
                ->orWhere('domainstatus', '=', 'Suspended');
        })->count();

        $allCount = $products->where('pid', $onlyOneServiceOrdered->rel_id)->count() + $CountUserActiveService;;

        if ($allCount >= $onlyOneServiceOrdered->limit) {
            $product = Product::find($onlyOneServiceOrdered->rel_id);
            return [
                'К сожалению вы не можете заказать более ' . $onlyOneServiceOrdered->limit . ' услуги: ' . $product->name . '<br/>' .
                '<a href="cart.php?a=view">Нажмите здесь дабы перейти в корзину и удалить продукт</a>'
            ];
        }
    }
    return [];
});

add_hook('ShoppingCartValidateCheckout', 1, function ($vars) {
    $addons = collect($_SESSION['cart']['addons']);
    if (empty($addons)) {
        return [];
    }
    $onlyOneServicesOrdered = ProductLimits::where('type', '=', 2)->get();

    foreach ($onlyOneServicesOrdered as $onlyOneServiceOrdered) {
        if ($addons->where('id', $onlyOneServiceOrdered->rel_id)->count() == 0) {
            continue;
        }

        foreach ($addons->where('id', $onlyOneServiceOrdered->rel_id) as $item) {
            $CountUserActiveAddons = Addon::where(function ($query) use ($onlyOneServiceOrdered, $item) {
                $query->where('addonid', '=', $onlyOneServiceOrdered->rel_id)
                    ->where('hostingid', '=', $item['productid']);
            })->where(function ($query) {
                $query->where('status', '=', 'Pending')
                    ->orWhere('status', '=', 'Active')
                    ->orWhere('status', '=', 'Suspended');
            })->count();

            $allCount = $addons->where('id', $onlyOneServiceOrdered->rel_id)->count() + $CountUserActiveAddons;;

            if ($allCount >= $onlyOneServiceOrdered->limit) {
                $addonInfo = AddonInfo::find($onlyOneServiceOrdered->rel_id);
                return [
                    'К сожалению вы не можете заказать более ' . $onlyOneServiceOrdered->limit . ' услуги: ' . $addonInfo->name . '<br/>' .
                    '<a href="cart.php?a=view">Нажмите здесь дабы перейти в корзину и удалить продукт</a>'
                ];
            }
        }

    }
    return [];
});
