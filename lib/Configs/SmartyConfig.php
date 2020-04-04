<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 30.03.2020, 12:15
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Configs;


class SmartyConfig
{

    public static function GetTemplateDir()
    {
        return ModuleConfig::getWhmcsRootDir() . '/modules/addons/' . ModuleConfig::getModuleName() . '/templates/';
    }

    public static function GetCompileDir()
    {
        global $templates_compiledir;

        return $templates_compiledir;
    }
}