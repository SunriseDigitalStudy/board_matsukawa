<?php
/**
 *
 *
 * @author  Masamoto Miyata <miyata@able.ocn.ne.jp>
 * @create  2009/07/21
 * @copyright 2007 Sunrise Digital Corporation.
 * @version  2012/08/15 12:32 Miyata
 **/
chdir(dirname(__FILE__).'/../');
require_once '../sdx/models/Sdx/Context.php';
$base = '.';

$context = Sdx_Context::create('../common', 'Bd_Environment_Http_Pc', array(
		'base'   => $base,
		'config' => $base.'/config',
		'cache'  => $base.'/cache'
));

$context->dispatch();