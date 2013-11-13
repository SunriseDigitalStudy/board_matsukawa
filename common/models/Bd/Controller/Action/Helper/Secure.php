<?php
/**
 *
 *
 * @author  Masamoto Miyata <gomocoro@gmail.com>
 * @create  2011/12/22
 * @copyright 2011 Sincere co.
 **/
 
class Bd_Controller_Action_Helper_Secure extends Sdx_Controller_Action_Helper_Secure
{
	protected function _getAdapter($id, $password)
	{
		return new Bd_Auth_Adapter_Db($id, $password);
	}
}