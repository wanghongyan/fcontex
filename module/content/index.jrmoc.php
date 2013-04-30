<?php
/***
 * 名称：内容模块
 * Joe 2012.07
 * www.fcontex.com
*/

if (!defined('SYSTEM_INCLUDE')) die('Access Denied.');

$productcids = '1,3,4,5,6,7,8';
$contentcids = '2,9,10,11';

$T->bind('links', $C->getContents('*', 'ct_type=1 and ct_check=1'));

switch ($R->event)
{
	case 'start':
		$R->cacheopen = TRUE;
		
		$sql  = 'select ct_id, ct_title, ct_title2, ct_cover from T[content] where ct_type=0 ';
		$sql .= 'and ct_check=1 and ct_fixed=1 and ct_cid in ('.$productcids.') order by ct_inserttime desc limit 4';
		$products = $D->query($sql);
		$T->bind('products', $D->fetchAll($products));
		$sql  = 'select ct_id, ct_title from T[content] where ct_type=0 ';
		$sql .= 'and ct_check=1 and ct_fixed=1 and ct_cid in ('.$contentcids.') order by ct_inserttime desc limit 4';
		$contents = $D->query($sql);
		$T->bind('contents', $D->fetchAll($contents));
		$T->display('start');
		break;
	
	case 'product':
		$R->cacheopen = TRUE;
		
		$mode = $R->param(1,1);
		$id = intval($R->param(1,2));
		$page = intval($R->param(1,3));
		
		$T->bind('banner', 'page_product.jpg');
		$T->bind('categories', $C->getCategories(1));
		if ($mode == 'read')
		{
			$page = $C->getContent('*', 'ct_id = '.$id);
			if (empty($page))
			{
				$R->print404();
			}
			$A->site['site_title'] = $page['ct_title'].' _ '.$A->site['site_title'];
			$A->site['site_description'] = $page['ct_title'].'，'.$page['ct_title'].'价格。'.$A->trim($page['ct_summary'], TRUE);
			$A->site['site_keywords'] = $page['ct_title'].','.$page['ct_title'].'价格,'.$A->site['site_keywords'];
			$T->bind('page', $page);
			$T->display('product.read');
		}
		else
		{
			$pager = FCApplication::sharedPageTurnner();
			$pager->style = 'Simple';
			$pager->page = $page;
			$pager->size = $A->site['site_pagesize'];
			$pager->linker = $R->getUrl('content/product-list-'.$id.'-{p}');
			$field = 'ct_id, ct_username, ct_title, ct_tags, ct_summary, ct_content, ct_inserttime, ct_cover, ct_uid, ct_hits';
			$where = 'ct_type=0 and ct_check=1';
			$order = 'ct_inserttime desc';
			if ($id == 0)
			{
				$where .= ' and ct_cid in ('.$productcids.')';
				$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
			}
			else
			{
				$where .= ' and ct_cid = '.$id;
				$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
				$category = $C->getCategory($id);
			}
			
			if (empty($category))
			{
				$category['cg_id'] = 0;
				$category['cg_title'] = '产品中心';
			}
			
			$A->site['site_title'] = $category['cg_title'].' _ '.$A->site['site_title'];
			$A->site['site_description'] = '查看'.$category['cg_title'].'相关产品。'.$A->site['site_description'];
			$A->site['site_keywords'] = $category['cg_title'].','.$A->site['site_keywords'];
			$T->bind('turnner', $pager->turnner);
			$T->bind('items', $D->fetchAll($items));
			$T->bind('category', $category);
			$T->display('product.list');
		}
		break;
	
	case 'news':
		$R->cacheopen = TRUE;
		
		$mode = $R->param(1,1);
		$id = intval($R->param(1,2));
		$page = intval($R->param(1,3));
		
		$T->bind('banner', 'page_news.jpg');
		$T->bind('categories', $C->getCategories(2));
		if ($mode == 'read')
		{
			$page = $C->getContent('*', 'ct_id = '.$id);
			if (empty($page))
			{
				$R->print404();
			}
			$A->site['site_title'] = $page['ct_title'].' _ '.$A->site['site_title'];
			$A->site['site_description'] = $page['ct_title'].'：'.$A->trim($page['ct_summary'], TRUE);
			$A->site['site_keywords'] = $page['ct_title'].','.$A->site['site_keywords'];
			$T->bind('page', $page);
			$T->display('news.read');
		}
		else
		{
			$pager = FCApplication::sharedPageTurnner();
			$pager->style = 'Simple';
			$pager->page = $page;
			$pager->size = $A->site['site_pagesize'];
			$pager->linker = $R->getUrl('content/news-list-'.$id.'-{p}');
			$field = 'ct_id, ct_username, ct_title, ct_tags, ct_summary, ct_content, ct_inserttime, ct_cover, ct_uid, ct_hits';
			$where = 'ct_type=0 and ct_check=1';
			$order = 'ct_fixed desc, ct_inserttime desc';
			if ($id == 0)
			{
				$where .= ' and ct_cid in ('.$contentcids.')';
				$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
			}
			else
			{
				$where .= ' and ct_cid = '.$id;
				$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
				$category = $C->getCategory($id);
			}
			
			if (empty($category))
			{
				$category['cg_id'] = 0;
				$category['cg_title'] = '资讯中心';
			}
			
			$A->site['site_title'] = $category['cg_title'].' _ '.$A->site['site_title'];
			$A->site['site_description'] = '查看“'.$category['cg_title'].'”相关信息。'.$A->site['site_description'];
			$A->site['site_keywords'] = $category['cg_title'].','.$A->site['site_keywords'];
			$T->bind('turnner', $pager->turnner);
			$T->bind('items', $D->fetchAll($items));
			$T->bind('category', $category);
			$T->display('news.list');
		}
		break;
	
	case 'search':
		$mode = $R->param(1,1);
		$keyw = urldecode($R->param(2));
		$page = intval($R->param(3));
		$T->bind('keyw', $keyw);
		
		$pager = FCApplication::sharedPageTurnner();
		$pager->style = 'Simple';
		$pager->page = $page;
		$pager->size = $A->site['site_pagesize'];
		$field = 'ct_id, ct_username, ct_title, ct_tags, ct_summary, ct_content, ct_inserttime, ct_cover, ct_uid, ct_hits';
		$where = 'ct_type=0 and ct_check=1 and (ct_title like "%'.$keyw.'%" or ct_tags like "%'.$keyw.'%")';
		$order = 'ct_inserttime desc';
		
		if ($mode == 'product')
		{
			$pager->linker = $R->getUrl('content/search-product/'.$keyw.'/{p}');
			$A->site['site_title'] = $keyw.' _ 产品搜索 _  '.$A->site['site_title'];
			$A->site['site_description'] = '专业生产'.$keyw.'，提供'.$keyw.'价格信息。'.$A->site['site_description'];
			$A->site['site_keywords'] = $keyw.','.$keyw.'价格,'.$A->site['site_keywords'];
			$where .= ' and ct_cid in ('.$productcids.')';
			$T->bind('banner', 'page_product.jpg');
			$T->bind('categories', $C->getCategories(1));
		}
		else
		{
			$pager->linker = $R->getUrl('content/search-news/'.$keyw.'/{p}');
			$A->site['site_title'] = $keyw.' _ 资讯搜索 _  '.$A->site['site_title'];
			$A->site['site_description'] = '查找“'.$keyw.'”的相关信息。'.$A->site['site_description'];
			$A->site['site_keywords'] = $keyw.','.$A->site['site_keywords'];
			$where .= ' and ct_cid in ('.$contentcids.')';
			$T->bind('banner', 'page_news.jpg');
			$T->bind('categories', $C->getCategories(2));
		}
		
		$items = $pager->parse('ct_id', $field, 'T[content]', $where, $order);
		$T->bind('turnner', $pager->turnner);
		$T->bind('items', $D->fetchAll($items));
		$T->display($mode.'.search');
		break;
	
	case 'page':
		$R->cacheopen = TRUE;
		
		$id = intval($R->param(1,1));
		$page = $C->getContent('*', 'ct_id = '.$id);
		if (empty($page))
		{
			$R->print404();
		}
		$A->site['site_title'] = $page['ct_title'].' _ '.$A->site['site_title'];
		$A->site['site_description'] = $A->strLeft($A->trim($page['ct_content'], TRUE), 220, '...');
		$T->bind('page', $page);
		$T->bind('banner', 'page_'.$id.'.jpg');
		$T->display('page');
		break;
	
	case 'rss':
		$R->cacheopen = TRUE;
		
		$contents = $C->getContents('*', 'ct_type=0 and ct_check=1', 'ct_fixed desc, ct_inserttime desc', 30);
		$item = array();
		$i = 0;
		foreach ($contents as $content)
		{
			$item[$i]['link'] = $R->getUrl('content/product-read-'.$content['ct_id']);
			$item[$i]['cover'] = $A->getThumb($content['ct_cover']);
			$item[$i]['title'] = $content['ct_title'];
			$item[$i]['author'] = $content['ct_username'];
			$item[$i]['category'] = $content['ct_category'];
			$item[$i]['pubDate'] = date('Y-m-d h:i:s', $content['ct_inserttime']); //#WW,d #MM y h:i:s +0800
			$item[$i]['description'] = $content['ct_summary'];
			
			$i++;
		}
		$channel['link'] = $R->getUrl('content/product-list');
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