<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 31.03.2020, 4:08
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Models;

use Exception;
use Throwable;
use WHMCS\Database\Capsule;
use WHMCS\Model\AbstractModel;
use WHMCS\Product\Addon;
use WHMCS\Product\Group;
use WHMCS\Product\Product;

class ProductLimits extends AbstractModel
{
    public $incrementing = true;
    protected $table = "mod_addon_limit_product_order_limits";
    protected $primaryKey = 'id';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getServiceUrlAttribute(): string
    {
        switch ($this->type) {
            case 1:
                return 'clientsservices.php?productselect=' . $this->rel_id;
            case 2:
                return 'clientsservices.php?aid=' . $this->rel_id;
            case 3:
                return 'clientsdomains.php?id=' . $this->rel_id;
        }
    }

    public function getFullTextAttribute(): string
    {
        try {
            switch ($this->type) {
                case 1:
                    $product = Product::findOrFail($this->rel_id);
                    return Group::find($product->gid)->name . '\\' . $product->name;
                case 2:
                    return 'Дополнение\\' . Addon::findOrFail($this->rel_id)->name;
                case 3:
                    $domain = Capsule::table('tbldomainpricing')->where('id', $this->rel_id)->first();
                    if (empty($domain)) {
                        throw new Exception('Вероятно удален домен');
                    }
                    return 'Домен\\' . $domain->extension;
                default:
                    return 'unknown  type';
            }
        } catch (Throwable $e) {
            return 'Вероятно удален продукт';
        }
    }
}