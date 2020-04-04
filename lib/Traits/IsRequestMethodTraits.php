<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 31.03.2020, 4:13
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Traits;

trait IsRequestMethodTraits
{

    protected function isRequestMethod($Method)
    {
        if ($_SERVER['REQUEST_METHOD'] === $Method) {
            return true;
        }

        return false;
    }
}