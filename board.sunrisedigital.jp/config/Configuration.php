<?php
/**
 *
 *
 * @author  Masamoto Miyata <miyata@able.ocn.ne.jp>
 * @create  2009/09/14
 * @copyright 2007 Sunrise Digital Corporation.
 * @version  v 1.0 2009/09/14 18:50:08 Miyata
 **/
require_once '../common/models/Bd/Configuration.php';

class Configuration extends Bd_Configuration
{	
	protected function _initHttp(Sdx_Context $context)
	{
		parent::_initHttp($context);
		
		//If you want to register other auto load namespase, Remove this comment out.
		//$context->registerAutoloadNamespace('Other');
		
		//If you want to enable access control, Remove this comment out.
		//$context->registerControllerPlugin(new Sdx_Controller_Plugin_AccessControl());
	}
}