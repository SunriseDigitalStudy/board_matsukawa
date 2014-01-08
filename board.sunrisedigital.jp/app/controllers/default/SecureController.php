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
        
        //ログイン後にトップページに飛ばないようにする
        //ログイン後に遷移元(ログインページの前のページ)に戻る
        //url直入力でログインページに来た場合は、前のページのurlのデータはないのでsessionへ値を入れる処理をしないようにする
        if(isset($_SERVER['HTTP_REFERER'])){
          if($_SERVER['HTTP_REFERER'] !== 'http://board.sunrisedigital.jp/secure/login'){                                     
            Sdx_Debug::dump($_SERVER['HTTP_REFERER'],'$_SERVER2');
            $_SESSION['referer_url'] = $_SERVER['HTTP_REFERER'];
          }  
        }
//        $this->_helper->secure->login($_SESSION['referer_url']);
        
        //url直入力でログインページに来た場合は、ログイン後、トップページに飛ぶようにする
        if(isset($_SESSION['referer_url'])){
          $this->_helper->secure->login($_SESSION['referer_url']);
        }else{
          $this->_helper->secure->login('/');
        }
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