<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 30.03.2020, 12:22
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Pages;

use WHMCS\Module\Addon\LimitProductOrder\Interfaces\PageInterface;
use WHMCS\Module\Addon\LimitProductOrder\Models\ProductLimits;
use WHMCS\View\Menu\MenuFactory;

class AdminIndexPage implements PageInterface
{
    private $templateName = 'admin_index.tpl';
    private $vars = [];

    function __construct()
    {
        $this->vars['LimitList'] = ProductLimits::all();
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
            'Главная' => '',
        ];
    }
}