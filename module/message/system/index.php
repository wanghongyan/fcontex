<?php
/***
 * 名称：消息模块
 * Alan 2012.07
 * www.fcontex.com
*/
include '../../../kernel/startup.php';

switch ($A->strGet('mode'))
{
	case 'gbook.select':
		if (!$U->hasRights('gbook.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('gbook.select');
		break;
	case 'gbook.update':
		if (!$U->hasRights('gbook.update'))
		{
			$A->hasRightsErr();
		}
		$T->show('gbook.update');
		break;
	case 'comment.select':
		if (!$U->hasRights('comment.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('comment.select');
		break;
	case 'comment.update':
		if (!$U->hasRights('comment.update'))
		{
			$A->hasRightsErr();
		}
		$T->show('comment.update');
		break;
	default: $A->hasRightsErr('无效请求。'); break;
}
?>