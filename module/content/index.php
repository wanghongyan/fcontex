<?php
/***
 * 名称：内容模块
 * Joe 2012.07
 * www.fcontex.com
*/

if (!defined('SYSTEM_INCLUDE')) die('Access Denied.');

switch ($R->event)
{
	case 'start':
	case 'list':
		$R->cacheopen = TRUE;
		
		$cid = intval($R->param(1,1));
		$page = intval($R->param(1,2));
		
		$pager = FCApplication::sharedPageTurnner();
		$pager->style = 'Simple';
		$pager->page = $page;
		$pager->size = $A->site['site_pagesize'];
		$pager->linker = $R->getUrl('content/list-'.$cid.'-{p}');
		$field = 'ct_id, ct_username, ct_title, ct_tags, ct_summary, ct_content, ct_inserttime, ct_cover, ct_uid, ct_hits, ct_talks';
		$where = 'ct_type=0 and ct_check=1';
		$order = 'ct_fixed desc, ct_inserttime desc';
		if ($cid == 0)
		{
			$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
			$A->site['site_title'] = $A->site['site_title'];
			$A->site['site_description'] = $A->site['site_description'];
			$A->site['site_keywords'] = $A->site['site_keywords'];
			
			$T->bind('category', array('id'=>$cid, 'title'=>''));
		}
		else
		{
			$where .= ' and ct_cid = '.$cid;
			$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
			$category = $C->getCategory($cid);
			$position[0] = array
			(
				'text' => $category['cg_title'],
				'link' => $R->getUrl('content/list-'.$cid.'-'.$page)
			);
			$T->bind('position', $position);
			$A->site['site_title'] = $category['cg_title'].' _ '.$A->site['site_title'];
			$A->site['site_description'] = '查看'.$category['cg_title'].'相关日志。'.$A->site['site_description'];
			$A->site['site_keywords'] = $category['cg_title'].','.$A->site['site_keywords'];
			
			$T->bind('category', array('id'=>$cid, 'title'=>$category['cg_title']));
		}
		$T->bind('turnner', $pager->turnner);
		$T->bind('items', $D->fetchAll($items));
		$T->display('list');
		break;
		
	case 'read':
	case 'page':
		$R->cacheopen = TRUE;
		
		$id = intval($R->param(1,1));
		$page = $C->getContent('*', 'ct_id = '.$id);
		if (empty($page))
		{
			$R->print404();
		}
		if ($page['ct_type'] == 0)
		{
			$position[0] = array
			(
				'text' => $page['ct_category'],
				'link' => $R->getUrl('content/list-'.$page['ct_cid'].'-1')
			);
			$position[1] = array
			(
				'text' => '正文',
				'link' => ''
			);
		}
		else
		{
			$position[0] = array
			(
				'text' => $page['ct_title'],
				'link' => $R->getUrl('content/page-'.$id)
			);
		}
		$T->bind('position', $position);
		$A->site['site_title'] = $page['ct_title'].' _ '.$A->site['site_title'];
		$A->site['site_description'] = $page['ct_title'].'，'.$A->trim($page['ct_summary'], TRUE);
		$A->site['site_keywords'] = $page['ct_title'].','.$A->site['site_keywords'];
		$T->bind('page', $page);
		$T->display('read');
		break;
	
	case 'search':
		$keyw = urldecode($R->param(2));
		$page = intval($R->param(3));
		$T->bind('keyw', $keyw);
		
		$pager = FCApplication::sharedPageTurnner();
		$pager->style = 'Simple';
		$pager->page = $page;
		$pager->size = $A->site['site_pagesize'];
		$pager->linker = $R->getUrl('content/search/'.$keyw.'/{p}');
		$field = 'ct_id, ct_username, ct_title, ct_tags, ct_summary, ct_content, ct_inserttime, ct_cover, ct_uid, ct_hits, ct_talks';
		$where = 'ct_type=0 and ct_check=1 and (ct_title like "%'.$keyw.'%" or ct_tags like "%'.$keyw.'%")';
		$order = 'ct_inserttime desc';
		$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
		$T->bind('items', $D->fetchAll($items));
		$T->bind('turnner', $pager->turnner);
		$position[0] = array
		(
			'text' => '“'.$keyw.'”',
			'link' => ''
		);
		$T->bind('position', $position);
		$A->site['site_title'] = $keyw.' _ 日志搜索 _  '.$A->site['site_title'];
		$A->site['site_description'] = '查看'.$keyw.'，相关日志。'.$A->site['site_description'];
		$A->site['site_keywords'] = $keyw.','.$A->site['site_keywords'];
		$T->display('list');
		break;
	
	case 'tag':
		$tag = urldecode($R->param(2));
		$page = intval($R->param(3));
		$T->bind('tag', $tag);
		
		$pager = FCApplication::sharedPageTurnner();
		$pager->style = 'Simple';
		$pager->page = $page;
		$pager->size = $A->site['site_pagesize'];
		$pager->linker = $R->getUrl('content/tag/'.$tag.'/{p}');
		$field = 'ct_id, ct_username, ct_title, ct_tags, ct_summary, ct_content, ct_inserttime, ct_cover, ct_uid, ct_hits, ct_talks';
		$where = 'ct_type=0 and ct_check=1 and (ct_tags like "%'.$tag.'%")';
		$order = 'ct_inserttime desc';
		$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
		$T->bind('items', $D->fetchAll($items));
		$T->bind('turnner', $pager->turnner);
		$position[0] = array
		(
			'text' => '“'.$tag.'”',
			'link' => ''
		);
		$T->bind('position', $position);
		$A->site['site_title'] = $tag.' _ 标签检索 _  '.$A->site['site_title'];
		$A->site['site_description'] = '查看'.$tag.'，相关日志。'.$A->site['site_description'];
		$A->site['site_keywords'] = $tag.','.$A->site['site_keywords'];
		$T->display('list');
		break;
	
	case 'rss':
		$R->cacheopen = TRUE;
		
		$contents = $C->getContents('*', 'ct_type=0 and ct_check=1', 'ct_fixed desc, ct_inserttime desc', 30);
		$item = array();
		$i = 0;
		foreach ($contents as $content)
		{
			$item[$i]['link'] = $R->getUrl('content/read-'.$content['ct_id']);
			$item[$i]['cover'] = $A->getThumb($content['ct_cover']);
			$item[$i]['title'] = $content['ct_title'];
			$item[$i]['author'] = $content['ct_username'];
			$item[$i]['category'] = $content['ct_category'];
			$item[$i]['pubDate'] = date('Y-m-d h:i:s', $content['ct_inserttime']);
			$item[$i]['description'] = $content['ct_summary'];
			
			$i++;
		}
		$channel['link'] = $R->getUrl('/');
		$channel['title'] = $A->site['site_title'];
		$channel['description'] = $A->site['site_description'];
		$channel['generator'] = SYSTEM_NAME.' '.SYSTEM_VERSION;
		$channel['ttl'] = ceil($A->site['site_cachetime']/60);
		$channel['item'] = $item;
		
		$T->bind('channel', $channel);
		header('Content-Type: text/xml;');
		$T->display('rss');
		break;
	
	case '404': default:
		$R->cacheopen = TRUE;
		$T->display('404');
		break;
}
?>