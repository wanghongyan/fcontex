<?php
/***
 * 名称：系统模块动作处理程序
 * Alan 2012.03
 * www.fcontex.com
*/

include '../../../kernel/startup.php';

switch ($mode = $A->strGet('mode'))
{
	case 'module.insert':
	case 'module.update':
		if (!$U->hasRights('system.modules.insert'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		if (!$U->hasRights('system.modules.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		$path = PATH_MODULE.$args.'/module.php';
		include $path;
		$class = 'FCModule'.ucfirst($args);
		if (!class_exists($class))
		{
			echo '参数错误 [ '.$args.' ] 。';
		}
		else
		{
			//缓存模块组
			$module = new $class();
			$modules = $A->loadConfig('system.modules');
			$modules[$args] = $module->basic;
			$modules[$args]['menus'] = $module->menus;
			$modules[$args]['rights'] = $module->rights;
			$A->saveConfig('system.modules', $modules);
			
			//安装函数
			if ($mode == 'module.insert') $module->install();
			$A->logInsert(($mode=='module.insert' ? '安装' : '更新') . '了模块 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'module.disable':
	case 'module.delete':
		if (!$U->hasRights('system.modules.disable'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		if (!$U->hasRights('system.modules.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		if (in_array(strtoupper($args), explode('|', SYSTEM_MODULE)))
		{
			echo 'ERR|核心模块不允许禁用。';
		}
		else
		{
			$path = PATH_MODULE.$args.'/module.php';
			include $path;
			$class = 'FCModule'.ucfirst($args);
			if (!class_exists($class))
			{
				echo '参数错误 [ '.$args.' ] 。';
			}
			else
			{
				//清理模块组缓存
				$module = new $class();
				$modules = $A->loadConfig('system.modules');
				unset($modules[$args]);
				$module->uninstall();
				$A->saveConfig('system.modules', $modules);
				
				//卸载函数
				if ($mode == 'module.delete') $module->uninstall();
				$A->logInsert(($mode=='module.disable' ? '禁用' : '删除') . '了模块 #'.$args);
				echo 'YES';
			}
		}
		break;
		
	case 'module.order':
		if (!$U->hasRights('system.modules.order'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$args = $A->strGet('args');
		$old = $A->loadConfig('system.modules');
		$new = array();
		foreach (explode(',', $args) as $key)
		{
			if (isset($old[$key])) $new[$key] = $old[$key];
		}
		$A->saveConfig('system.modules', $new);
		
		$A->logInsert('更新了模块排序');
		
		echo 'YES';
		
		break;
	
	//站点配置
	case 'config.site':
		if (!$U->hasRights('system.site.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$data = $A->loadConfig('system.site');
		$data['site_name'] 			= $A->strPost('site_name');
		$data['site_domain'] 		= str_replace('http://', '', $A->strPost('site_domain'));
		$data['site_domainlock'] 	= intval($A->strPost('site_domainlock')) ? TRUE : FALSE;
		$data['site_title'] 		= $A->strPost('site_title');
		$data['site_keywords'] 		= $A->strPost('site_keywords');
		$data['site_description'] 	= $A->strPost('site_description');
		$data['site_rewrite'] 		= intval($A->strPost('site_rewrite')) ? TRUE : FALSE;
		$data['site_pagesize'] 		= intval($A->strPost('site_pagesize'));
		$data['site_commentlock']	= intval($A->strPost('site_commentlock')) ? TRUE : FALSE;
		$data['site_gbooklock'] 	= intval($A->strPost('site_gbooklock')) ? TRUE : FALSE;
		$data['site_email'] 		= $A->strPost('site_email');
		$data['site_counter'] 		= $A->strPost('site_counter', FALSE);
		$data['site_copyright'] 	= $A->strPost('site_copyright');
		$data['site_emailserver'] 	= $A->strPost('site_emailserver');
		$data['site_emailpassword'] = $A->strPost('site_emailpassword') == $A->loadConfig('system.site', 'site_emailpassword') ? $A->strPost('site_emailpassword') : $A->strEnCode($A->strPost('site_emailpassword'));
		
		$A->saveConfig('system.site', $data);
		$A->logInsert('更新了站点配置');
		echo 'YES';
		break;
	
	case 'config.skin':
		if (!$U->hasRights('system.skins'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$arg = trim($A->strGet('args'));
		if ($arg == '')
		{
			echo 'ERR|皮肤设置失败。';
		}
		else 
		{
			$D->update('T[user]', array('us_skin' => $arg), array('us_id' => $_SESSION['userInfo']['us_id']));
			$_SESSION['userInfo']['us_skin'] = $arg;
			$A->logInsert('更换了皮肤 # '.$arg);
			echo 'YES';
		}
		break;
	
	case 'config.theme':
		if (!$U->hasRights('system.themes'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$arg = trim($A->strGet('args'));
		if ($arg == '')
		{
			echo 'ERR|主题设置失败。';
		}
		else 
		{
			$data = $A->loadConfig('system.site');
			$data['site_theme'] = $arg;
			$A->saveConfig('system.site', $data);
			$A->logInsert('更换了主题 # '.$arg);
			echo 'YES';
		}
		break;
	
	case 'log.delete':
		if (!$U->hasRights('system.logs.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$ids = $A->strGet('args');
		if (trim($ids) == '')
		{
			echo 'ERR|请选择记录。';
		}
		else 
		{
			if ($ids == '*')
			{
				$D->delete('T[logs]', '1=1');
			}
			else
			{
				$D->delete('T[logs]', 'lg_id in ('.$ids.')');
			}
			
			$A->logInsert('清理了日志');
			
			echo 'YES';
		}
		break;
	
	case 'file.delete':
		if (!$U->hasRights('system.file.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$ids = $A->strGet('args');
		if (trim($ids) == '')
		{
			echo 'ERR|请选择记录。';
		}
		else 
		{
			$arr = explode(',', $ids);
			$uploadPath = $A->system['uploadPath'];
			foreach ($arr as $id)
			{
				if (!intval($id)) continue;
				$res = $D->query('select at_dir, at_filenewname, at_id from T[attached] where at_id = '.intval($id));
				$rst = $D->fetch($res);
				$path1 = $uploadPath.$rst['at_dir'].$rst['at_filenewname'];
				if (file_exists($path1))
				{
					unlink($path1);
				}
				$D->delete('T[attached]', 'at_id = '.$rst['at_id']);
			}
			$A->logInsert('删除了附件 # '.$ids);
			echo 'YES';
		}
		break;
	
	case 'file.upload':
		//此处无法获取cookie, 重新验证登录, 防止非法上传
		$passport = explode("\n", $A->strDecode($A->strPost('passport')));
		if (count($passport) != 2)
		{
			die(ERROR_NO_RIGHTS);
		}
		
		$sql = 'select us_group, us_id from T[user] where us_username = "'.$passport[0].'" and us_password = "'.$A->strEncode($passport[1]).'"';
		$res = $D->query($sql);
		if (!($rstU = $D->fetch($res)))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$sql = 'select gr_rights, gr_id from T[group] where gr_id = '.$rstU['us_group'];
		$res = $D->query($sql);
		$rstG = $D->fetch($res);
		if (!in_array('system.file.upload', explode(',', $rstG['gr_rights'])) && !in_array($rstU['us_id'], explode(',', $A->system['system_user'])))
		{
			die(ERROR_NO_RIGHTS);
		}
		//验证结束//
		
		$fileDir = $A->strPost('dir');
		$config_filetype = $A->system['uploadSuffix'];
		$fileType = $A->strPost('type');
		$suffix = isset($config_filetype[$fileType]) ? $config_filetype[$fileType] : $config_filetype['image'];
		$upload = $A->loadLibrary('fileupload');
		$upload->fileLimitSuffix = $suffix;
		$upload->fileNewDir = $fileDir;
		$data = $upload->upload();
		//上传成功
		if ($data['error'] == '')
		{
			//存入数据库
			$insertarr = array
			(
				'at_filenewname'	=> $data['fileNewName'],
				'at_size'			=> $data['fileSize'],
				'at_suffix'			=> $data['fileSuffix'],
				'at_time'			=> time(),
				'at_dir'			=> $data['fileDir'],
				'at_filename'		=> $data['fileName'],
				'at_isimage'		=> $data['isImage'],
				'at_filetype'		=> $data['fileType'],
				'at_uid'			=> $rstU['us_id']
			);
			$D->insert('T[attached]', $insertarr);
			$data['fileID'] = $D->insertid('T[attached]');
			$data['filePipe'] = $R->getUrl('system/file/'.$A->strEnCode($data['fileID']), '', DIR_SITE);
			
			$A->logInsert('上传了附件 # '.$data['fileID'], $rstU['us_id']);
		}
		$data['fileUrl'] = DIR_SITE.DIR_STORE.'/'.$A->system['uploadDir'].'/'.$data['fileDir'].$data['fileNewName'];
		$data['fileIcon'] = $A->fileIcon($A->fileSuffix($data['fileName']));
		echo json_encode($data);
		break;
	
	case 'cache.clean':
		if (!$U->hasRights('system.cache'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = $A->strGet('args');
		foreach (explode(',', $args) as $name)
		{
			$name = PATH_CACHE.$name;
			if (is_dir($name))
			{
				$A->cleanDirectory($name);
			}
			else
			{
				unlink($name);
			}
		}
		echo 'YES';
		break;
	
	case 'cache.time':
		if (!$U->hasRights('system.cache'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$data = $A->loadConfig('system.site');
		$data['site_cachetime'] = intval($A->strGet('args'));
		$A->saveConfig('system.site', $data);
		$A->logInsert('更新了缓存配置');
		echo 'YES';
		break;
		
	default:
		echo 'ERR|无效请求。';
		break;
}
?>