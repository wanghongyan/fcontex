<?php
/***
 * 名称：用户模块动作处理程序
 * Alan 2012.03
 * www.fcontex.com
*/

include '../../../../kernel/startup.php';

switch ($mode = $A->strGet('mode'))
{
	case 'group.insert':
		
		if (!$U->hasRights('user.group.insert'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$gr_name = trim($A->strPost('gr_name'));
		if ( $gr_name == '' )
		{
			echo 'ERR|请输入组名称。|gr_name';
		}
		else
		{
			
			if (is_array($rights = $A->strPost('gr_rights')))
			{
				$rights = implode(',', $rights);
			}
			
			$insertarr = array
			(
				'gr_name'	=> $gr_name,
				'gr_desc'	=> $A->strPost('gr_desc'),
				'gr_rights' => $rights,
				'gr_time'	=> time()
			);
			$D->insert('T[group]', $insertarr);
			
			$A->logInsert('添加了用户组 #'.$D->insertid('T[group]'));
			echo 'YES';
			
		}
		break;
		
	case 'group.update':
		
		if (!$U->hasRights('user.group.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$gr_name = trim($A->strPost('gr_name'));
		
		$args = $A->strGet('args');
		if (!is_numeric($args))
		{
			echo 'ERR|错误的参数[ '.$args.' ]。';
		}
		elseif ( $gr_name == '' )
		{
			echo 'ERR|请输入组名称。|gr_name';
		}
		else
		{
			$rights = '';
			if (is_array($rights = $A->strPost('gr_rights')))
			{
				$rights = implode(',', $rights);
			}
		
			$updatearr = array
			(
				'gr_name'	=> $gr_name,
				'gr_desc'	=> $A->strPost('gr_desc'),
				'gr_rights'	=> $rights
			);
			$D->update('T[group]', $updatearr, array('gr_id' => $args));
			
			$A->logInsert('编辑了用户组 #'.$args);
			echo 'YES';
			
		}
		break;
		
	case 'group.delete':
		
		if (!$U->hasRights('user.group.delete'))
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
			$D->delete('T[group]', 'gr_id in('.$args.')');
			
			$A->logInsert('删除了用户组 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'user.insert':
		
		if (!$U->hasRights('user.insert'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$us_username = trim($A->strPost('us_username'));
		$us_password = trim($A->strPost('us_password'));
		$us_password2 = trim($A->strPost('us_password2'));
		$us_group = trim($A->strPost('us_group'));
		$us_name = trim($A->strPost('us_name'));
		if ( $us_username == '' )
		{
			echo 'ERR|请输入登录帐户。|us_username';
		}
		elseif ( $us_password == '' )
		{
			echo 'ERR|请输入登录密码。|us_password';
		}
		elseif ( $us_password != $us_password2 )
		{
			echo 'ERR|两次输入密码不一致。|us_password2';
		}
		elseif ( $us_group == '0' )
		{
			echo 'ERR|请选择所属分组。|us_group';
		}
		elseif ( $us_name == '' )
		{
			echo 'ERR|请输入用户姓名。|us_name';
		}
		else
		{
			$insertarr = array
			(
				'us_group'		=> $us_group,
				'us_username'	=> $us_username,
				'us_password'	=> $A->strEnCode($us_password),
				'us_name'		=> $us_name,
				'us_email'		=> $A->strPost('us_email'),
				'us_phone'		=> $A->strPost('us_phone'),
				'us_face'		=> $A->strPost('us_face'),
				'us_time'		=> time()
			);
			$D->insert('T[user]', $insertarr);
			
			$A->logInsert('删除了用户 #'.$D->insertid('T[user]'));
			echo 'YES';
			
		}
		break;
		
	case 'user.update':
		$args = $A->strGet('args');
		
		$uid = isset($_SESSION['userInfo']['us_id']) ? $_SESSION['userInfo']['us_id'] : 0;
		if (!$U->hasRights('user.update') && $uid != $args)
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		
		$us_username = trim($A->strPost('us_username'));
		$us_password = trim($A->strPost('us_password'));
		$us_password2 = trim($A->strPost('us_password2'));
		$us_group = trim($A->strPost('us_group'));
		$us_name = trim($A->strPost('us_name'));
		
		if ( $us_username == '' )
		{
			echo 'ERR|请输入登录帐户。|us_username';
		}
		elseif ( $us_password != '' && $us_password != $us_password2 )
		{
			echo 'ERR|两次输入密码不一致。|us_password2';
		}
		elseif ( $us_group == '0' )
		{
			echo 'ERR|请选择所属分组。|us_group';
		}
		elseif ( $us_name == '' )
		{
			echo 'ERR|请输入用户姓名。|us_name';
		}
		elseif ($U->isSystemUser($args) && !$U->isSystemUser())
		{
			echo 'ERR|没有权限编辑该用户。';
		}
		else
		{
			$updatearr = array
			(
				'us_group'		=> $us_group,
				'us_username'	=> $us_username,
				'us_name'		=> $us_name,
				'us_email'		=> trim($A->strPost('us_email')),
				'us_phone'		=> trim($A->strPost('us_phone')),
				'us_desc'		=> trim($A->strPost('us_desc')),
				'us_face'		=> $A->strPost('us_face')
			);
			if ($us_password != '') $updatearr['us_password'] = $A->strEnCode($us_password);
			$D->update('T[user]', $updatearr, array('us_id' => $args));
			
			if ($args == $_SESSION['userInfo']['us_id'])
			{
				$_SESSION['userInfo']['us_group']	= $us_group;
				$_SESSION['userInfo']['us_name']	= $us_name;
				$_SESSION['userInfo']['us_email']	= trim($A->strPost('us_email'));
				$_SESSION['userInfo']['us_phone']	= trim($A->strPost('us_phone'));
				$_SESSION['userInfo']['us_desc']	= trim($A->strPost('us_desc'));
				$_SESSION['userInfo']['us_face']	= $A->strPost('us_face');
			}
			
			$A->logInsert('编辑了用户 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'user.delete':
		
		if (!$U->hasRights('user.delete'))
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
			$ids = array();
			foreach (explode(',', $args) as $id)
			{
				if (!$U->isSystemUser($id) && $U->isSystemUser())
				{
					$D->delete('T[user]', 'us_id in('.$args.')');
					$ids[] = $id;
				}
			}
			
			$A->logInsert('删除了用户 #'.implode($ids));
			echo 'YES';
		}
		break;
	
	case 'user.login':
		$username = $A->strPost('username');
		$password = $A->strPost('password');
		if (trim($username) == '')
		{
			echo 'ERR|请输入用户名。';
		}
		elseif (trim($password) == '')
		{
			echo 'ERR|请输入密码。';
		}
		else 
		{
			//暂以文件形式验证次数
			$userip = $A->getUserIP();
			$filePath = PATH_STORE.'cache/login/'.md5(($userip ? $userip : 'err')).'.php';
			!file_exists($filePath) && file_put_contents($filePath, 5);
			$hit = file_get_contents($filePath);
			//以文件修改时间判断是否可以重置登录数
			if ($hit < 1)
			{
				$filemtime = filemtime($filePath);
				if (($filemtime+60*15) < time())
				{
					$hit = 5;
					file_put_contents($filePath, $hit);
				}
				else exit('ERR|重复次数过多，请'.intval(15 - (time() - $filemtime) / 60).'分钟后再试。');
			}
			
			$query = $D->query('select us_id from T[user] where us_username = "'.$username.'" and us_password = "'.$A->strEnCode($password).'"');
			$r = $D->fetch($query);
			if ($D->count($query) < 1)
			{
				$hit--;
				file_put_contents($filePath, $hit);
				echo 'ERR|用户名或密码不正确，还可以试'.$hit.'次。';
			}
			else 
			{
				$A->setCookie('passport', $A->strEnCode($username."\n".$password));
				$res = $D->query('select * from T[user] where us_id = '.$r['us_id']);
				$rst = $D->fetch($res);
				$_SESSION['userInfo'] = $rst;
				
				$res = $D->query('select * from T[group] where gr_id = '.$rst['us_group']);
				$rst = $D->fetch($res);
				$_SESSION['userGroup'] = $rst;
				
				if (!$U->hasRights('system.login'))
				{
					die('ERR|没有后台登录权限。');
				}
				$skin = $_SESSION['userInfo']['us_skin'] ? str_replace($A->system['skin'], $_SESSION['userInfo']['us_skin'], URL_SKIN) : '';
				file_put_contents($filePath, 5);
				$A->logInsert('登录了系统');
				echo 'YES|'.$skin.'|'.$_SESSION['userInfo']['us_id'];
			}
		}
		break;
		
	case 'login.out':
		if ($U->hasRights('system.login'))
		{
			$A->logInsert('退出了系统');
			unset($_SESSION['userInfo']);
			unset($_SESSION['userGroup']);
			$A->setCookie('passport', '');
		}
		echo 'YES';
		break;
	
	//发送重置密码链接
	case 'retrievecode':
		$username = trim($A->strPost('username'));
		$email = trim($A->strPost('email'));
		if ($username == '')
		{
			echo 'ERR|请填写账号。|username';
		}
		elseif (!preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/", $email))
		{
			echo 'ERR|请填写正确的邮箱地址。||email';
		}
		else
		{
			$query = $D->query('select count(*) as num from T[user] where us_username = "'.$username.'" and us_email = "'.$email.'"');
			$rst = $D->fetch($query);
			if ($rst['num'] < 1)
			{
				echo 'ERR|账号或邮箱不匹配。';
			}
			else 
			{
				$M = $A->loadLibrary('email');
				$M->sendName = 'Fcontex 官方邮件';
				$M->sendEmail = $A->loadConfig('system.site', 'site_email');
				$M->sendUser = $A->loadConfig('system.site', 'site_email');
				$M->sendHost = $A->loadConfig('system.site', 'site_emailserver');
				$M->sendPass = $A->strDeCode($A->loadConfig('system.site', 'site_emailpassword'));
				$body = '您好，这是来自<a href="http://www.fcontex.com" target="_blank">Fcontex</a>一封找回密码邮件。<br /><br />';
				$time = time();
				$code = $A->strEnCode($username.'|'.$email.'|'.$time);				
				$body .= '点击此链接设置新密码：'.URL_SITE.'module/user/system/?mode=user.resetcode&code='.$code;
				$body .= '<br />15分钟内有效，请尽快修改您的密码。';
				if ($M->mailTo($email, 'Fcontex 系统密码找回服务', $body, $username))
				{
					$D->update('T[user]', array('us_resetcodetime' => $time), array('us_username' => $username));
					echo 'YES';
				}
				else echo 'ERR|'.$M->err;
			}
		}
	
		break;
	
	//重置密码
	case 'user.resetcode':
		$code = $A->strPost('code');
		$username = trim($A->strPost('username'));
		$password_1 = trim($A->strPost('password_1'));
		$password_2 = trim($A->strPost('password_2'));
		$code = explode('|', $A->strDeCode($code));
		if (count($code) != 3)
		{
			exit('ERR|参数错误。');
		}
		if (trim($code[0]) == '' || trim($code[1]) == '' || trim($code[2]) == '')
		{
			exit('ERR|参数错误。');
		}
		if ($code[0] != $username)
		{
			exit('ERR|用户名不正确。');
		}
		$query = $D->query('select count(*) as num from T[user] where us_resetcodetime = '.$code[2].' and us_username = "'.$code[0].'" and us_email = "'.$code[1].'"');
		$rst = $D->fetch($query);
		if ($rst['num'] < 1)
		{
			exit('ERR|获取用户失败。');
		}
		if (((time() - $code[2]) / 60) > 15)
		{
			exit('ERR|链接已经过期。');
		}
		if ($password_1 == '')
		{
			echo 'ERR|请填写新的密码。';
		}
		elseif ($password_2 != $password_1)
		{
			echo 'ERR|两次输入密码不一致。';
		}
		else 
		{
			$D->update('T[user]', array('us_password' => $A->strEnCode($password_1), 'us_resetcodetime' => ''), array('us_username' => $username));
			echo 'YES';
		}
		
		break;
	
	default:
		echo 'ERR|无效请求。';
		break;
}
?>