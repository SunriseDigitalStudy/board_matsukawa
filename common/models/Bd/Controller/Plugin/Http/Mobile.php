<?php
/**
 *
 *
 * @author  Masamoto Miyata <miyata@able.ocn.ne.jp>
 * @create  2009/08/25
 * @copyright 2007 Sunrise Digital Corporation.
 * @version  v 1.0 2009/08/25 15:06:11 Miyata
 **/
class Bd_Controller_Plugin_Http_Mobile extends Bd_Controller_Plugin_Http
{
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
		$this->setConvertKana('ka');
		$this->setCharset('UTF-8', 'sjis-win');
		
		parent::routeStartup($request);
	}
}