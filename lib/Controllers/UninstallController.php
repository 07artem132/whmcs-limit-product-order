<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 30.03.2020, 12:11
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Controllers;

use Exception;
use WHMCS\Database\Capsule;

class UninstallController
{

    public static function dropTable($tableName)
    {
        try {
            Capsule::schema()->dropIfExists($tableName);
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'description' => sprintf(LanguageController::trans('errorDropTable'), $tableName, $e->getMessage())
            );
        }

        return [];
    }
}