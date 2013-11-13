<?php
/**
 *
 *
 * @author  Masamoto Miyata <miyata@able.ocn.ne.jp>
 * @create  2011/12/21
 * @version  v 1.0 2011/12/21 18:50:08 Miyata
 **/

class SecureController extends Sdx_Controller_Action_Http
{
	public function loginAction()
	{
		$this->_initHelper();
		$this->_helper->secure->login("/");
	}
	
	public function logoutAction()
	{
		$this->_initHelper();
		$this->_helper->secure->logout();
	}
	
	public function denyAction()
	{
		
	}
	
	private function _initHelper()
	{
		$helper = new Bd_Controller_Action_Helper_Secure();
		$this->addHelper($helper);
		
		$helper
			->setIdElement(new Sdx_Form_Element_Text(array(
				'name'=>'login_id',
			)))
			->setPasswordElement(new Sdx_Form_Element_Password(array(
				'name'=>'password',
			)));
	}

}