<?php
require_once 'Sdx/Controller/Action/Cli.php';

class Cli_ErrorController extends Sdx_Controller_Action_Cli
{
    public function indexAction()
    {
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type)
        {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            	
            	$module = $errors->request->getModuleName();
            	$controller = $errors->request->getControllerName();
            	$action = $errors->request->getActionName();
            	
            	echo $errors->exception->getMessage().PHP_EOL
            		.'module     : '.$module.PHP_EOL
            		.'controller : '.$controller.PHP_EOL
            		.'action     : '.$action.PHP_EOL;
                break;
            default:
            	throw $errors->exception;
            	break;
        }
    }
}