<?php

class AdminQualityAssuranceController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->controller_quick_name = 'module';

        if ($this->ajax) {
            $this->display_header_javascript = false;
            $this->display_footer = false;
            $this->display_header = false;
        }
    }

    /**
     * Initialize the content by adding Boostrap and loading the TPL
     *
     * @param none
     *
     * @return none
     */
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
                    'content' => pSQL(Tools::getValue('content')),
                ],
                'id = ' . (int) $row['id']
            );
        } else {
            Db::getInstance()->insert(
                'quality_assurance_hooks',
                [
                    'name' => pSQL($hookName),
                    'content' => pSQL(Tools::getValue('content')),
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
                'content' => pSQL(Tools::getValue('content')),
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
