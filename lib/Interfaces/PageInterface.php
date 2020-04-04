<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 30.03.2020, 12:16
 *
 */

namespace WHMCS\Module\Addon\LimitProductOrder\Interfaces;

use WHMCS\View\Menu\MenuFactory;

interface  PageInterface
{
    /**
     * @return string
     */
    public function getTemplateName(): string;

    /**
     * @return array
     */
    public function getVars(): array;

    /**
     * @return MenuFactory|null
     */
    public function getSubMenu(): ?MenuFactory;

    /**
     * @return array
     */
    public function getBreadcrumb(): array;
}