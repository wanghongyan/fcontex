<?php
/***
 * 名称：内容模块
 * Joe 2012.07
 * www.fcontex.com
*/

if (!defined('SYSTEM_INCLUDE')) die('Access Denied.');

$T->bind('M', $A->loadLibrary('message'));

switch ($this->event)
{
	case 'comment':
		$R->cacheopen = TRUE;
		
		$cid = intval($R->param(1,1));
		$page = intval($R->param(1,2));
		
		if (!($quiet = $A->site['site_commentlock']))
		{
			$rst = $D->fetch($D->query('select ct_quiet from T[content] where ct_id = '.$cid));
			if ($rst && $rst['ct_quiet']) $quiet = TRUE;
		}
		$T->bind('quiet', $quiet);
		
		$pager = FCApplication::sharedPageTurnner();
		$pager->page = $page;
		$pager->style = 'Simple';
		$pager->linker = "#{p}\" onclick=\"comment.fetch('".$R->getUrl('message/comment-'.$cid.'-{p}')."');return false;";
		$query = $pager->parse('cm_id', '*', 'T[comment]', 'cm_check = 1 and cm_topid = 0 and cm_cid = '.$cid, 'cm_id desc');
		$T->bind('comments', $D->fetchAll($query));
		$T->bind('turnner', $pager->turnner);
		$T->display('comment');
		break;
		
	case 'gbook':
		$R->cacheopen = TRUE;
		
		$page = intval($R->param(1,1));
		
		$T->bind('quiet', $A->site['site_gbooklock']);
		
		$pager = FCApplication::sharedPageTurnner();
		$pager->page = $page;
		$pager->style = 'Simple';
		$pager->linker = $R->getUrl('message/gbook-{p}');
		$query = $pager->parse('gb_id', '*', 'T[gbook]', 'gb_check = 1 and gb_topid = 0 order by gb_id desc');
		$T->bind('gbook', $D->fetchAll($query));
		$T->bind('turnner', $pager->turnner);
		$position[0] = array
		(
			'text' => '留言',
			'link' => $R->getUrl('message/gbook')
		);
		$T->bind('position', $position);
		$T->display('gbook');
		break;
		
	default:
		$R->cacheopen = TRUE;
		$T->display('404');
		break;
}
?>