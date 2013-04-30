<?php
/***
 * 名称：用户模块类
 *      模块命名方式必须是module加首字段大写的目录名才会被内核识别
 * Joe 2012.03.06
 * www.fcontex.com
*/

final class FCModuleUser
{
	/***
	 * 模块基本信息
	*/
	public $basic = array
	(
		'for'     => '1.0',
		'name'    => '用户管理',
		'icon'    => 'module.png',
		'desc'    => '用户模块，提供用户管理和权限控制功能。',
		'author'  => 'Joe',
		'contact' => 'Joe@fcontex.com',
		'version' => '1.0',
		'update'  => '2012.05',
		'support' => 'www.fcontex.com',
	);
	
	/***
	 * 模块权限字段 安装时注册到内核中
	 * 为保证全局唯一请使用模块目录名加下划线作前缀
	*/
	public $rights = array
	(
		'user.group.select' => '分组查看',
		'user.group.insert' => '分组添加',
		'user.group.update' => '分组编辑',
		'user.group.delete' => '分组删除',
		'user.select' => '用户查看',
		'user.insert' => '用户添加',
		'user.update' => '用户编辑',
		'user.delete' => '用户删除',
	);
	
	public $menus = array
	(
		'分组查看' => array('url' => 'mode=group.select', 'icon' => 'group.select.png'),
		'分组添加' => array('url' => 'mode=group.insert', 'icon' => 'group.insert.png'),
		'用户查看' => array('url' => 'mode=user.select', 'icon' => 'user.select.png'),
		'用户添加' => array('url' => 'mode=user.insert', 'icon' => 'user.insert.png'),
	);
	
	/***
	 * 模块安装回调函数
	 * 用于完成模块安装时的初始化工作
	*/
	function install()
	{
	}
	
	/***
	 * 模块反安装回调函数
	 * 用于完成模块卸载时的清理工作
	*/
	function uninstall()
	{
	}
}
?>