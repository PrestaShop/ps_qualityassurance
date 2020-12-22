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
class Ps_QualityAssuranceListenerModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        if (!$this->jsEventListenerIsEnabled()) {
            return;
        }

        if (Tools::getValue('action') !== 'RecordJSHookExecution') {
            $this->ajaxDie('Unknown action');
        }

        $this->processRecordJSHookExecution();
    }

    public function processRecordJSHookExecution()
    {
        $hookName = (string) Tools::getValue('name');
        $hookParameters = Tools::getValue('parameters');
        if (is_array($hookParameters)) {
            $hookParameters = json_encode($hookParameters);
        }

        if (!$hookName) {
            $this->renderJson(['error' => 'Missing hook name']);
            die();
        }

        Db::getInstance()->insert(
            'quality_assurance_hook_logs',
            [
                'request_identifier' => uniqid(),
                'hook_name' => 'JS Event - ' . pSQL($hookName),
                'hook_parameters' => pSQL($hookParameters, true),
                'output' => '',
                'called_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'error' => 0,
            ]
        );
        $this->ajaxDie('Execution registered');
    }

    /**
     * @return bool
     */
    protected function jsEventListenerIsEnabled()
    {
        return (bool) Configuration::get('PS_QA_MODULE_LISTEN_JS');
    }

    /**
     * @param array $data
     */
    private function renderJson($data)
    {
        header('Content-Type: application/json');
        $this->ajaxDie(json_encode($data));
    }
}
