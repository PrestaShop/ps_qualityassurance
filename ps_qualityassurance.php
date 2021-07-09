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
        ],
    ];

    /**
     * A HTTP request identifier to be able to
     * gather hook call logs that have been triggered by the same request
     *
     * @var string
     */
    protected $requestIdentifier;

    public function __construct()
    {
        $this->name = 'ps_qualityassurance';
        $this->tab = 'administration';
        $this->version = '1.1.1';
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
        $createHookTableQuery = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'quality_assurance_hooks` (' .
            '`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,' .
            '`name` varchar(255) NOT NULL,' .
            '`content` text NOT NULL,' .
            '`enabled` BOOLEAN NOT NULL DEFAULT TRUE,' .
            'PRIMARY KEY (`id`),' .
            'UNIQUE KEY `name` (`name`)' .
            ') ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        $createHookLogsQuery = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'quality_assurance_hook_logs` (' .
            '`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,' .
            '`request_identifier` varchar(255) NOT NULL,' .
            '`hook_name` varchar(255) NOT NULL,' .
            '`hook_parameters` text NOT NULL,' .
            '`output` text NOT NULL,' .
            '`called_at` datetime NOT NULL,' .
            '`error` TINYINT(1) NOT NULL,' .
            'PRIMARY KEY (`id`)' .
            ') ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        return Db::getInstance()->execute($createHookTableQuery)
            && Db::getInstance()->execute($createHookLogsQuery)
            && parent::install();
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

        $payload = $this->getRegisteredHookPayload($hookName);

        if (empty($payload['enabled'])) {
            $this->recordHookCall($hookName, $arguments);

            return;
        }

        $params = !empty($arguments[0]) ? $arguments[0] : [];
        try {
            $output = eval($payload['content']);
            $this->recordHookCall($hookName, $params, $output);

            return $output;
        } catch (Throwable $e) {
            $output = $e->getMessage();
            $this->recordHookCall($hookName, $params, $output, true);

            throw $e;
        }
    }

    public function getContent()
    {
        $moduleAdminLink = Context::getContext()->link->getAdminLink('AdminQualityAssurance', true);
        Tools::redirectAdmin($moduleAdminLink);
    }

    /**
     * Look whether there is a payload registered for this hook
     *
     * @param string $hookName
     *
     * @return array|null
     */
    protected function getRegisteredHookPayload($hookName)
    {
        $query = new DbQuery();
        $query->select('content')
            ->select('enabled');
        $query->from('quality_assurance_hooks');
        $query->where('name = "' . pSQL($hookName) . '"');

        $row = Db::getInstance()->getRow($query);

        return $row;
    }

    /**
     * @param string $hookName
     * @param array $arguments
     * @param string|null $output
     * @param bool|null $error
     *
     * @return bool
     */
    protected function recordHookCall($hookName, array $arguments, $output = null, $error = false)
    {
        $requestIdentifier = $this->getRequestIdentifier();

        $parameters = sprintf("('%s', '%s', '%s', '%s', now(), %d)",
            pSQL($requestIdentifier),
            pSQL($hookName),
            $this->makeArgumentsReadable($arguments),
            sprintf('%s: %s',
                (!$error ? 'Output' : 'Error'),
                pSQL($output)
            ),
            ($error ? 1 : 0)
        );

        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'quality_assurance_hook_logs (request_identifier, hook_name, hook_parameters, output, called_at, error) VALUES '
            . $parameters;

        return Db::getInstance()->execute($sql);
    }

    /**
     * @param array $arguments
     *
     * @return string
     */
    protected function makeArgumentsReadable(array $arguments)
    {
        $result = [];
        foreach ($arguments as $key => $value) {
            if (is_array($value)) {
                $result[$key] = 'Array';
            } elseif (is_object($value)) {
                $result[$key] = sprintf('Object %s', get_class($value));
            } else {
                $result[$key] = (string) $value;
            }
        }

        return json_encode($result);
    }

    /**
     * @return string
     */
    protected function getRequestIdentifier()
    {
        if ($this->requestIdentifier === null) {
            $this->requestIdentifier = uniqid();
        }

        return $this->requestIdentifier;
    }
}
