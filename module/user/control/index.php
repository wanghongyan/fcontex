<?php
/***
 * 名称：用户模块动作处理程序
 * Alan 2012.06
 * www.fcontex.com
*/

include '../../../kernel/startup.php';

switch ($mode = $A->strGet('mode'))
{
	case 'user.login':
		$username = trim($A->strGetForSQL('username'));
		$password = trim($A->strGetForSQL('password'));
		if ($username == '')
		{
			echo 'ERR|请输入登录帐户。|username';
		}
		else if ($password == '')
		{
			echo 'ERR|请输入登录密码。|password';
		}
		else
		{
			$D = FCApplication::sharedDataBase();
			
			$sql = 'select * from T[user] where us_username = "'.$username.'"';
			//die('ERR|'.$sql);
			$res = $D->query($sql);
			if (!($rst = $D->fetch($res)))
			{
				echo 'ERR|用户名或密码错误。|username';
			}
			else
			{
				if ($A->strDeCode($rst['us_password']) == $password)
				{
					setcookie('passport', $A->strEnCode($rst['us_username']."\n".$rst['us_password']), 0, '/');
					session_unset();
					FCApplication::sharedUser()->session();
					
					echo 'YES';
				}
				else
				{
					echo 'ERR|用户名或密码错误。|uname';
				}
			}
		}
		break;
		
	default:
		echo 'ERR|无效请求。';
		break;
}
?>