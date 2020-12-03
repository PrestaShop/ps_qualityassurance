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
class AdminQualityAssuranceController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;

        if ($this->ajax) {
            $this->display_header_javascript = false;
            $this->display_footer = false;
            $this->display_header = false;
        }
    }

    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign([
            'pathApp' => $this->module->assetsPath . 'back.js?v=' . mt_rand(),
        ]);
        $this->addCss($this->module->assetsPath . 'back.css');

        Media::addJsDef([
            'qualityAssurance' => [
                'urls' => [
                    'delete' => $this->generateAjaxUrl('DeleteHook'),
                    'hooks' => $this->generateAjaxUrl('GetHooks'),
                    'register' => $this->generateAjaxUrl('RegisterHook'),
                    'registeredHooks' => $this->generateAjaxUrl('GetRegisteredHooks'),
                    'update' => $this->generateAjaxUrl('UpdateHook'),
                    'toggleHookStatus' => $this->generateAjaxUrl('ToggleHookStatus'),
                ],
            ],
        ]);

        $this->createTemplate('views/admin/index.tpl');
        $content = $this->context->smarty->fetch($this->getTemplatePath() . 'index.tpl');
        $this->context->smarty->assign(
            [
                'content' => $this->content . $content,
            ]
        );
    }

    public function ajaxProcessRegisterHook()
    {
        $hookName = (string) Tools::getValue('name');
        if (empty($hookName) or !Validate::isHookName($hookName)) {
            $this->renderJson(['error' => 'Invalid hook name']);
        }

        $query = new DbQuery();
        $query->select('id');
        $query->from('quality_assurance_hooks');
        $query->where('name = "' . pSQL($hookName) . '"');
        $row = Db::getInstance()->getRow($query);

        if (!empty($row['id'])) {
            Db::getInstance()->update(
                'quality_assurance_hooks',
                [
                    'content' => pSQL(Tools::getValue('content'), true),
                ],
                'id = ' . (int) $row['id']
            );
        } else {
            Db::getInstance()->insert(
                'quality_assurance_hooks',
                [
                    'name' => pSQL($hookName),
                    'content' => pSQL(Tools::getValue('content'), true),
                ]
            );
            $this->module->registerHook($hookName);
        }

        $this->renderJson([]);
    }

    public function ajaxProcessGetHooks()
    {
        $this->renderJson(Hook::getHooks());
    }

    public function ajaxProcessGetRegisteredHooks()
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('quality_assurance_hooks');
        $this->renderJson(Db::getInstance()->executeS($query));
    }

    public function ajaxProcessDeleteHook()
    {
        $hookId = (int) Tools::getValue('hookId');

        $query = new DbQuery();
        $query->select('name');
        $query->from('quality_assurance_hooks');
        $query->where('id = ' . $hookId);
        $row = Db::getInstance()->getRow($query);
        if (empty($row)) {
            $this->renderJson(['error' => 'Hook not found']);
        }

        $this->module->unregisterHook($row['name']);
        $this->renderJson(Db::getInstance()->delete('quality_assurance_hooks', 'id = ' . $hookId));
    }

    public function ajaxProcessUpdateHook()
    {
        $hookId = (int) Tools::getValue('hookId');

        $query = new DbQuery();
        $query->select('name');
        $query->from('quality_assurance_hooks');
        $query->where('id = ' . $hookId);
        $row = Db::getInstance()->getRow($query);
        if (empty($row)) {
            $this->renderJson(['error' => 'Hook not found']);
        }

        Db::getInstance()->update(
            'quality_assurance_hooks',
            [
                'content' => pSQL(Tools::getValue('content'), true),
            ],
            'id = ' . $hookId
        );
        $this->renderJson([]);
    }

    public function ajaxProcessToggleHookStatus()
    {
        $hookId = (int) Tools::getValue('hookId');

        $query = new DbQuery();
        $query->select('enabled');
        $query->from('quality_assurance_hooks');
        $query->where('id = ' . $hookId);
        $row = Db::getInstance()->getRow($query);
        if (empty($row)) {
            $this->renderJson(['error' => 'Hook not found']);
        }

        Db::getInstance()->update(
            'quality_assurance_hooks',
            [
                'enabled' => !$row['enabled'],
            ],
            'id = ' . $hookId
        );
        $this->renderJson([]);
    }

    private function generateAjaxUrl($action)
    {
        return $this->context->link->getAdminLink(
            'AdminQualityAssurance',
            true,
            [],
            [
                'ajax' => 1,
                'action' => $action,
            ]
        );
    }

    private function renderJson($data)
    {
        header('Content-Type: application/json');
        $this->ajaxDie(json_encode($data));
    }
}
