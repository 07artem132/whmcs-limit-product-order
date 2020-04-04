<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 31.03.2020, 3:35
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Pages;

use WHMCS\Module\Addon\LimitProductOrder\Interfaces\PageInterface;
use WHMCS\Module\Addon\LimitProductOrder\Models\ProductLimits;
use WHMCS\Module\Addon\LimitProductOrder\Traits\IsRequestMethodTraits;
use WHMCS\Product\Addon;
use WHMCS\Product\Product;
use WHMCS\View\Menu\MenuFactory;

class AdminAddLimitPage implements PageInterface
{
    use IsRequestMethodTraits;
    private $templateName = 'admin_add_limit.tpl';
    private $vars = [];

    function __construct()
    {
        if ($this->isRequestMethod('POST')) {
            foreach ($_POST['rel_id'] as $item) {
                if (strpos($item, 'a') !== false) {
                    $rel_id = (int)filter_var($item, FILTER_SANITIZE_NUMBER_INT);
                    $rel_type = 2;
                } elseif (strpos($item, 'd') !== false) {
                    $rel_id = (int)filter_var($item, FILTER_SANITIZE_NUMBER_INT);
                    $rel_type = 3;
                } else {
                    $rel_id = $item;
                    $rel_type = 1;
                }
                $packageRelative = new ProductLimits();
                $packageRelative->limit = $_POST['limit'];
                $packageRelative->rel_id = $rel_id;
                $packageRelative->type = $rel_type;
                $packageRelative->saveOrFail();
            }
            redir('module=LimitProductOrder', 'addonmodules.php');
        }
        $packageRelative = ProductLimits::all()->keyBy(function ($item) {
            return $item->type . ':' . $item->rel_id;
        });

        $this->vars['associateList'] = collect([
            'product' => Product::join('tblproductgroups', 'tblproducts.gid', '=', 'tblproductgroups.id')
                ->orderBy('tblproductgroups.order', 'ASC')
                ->orderBy('tblproducts.order', 'ASC')
                ->orderBy('tblproducts.name', 'ASC')
                ->select('tblproducts.gid', 'tblproducts.id', 'tblproductgroups.name AS groupname', 'tblproducts.name AS productname')
                ->get()->groupBy('gid')->flatten()->transform(function ($item, $key) use ($packageRelative) {
                    return [
                        'id' => $item->id,
                        'groupname' => $item->groupname,
                        'text' => $item->productname,
                        'exits' => $packageRelative->has('1:' . $item->id)
                    ];
                })->reject(function ($item, $key) {
                    return $item['exits'];
                })->groupBy('groupname')
        ])->merge([
            'addon' => Addon::all()->transform(function ($item, $key) use ($packageRelative) {
                return [
                    'id' => 'a' . $item->id,
                    'groupname' => 'Дополнение',
                    'text' => $item->name,
                    'exits' => $packageRelative->has('2:' . $item->id)
                ];
            })->reject(function ($item, $key) {
                return $item['exits'];
            })->groupBy('groupname')
        ])->reject(function ($item, $key) {
            return $item->isEmpty();
        })->toArray();
    }

    function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @return array
     */
    function getVars(): array
    {
        return $this->vars;
    }

    function getSubMenu(): ?MenuFactory
    {
        return null;
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array
    {
        return [
            'Главная' => 'addonmodules.php?module=LimitProductOrder',
            'Добавление лимита' => '',
        ];
    }
}