<?php
/**
 *
 *
 * @author  Masamoto Miyata <miyata@able.ocn.ne.jp>
 * @create  2010/03/21
 * @copyright 2007 Sunrise Digital Corporation.
 * @version  v 1.0 2010/03/21 18:50:08 Miyata
 **/
require_once 'Sdx/Controller/Action/Http.php';

class ErrorController extends Sdx_Controller_Action_Http
{
    public function indexAction()
    {
    	$helper = new Sdx_Controller_Action_Helper_ErrorRenderer();
    	$this->addHelper($helper);
    	$this->_helper->errorRenderer();
    }
}