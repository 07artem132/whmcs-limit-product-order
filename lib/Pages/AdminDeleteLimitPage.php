<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 31.03.2020, 4:22
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Pages;

use WHMCS\Module\Addon\LimitProductOrder\Interfaces\PageInterface;
use WHMCS\Module\Addon\LimitProductOrder\Models\ProductLimits;
use WHMCS\View\Menu\MenuFactory;

class AdminDeleteLimitPage implements PageInterface
{
    private $templateName = 'admin_delete.tpl';
    private $vars = [];

    function __construct()
    {
        ProductLimits::destroy($_GET['id']);
        redir('module=LimitProductOrder', 'addonmodules.php');
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