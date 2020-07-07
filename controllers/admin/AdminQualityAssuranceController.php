<?php

class AdminQualityAssuranceController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->controller_quick_name = 'module';

        if ($this->ajax) {
            $this->display_header = false;
            $this->display_header_javascript = false;
            $this->display_footer = false;
        }
    }
    /**
     * Initialize the content by adding Boostrap and loading the TPL
     *
     * @param none
     * @return none
     */
    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign([
            'pathApp' => $this->module->assetsPath . 'back.js?v=' . mt_rand(),
        ]);
        Media::addJsDef([
            'qualityAssuranceHooks' => Hook::getHooks(),
        ]);

        $this->createTemplate('views/admin/index.tpl');
        $content = $this->context->smarty->fetch($this->getTemplatePath() . 'index.tpl');
        $this->context->smarty->assign(
            [
                'content' => $this->content . $content,
            ]
        );
    }
}
