<?php
/***
 * 名称：用户类
 * Alan, 2012.06
 * http://www.fcontex.com/
*/
 
final class user
{
	private $app = NULL;
	private $db  = NULL;
	
	function __construct($database=NULL)
	{
		$this->app = FCApplication::sharedApplication();
		$this->db  = $database ? $database : FCApplication::sharedDataBase();
	}
	
	//读取COOKIE通行证来创建用户会话
	public function session()
	{
		if (!$this->app->getCookie('passport'))
		{
			return false;
		}
		
		$passport = explode("\n", $this->app->strDecode($this->app->getCookie('passport')));
		if (count($passport) != 2)
		{
			return false;
		}
		//die(var_export($passport, TRUE));
		
		$sql = 'select * from T[user] where us_username = "'.$passport[0].'" and us_password = "'.$this->app->strEncode($passport[1]).'"';
		$res = $this->db->query($sql);
		if (!($rstU = $this->db->fetch($res)))
		{
			return false;
		}
		//die(var_export($rstU, TRUE));
		
		$sql = 'select * from T[group] where gr_id = '.$rstU['us_group'];
		$res = $this->db->query($sql);
		if (!($rstG = $this->db->fetch($res)))
		{
			return false;
		}
		//die(var_export($rstG, TRUE));
		
		$_SESSION['userInfo'] = $rstU;
		$_SESSION['userGroup'] = $rstG;
		
		//die(var_export($_SESSION, TRUE));
		
		return TRUE;
	}
	
	//用户权限检查
	public function hasRights($rights='system.login')
	{
		if (!isset($_SESSION['userInfo']))
		{
			$this->session();
		}
		if ($this->isSystemUser())
		{
			return TRUE;	
		}
		if (isset($_SESSION['userGroup']['gr_rights']))
		{
			return in_array($rights, explode(',', $_SESSION['userGroup']['gr_rights']));
		}
		else return false;
	}
	
	//获取系统皮肤
	public function getSkin()
	{
		$dir = DIR_SITE.DIR_MODULE.'/system/skins/';
		if ($this->hasRights('system.login'))
		{
			if ($_SESSION['userInfo']['us_skin']) return $dir.$_SESSION['userInfo']['us_skin'].'/';
		}
		return $dir.$this->app->system['skin'].'/';
	}
	
	//获取用户头像
	public function getFace($files='')
	{
		$name = substr(strrchr($files, '/'), 1);
		if ($files == '' || !file_exists($this->app->system['uploadPath'].(str_replace($name, '', $files)).$name))
		{
			return URL_SKIN.'error_no_rights.png';
		}
		else return DIR_SITE.DIR_STORE.'/'.$this->app->system['uploadDir'].'/'.(str_replace($name, '', $files)).$name;
	}
	
	//是否为系统管理员
	public function isSystemUser($uid=0)
	{
		if (!isset($_SESSION['userInfo']['us_id']))
		{
			return false;
		}
		$uid = $uid ? $uid : $_SESSION['userInfo']['us_id'];
		return in_array($uid, explode(',', $this->app->system['system_user']));
	}
}
?>