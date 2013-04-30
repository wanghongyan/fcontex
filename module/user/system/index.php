<?php
/***
 * 名称：用户模块
 * Alan 2012.03
 * www.fcontex.com
*/
include '../../../kernel/startup.php';

switch ($A->strGet('mode'))
{
	case 'group.select':
		if (!$U->hasRights('user.group.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('group.select');
		break;
	case 'group.insert':
		if (!$U->hasRights('user.group.insert'))
		{
			$A->hasRightsErr();
		}
		$T->show('group.insert');
		break;
	case 'group.update':
		if (!$U->hasRights('user.group.update'))
		{
			$A->hasRightsErr();
		}
		$T->show('group.update');
		break;
	case 'user.select':
		if (!$U->hasRights('user.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('user.select');
		break;
	case 'user.insert':
		if (!$U->hasRights('user.insert'))
		{
			$A->hasRightsErr();
		}
		$T->show('user.insert');
		break;
	case 'user.update':
		$args = $A->strGet('args');
		$T->bind('args', $args);
		if (!$U->hasRights('user.update') )
		{
			$uid = isset($_SESSION['userInfo']) ? $_SESSION['userInfo']['us_id'] : 0;
			if ($uid != $args) $A->hasRightsErr();
		}
		$T->show('user.update');
		break;
	case 'user.login':
		$T->show('user.login');
		break;
	case 'user.retrievecode':
		$T->show('user.retrievecode');
		break;
	case 'user.resetcode':
		$code = $A->strGet('code');
		$code = explode('|', $A->strDeCode($code));
		if (count($code) != 3)
		{
			$A->hasRightsErr('参数错误。');
		}
		if (trim($code[0]) == '' || trim($code[1]) == '' || trim($code[2]) == '')
		{
			exit('ERR|参数错误。');
		}
		$query = $D->query('select count(*) as num from T[user] where us_resetcodetime = '.$code[2].' and us_username = "'.$code[0].'" and us_email = "'.$code[1].'"');
		$rst = $D->fetch($query);
		if ($rst['num'] < 1)
		{
			$A->hasRightsErr('错误：没有找到用户，可能链接已经被使用。');
		}
		if (((time() - $code[2]) / 60) > 15)
		{
			$A->hasRightsErr('错误：链接已经过期，请重新找回密码以获取新的地址。');
		}
		$T->show('user.resetcode');
		break;
	default: $A->hasRightsErr('无效请求。');break;
}
?>