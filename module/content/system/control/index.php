<?php
/***
 * 名称：内容模块动作处理程序
 * Alan 2012.07
 * www.fcontex.com
*/

include '../../../../kernel/startup.php';

switch ($mode = $A->strGet('mode'))
{
	case 'category.insert':
		if (!$U->hasRights('content.category.insert'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$cg_title = trim($A->strPost('cg_title'));
		if ( $cg_title == '' )
		{
			echo 'ERR|请输入栏目名称。|cg_title';
		}
		else
		{
			$insertarr = array
			(
				'cg_pid'	=> intval($A->strPost('cg_pid')),
				'cg_title'	=> $cg_title,
				'cg_type'	=> intval($A->strPost('cg_type')),
				'cg_url'	=> $A->strPost('cg_url'),
				'cg_target'	=> $A->strPost('cg_target'),
				'cg_desc'	=> $A->strPost('cg_desc'),
				'cg_time'	=> time()
			);
			$D->insert('T[category]', $insertarr);
			
			$A->logInsert('添加了栏目 #'.$D->insertid('T[category]'));
			echo 'YES';
			
		}
		break;
		
	case 'category.update':
		if (!$U->hasRights('content.category.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		$cg_title = trim($A->strPost('cg_title'));
		if (!is_numeric($args))
		{
			echo 'ERR|错误的参数[ '.$args.' ]。';
		}
		elseif ( $cg_title == '' )
		{
			echo 'ERR|请输入栏目名称。|cg_title';
		}
		else
		{
			$updatearr = array
			(
				'cg_pid'	=> intval($A->strPost('cg_pid')),
				'cg_title'	=> $cg_title,
				'cg_type'	=> intval($A->strPost('cg_type')),
				'cg_url'	=> $A->strPost('cg_url'),
				'cg_target'	=> $A->strPost('cg_target'),
				'cg_desc'	=> $A->strPost('cg_desc')
			);
			$D->update('T[category]', $updatearr, array('cg_id' => $args));
			
			$A->logInsert('修改了栏目 #'.$args);
			echo 'YES';
			
		}
		break;
		
	case 'category.delete':
		if (!$U->hasRights('content.category.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		if (!preg_match('/^[0-9]+?(,[0-9]+?)*$/', $args))
		{
			echo 'ERR|未选中任何项。';
		}
		else
		{
			$D->delete('T[category]', 'cg_id in ('.$args.')');
			
			$A->logInsert('删除了栏目 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'category.order':
		if (!$U->hasRights('content.category.order'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		if (!preg_match('/^[0-9]+?(,[0-9]+?)*$/', $args))
		{
			echo 'ERR|未选中任何项。';
		}
		else
		{
			
			foreach (explode(',', $args) as $key => $value)
			{
				$D->update('T[category]', array('cg_order' => $key), 'cg_id = '.$value);
			}
			$A->logInsert('更新了栏目排序');
			echo 'YES';
		}
		break;
		
	case 'content.insert':
		if (!$U->hasRights('content.insert'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$ct_type 			= intval($A->strPost('ct_type'));
		$ct_title			= trim($A->strPost('ct_title'));
		$ct_title2			= trim($A->strPost('ct_title2'));
		$ct_cid				= intval($A->strPost('ct_cid'));
		$ct_category		= trim($A->strPost('ct_category'));
		$ct_summary			= trim($A->strPost('ct_summary'));
		$ct_content			= trim($A->strPost('ct_content'));
		$ct_cover			= trim($A->strPost('ct_cover'));
		$ct_check			= $A->strPost('ct_check');
		$ct_fixed			= $A->strPost('ct_fixed');
		$ct_quiet			= $A->strPost('ct_quiet');
		$ct_tags			= str_replace('，', ',', trim($A->strPost('ct_tags')));
		$ct_inserttime		= intval(strtotime($A->strPost('ct_inserttime')));
		if ($ct_title == '')
		{
			echo 'ERR|请输入标题。|ct_title';
		}
		/*elseif ( !is_numeric($ct_cid) || $ct_cid == '0' )
		{
			echo 'ERR|请选择栏目。|cg_category';
		}*/
		elseif ($ct_content == '')
		{
			echo 'ERR|请输入内容。|ct_content';
		}
		else
		{
			$insertarr = array
			(
				'ct_type'			=> ($ct_type ? 1 : 0),
				'ct_title'			=> $ct_title,
				'ct_title2'			=> $ct_title2,
				'ct_cid'			=> $ct_cid,
				'ct_category'		=> $ct_category,
				'ct_tags'			=> $ct_tags,
				'ct_summary'		=> $ct_summary == '' ? $A->strLeft(strip_tags($ct_content), 200) : $ct_summary,
				'ct_content'		=> $ct_content,
				'ct_cover'			=> $ct_cover,
				'ct_inserttime'		=> $ct_inserttime,
				'ct_updatetime'		=> time(),
				'ct_uid'			=> $_SESSION['userInfo']['us_id'],
				'ct_username'		=> $_SESSION['userInfo']['us_username'],
				'ct_quiet'			=> ($ct_quiet ? 0 : 1),
				'ct_fixed'			=> ($ct_fixed ? 1 : 0),
				'ct_check'			=> ($ct_check ? 0 : 1)
			);
			if ($A->strPost('ct_seo'))
			{
				$insertarr['ct_seo'] 			= 1;
				$insertarr['ct_pagetitle']		= trim($A->strPost('ct_pagetitle'));
				$insertarr['ct_keywords']		= trim($A->strPost('ct_keywords'));
				$insertarr['ct_description']	= trim($A->strPost('ct_description'));
			}
			$D->insert('T[content]', $insertarr);
			$insertid = $D->insertid('T[content]');
			
			//处理标签
			if ($ct_tags != '')
			{
				$tags = explode(',', $ct_tags);
				foreach ($tags as $tag)
				{
					$tag = trim($tag);
					$query = $D->query('select tg_id from T[tags] where tg_title = "'.$tag.'"');
					if (!$D->fetch($query))
					{
						$D->insert('T[tags]', array('tg_title' => $tag));
					}
				}
			}
			
			$A->logInsert('添加了信息 #'.$D->insertid('T[content]'));
			echo 'YES';
			
		}
		break;
		
	case 'content.update':
		if (!$U->hasRights('content.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$ct_title		= trim($A->strPost('ct_title'));
		$ct_title2		= trim($A->strPost('ct_title2'));
		$ct_cid			= intval($A->strPost('ct_cid'));
		$ct_category	= trim($A->strPost('ct_category'));
		$ct_summary		= trim($A->strPost('ct_summary'));
		$ct_content		= trim($A->strPost('ct_content'));
		$ct_cover		= trim($A->strPost('ct_cover'));
		$ct_check		= $A->strPost('ct_check');
		$ct_fixed		= $A->strPost('ct_fixed');
		$ct_quiet		= $A->strPost('ct_quiet');
		$ct_tags		= str_replace('，', ',', trim($A->strPost('ct_tags')));
		$ct_inserttime	= intval(strtotime($A->strPost('ct_inserttime')));
		
		$args = $A->strGet('args');
		if (!is_numeric($args))
		{
			echo 'ERR|错误的参数[ '.$args.' ]。';
		}
		elseif ($ct_title == '')
		{
			echo 'ERR|请输入标题。|ct_title';
		}
		/*elseif ( !is_numeric($ct_cid) || $ct_cid == '0' )
		{
			echo 'ERR|请选择栏目。|cg_category';
		}*/
		elseif ($ct_content == '')
		{
			echo 'ERR|请输入内容。|ct_content';
		}
		else
		{
			$updatearr = array
			(
				'ct_title' 			=> $ct_title,
				'ct_title2' 		=> $ct_title2,
				'ct_cid' 			=> $ct_cid,
				'ct_category' 		=> $ct_category,
				'ct_tags'			=> $ct_tags,
				'ct_summary'		=> $ct_summary == '' ? $A->strLeft(strip_tags($ct_content), 200) : $ct_summary,
				'ct_content' 		=> $ct_content,
				'ct_cover'			=> $ct_cover,
				'ct_inserttime'		=> $ct_inserttime,
				'ct_updatetime' 	=> time(),
				'ct_uid'			=> $_SESSION['userInfo']['us_id'],
				'ct_username'		=> $_SESSION['userInfo']['us_username'],
				'ct_quiet'			=> ($ct_quiet ? 0 : 1),
				'ct_fixed'			=> ($ct_fixed ? 1 : 0),
				'ct_check'			=> ($ct_check ? 0 : 1)
			);
			$updatearr['ct_seo'] 			= $A->strPost('ct_seo') ? 1 : 0;
			$updatearr['ct_pagetitle']		= trim($A->strPost('ct_pagetitle'));
			$updatearr['ct_keywords']		= trim($A->strPost('ct_keywords'));
			$updatearr['ct_description']	= trim($A->strPost('ct_description'));
			
			$D->update('T[content]', $updatearr, 'ct_id = '.$args);
			
			//处理标签
			if ($ct_tags != '')
			{
				$tags = explode(',', $ct_tags);
				foreach ($tags as $tag)
				{
					$tag = trim($tag);
					$query = $D->query('select tg_id from T[tags] where tg_title = "'.$tag.'"');
					if (!$D->fetch($query))
					{
						$D->insert('T[tags]', array('tg_title' => $tag));
					}
				}
			}
			
			$A->logInsert('编辑了信息 #'.$args);
			echo 'YES';
			
		}
		break;
		
	case 'content.delete':
		if (!$U->hasRights('content.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		if (!preg_match('/^[0-9]+?(,[0-9]+?)*$/', $args))
		{
			echo 'ERR|未选中任何项。';
		}
		else
		{
			$D->delete('T[content]', 'ct_id in ('.$args.')');
			
			$A->logInsert('删除了信息 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'content.check':
		$save = $A->strGet('save');
		$args = $A->strGet('args');
		$updatearr = array();
		if     ($save == 'content.check_1') $updatearr['ct_check'] = 1;
		elseif ($save == 'content.check_0') $updatearr['ct_check'] = 0;
		elseif ($save == 'content.fixed_1') $updatearr['ct_fixed'] = 1;
		elseif ($save == 'content.fixed_0') $updatearr['ct_fixed'] = 0;
		else
		{
			exit('ERR|请选择操作。');	
		}
		foreach (explode(',', $args) as $id)
		{
			$D->update('T[content]', $updatearr, array('ct_id' => intval($id)));	
		}
		break;
	
	case 'tag.delete':
		if (!$U->hasRights('content.tags.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		if (!preg_match('/^[0-9]+?(,[0-9]+?)*$/', $args))
		{
			echo 'ERR|未选中任何项。';
		}
		else
		{
			$D->delete('T[tags]', 'tg_id in ('.$args.')');
			
			$A->logInsert('删除了标签 #'.$args);
			echo 'YES';
		}
		break;
	
	case 'tag.update':
		if (!$U->hasRights('content.tags.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$ids = $A->strPost('tg_id');
		if (!is_array($ids))
		{
			echo 'ERR|没有标签被修改。';
		}
		else 
		{
			$title = $A->strPost('tg_title');
			$color = $A->strPost('tg_color');
			foreach ($ids as $key => $id)
			{
				$title[$key] = isset($title[$key]) ? $title[$key] : '';
				$color[$key] = isset($color[$key]) ? $color[$key] : '';
				$D->update('T[tags]', array('tg_title' => trim($title[$key]), 'tg_color' => trim($color[$key])), array('tg_id' => $id));
			}
			$A->logInsert('修改了标签 #'.implode(',', $ids));
			echo 'YES';
		}
		break;
		
	case 'navigate.add':
		if (!$U->hasRights('content.navigate.insert'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$title = trim($A->strPost('nv_title'));
		if ($title == '')
		{
			die('ERR|请填写标题。|nv_title');
		}
		$type = $A->strPost('nv_type');
		$target = $A->strPost('nv_target') ? 1 : 0;
		$check = $A->strPost('nv_check') ? 1 : 0;
		$insertarr = array('nv_title' => $title, 'nv_target' => $target, 'nv_check' => $check);
		if ($type == 1)
		{
			//自定义链接
			$url = trim($A->strPost('nv_url'));
			if ($url == '') die('ERR|请填写链接地址。|nv_url');
			$insertarr['nv_url'] = $url;	
		}
		elseif ($type == 2)
		{
			//栏目链接
			$category = intval($A->strPost('nv_category'));
			$insertarr['nv_url'] = FCApplication::sharedRouting()->getUrl('content/category/'.$category);
		}
		elseif ($type == 3)
		{
			//页面链接
			$page = intval($A->strPost('nv_page'));
			$insertarr['nv_url'] = FCApplication::sharedRouting()->getUrl('content/page/'.intval($page));
		}
		else
		die('ERR|没有链接选择类型。');
		
		//查询最大排序值
		$query = $D->query('select max(nv_order) as num from T[navigate]');
		$rst = $D->fetch($query);
		$insertarr['nv_order'] = $rst['num'] + 1;
		$D->insert('T[navigate]', $insertarr);
		
		$A->logInsert('添加了导航 #'.$title);
	
		echo 'YES';
		break;
		
	case 'navigate.check':
		if (!$U->hasRights('content.navigate.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = intval($A->strGet('args'));
		$check = intval($A->strGet('check')) ? 1 : 0;
		$D->update('T[navigate]', array('nv_check' => $check), array('nv_id' => $args));
		
		$A->logInsert('更改导航状态为：'.($check ? '启用' : '禁用').' #'.$args);
		
		echo 'YES';
		break;
	
	case 'navigate.target':
		if (!$U->hasRights('content.navigate.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = intval($A->strGet('args'));
		$target = intval($A->strGet('target')) ? 1 : 0;
		$D->update('T[navigate]', array('nv_target' => $target), array('nv_id' => $args));
		
		$A->logInsert('更改导航新窗口状态为：'.($target ? '是' : '否').' #'.$args);
		
		echo 'YES';
		break;
		
	case 'navigate.update':
		if (!$U->hasRights('content.navigate.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$ids = $A->strPost('ids');
		$nv_order = $A->strPost('nv_order');
		$nv_title = $A->strPost('nv_title');
		$nv_url	  = $A->strPost('nv_url');
		if (!is_array($ids))
		{
			die('ERR|没有选中记录。');
		}
		foreach ($ids as $key => $id)
		{
			$D->update('T[navigate]', array('nv_title' => $nv_title[$key], 'nv_order' => $nv_order[$key], 'nv_url' => $nv_url[$key]), array('nv_id' => $id));
		}
		
		$A->logInsert('编辑了导航 #'.implode(',', $ids));
		echo 'YES';
		
		break;
		
	case 'navigate.delete':
		if (!$U->hasRights('content.navigate.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = intval($A->strGet('args'));
		$D->delete('T[navigate]', 'nv_id = '.$args);
		
		$A->logInsert('删除了导航 #'.$args);
		
		echo 'YES';
		break;
		
	default:
		echo 'ERR|无效请求。';
		break;
}
?>