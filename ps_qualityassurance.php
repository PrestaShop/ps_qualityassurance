<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class Ps_Qualityassurance extends Module
{
    public $jsPath;
    public $cssPath;

    public $tabs = [
        [
            'name' => 'Quality Assurance',
            'class_name' => 'AdminQualityAssurance',
            'visible' => true,
            'parent_class_name' => 'CONFIGURE',
        ]
    ];

    public function __construct()
    {
        $this->name = 'ps_qualityassurance';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->bootstrap = true;
        $this->ajax = (bool) Tools::getValue('ajax');

        parent::__construct();

        $this->displayName = $this->trans('Quality Assurance', [], 'Modules.Qualityassurance.Admin');
        $this->description = $this->trans('Helps you to test hooks.', [], 'Modules.Qualityassurance.Admin');
        $this->ps_versions_compliancy = ['min' => '1.7.1.0', 'max' => _PS_VERSION_];

        $this->assetsPath = $this->_path . 'views/dist/';
    }
}
