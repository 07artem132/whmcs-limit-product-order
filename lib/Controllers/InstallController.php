<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 30.03.2020, 12:12
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Controllers;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use WHMCS\Database\Capsule;

class InstallController
{
    public static function createTableLimits()
    {
        try {
            if (!Capsule::schema()->hasTable('mod_addon_limit_product_order_limits')) {
                Capsule::schema()->create('mod_addon_limit_product_order_limits', function ($table) {
                    /** @var Blueprint $table */
                    $table->increments('id');
                    $table->integer('rel_id');
                    $table->integer('type');
                    $table->integer('limit');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf('Ошибка при создании таблицы: %s , %s', 'mod_addon_limit_product_order_limits', $e->getMessage())
            );
        }
        return [];
    }
}