<?php
/***
 * 名称：内容模块
 * Joe 2012.07
 * www.fcontex.com
*/
include '../../../kernel/startup.php';

switch ($A->strGet('mode'))
{
	case 'category.select':
		if (!$U->hasRights('content.category.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('category.select');
		break;
	case 'category.insert':
		if (!$U->hasRights('content.category.insert'))
		{
			$A->hasRightsErr();
		}
		$T->show('category.insert');
		break;
	case 'category.update':
		if (!$U->hasRights('content.category.update'))
		{
			$A->hasRightsErr();
		}
		$T->show('category.update');
		break;
	case 'content.select':
		if (!$U->hasRights('content.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('content.select');
		break;
	case 'content.insert':
        if (!$U->hasRights('content.insert'))
		{
			$A->hasRightsErr();
		}
		$T->show('content.insert');
		break;
	case 'content.update':
		if (!$U->hasRights('content.update'))
		{
			$A->hasRightsErr();
		}
		$T->show('content.update');
		break;
	case 'page.select':
		if (!$U->hasRights('content.page.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('page.select');
		break;
	case 'page.insert':
		if (!$U->hasRights('content.page.insert'))
		{
			$A->hasRightsErr();
		}
		$T->show('page.insert');
		break;
	case 'page.update':
		if (!$U->hasRights('content.page.update'))
		{
			$A->hasRightsErr();
		}
		$T->show('page.update');
		break;
	case 'tags.select':
		if (!$U->hasRights('content.tags.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('tags.select');
		break;
	case 'navigate.select':
		if (!$U->hasRights('content.navigate.select'))
		{
			$A->hasRightsErr();
		}
		$T->show('navigate.select');
		break;
	default: $A->hasRightsErr('无效请求。'); break;
}
?>