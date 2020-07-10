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
    public $assetsPath;

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

    public function install()
    {
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'quality_assurance_hooks` (' .
            '`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,' .
            '`name` varchar(255) NOT NULL,' .
            '`content` text NOT NULL,' .
            'PRIMARY KEY (`id`),' .
            'UNIQUE KEY `name` (`name`)' .
            ') ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        return Db::getInstance()->execute($query) && parent::install();
    }

    /**
     * Dispatch hooks
     *
     * @param string $methodName
     * @param array $arguments
     */
    public function __call($methodName, array $arguments)
    {
        $hookName = preg_replace('~^hook~', '', $methodName);
        $params = !empty($arguments[0]) ? $arguments[0] : [];

        $query = new DbQuery();
        $query->select('content');
        $query->from('quality_assurance_hooks');
        $query->where('name = "' . pSQL($hookName) . '"');

        $row = Db::getInstance()->getRow($query);
        if (!empty($row)) {
            try {
                eval($row['content']);
            } catch (Throwable $e) {
                echo $e->getMessage();
            }
        }
    }
}
