<?php
/**
 *
 *
 * @author  Masamoto Miyata <miyata@sunrisedigital.jp>
 * @create  2010/10/21
 * @copyright 2010 Sunrise Digital Corporation.
 * @version 2010/10/21 12:17:08
 **/
class Bd_Environment_Http_Pc extends Bd_Environment_Http
{
	public function getPlugins()
	{
		return array(new Bd_Controller_Plugin_Http_Pc());
	}
}