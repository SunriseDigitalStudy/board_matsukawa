<?php

class ControlController extends Sdx_Controller_Action_Http{
    public function tagAction(){
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');
        $this->_helper->scaffold->run();
    }
    
    public function threadAction(){
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');
        $this->_helper->scaffold->run();
    }
    
    public function genreAction(){
        $this->_helper->scaffold->setViewRendererPath('default/control/scaffold.tpl');
        $this->_helper->scaffold->run();
    }
    
}
