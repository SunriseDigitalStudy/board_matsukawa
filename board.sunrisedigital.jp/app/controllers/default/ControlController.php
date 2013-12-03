<?php

class ControlController extends Sdx_Controller_Action_Http {

    public function __call($name, $arguments) {
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');
        $this->_helper->scaffold->run();
    }

    public function threadAction() {
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');

        $this->_helper->scaffold->setHook(
                Sdx_Controller_Action_Helper_Scaffold::HOOK_BIND_FORM, array($this, 'hookBindForm'
        ));

        $this->_helper->scaffold->setHook(
                Sdx_Controller_Action_Helper_Scaffold::HOOK_BIND_PARAMS_TO_FORM, array($this, 'hookBindParamsToForm'
        ));

        $this->_helper->scaffold->setHook(
                Sdx_Controller_Action_Helper_Scaffold::HOOK_BEFORE_RECORD_SAVE, array($this, 'hookBeforeRecordSave'
        ));

        $this->_helper->scaffold->setHook(
                Sdx_Controller_Action_Helper_Scaffold::HOOK_AFTER_RECORD_SAVE, array($this, 'hookAfterRecordSave'
        ));

        $this->_helper->scaffold->run();
    }

    public function hookBindForm($params) {
        Sdx_Debug::dump($params, 'hookBindForm');
    }

    public function hookBindParamsToForm($params) {
        Sdx_Debug::dump($params, 'hookBindParamsToForm');
    }

    public function hookBeforeRecordSave($params) {

        Sdx_Debug::dump($params, 'hookBeforeRecordSave');
    }

    public function hookAfterRecordSave($params) {
        Sdx_Debug::dump($params, 'hookAfterRecordSave');
    }

    public function genreListAction() {
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');
        $this->_helper->scaffold->runList('/control/genre-edit', 'scaffold/default/control/genre');
    }

    public function genreEditAction() {
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');
        $this->_helper->scaffold->runEdit('/control/genre-list', 'scaffold/default/control/genre');
    }

}
