<?php
/***
 * 名称：系统模块类
 *      模块命名方式必须是FCModule加首字段大写的目录名才会被内核识别
 * Alan 2012.03.06
 * www.fcontex.com
*/

final class FCModuleSystem
{
	/***
	 * 模块基本信息
	*/
	public $basic = array
	(
		'for'     => '1.0',
		'name'    => '系统配置',
		'icon'    => 'module.png',
		'desc'    => '系统核心，提供基础运行库、模块控制、全局配置、栏目管理等最核心的功能。',
		'author'  => 'alan',
		'contact' => 'alan#fcontex.com',
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
		'system.login' => '控制台登录',
		'system.site.select' => '配置查看',
		'system.site.update' => '配置更新',
		'system.modules.select'  => '模块查看',
		'system.modules.insert'  => '模块安装',
		'system.modules.update'  => '模块更新',
		'system.modules.disable' => '模块禁用',
		'system.modules.order'   => '模块排序',
		'system.modules.delete'  => '模块卸载',
		'system.logs.select'     => '日志查看',
		'system.logs.delete'     => '日志清理',
		'system.file.select'	 => '附件查看',
		'system.file.delete'	 => '附件删除',
		'system.file.upload'	 => '上传文件',
		'system.cache'  => '缓存管理',
		'system.skins'  => '控制台皮肤',
		'system.themes' => '网站主题'
	);
	
	/***
	 * 模块功能菜单
	*/
	public $menus = array
	(
		'站点配置' => array('url' => 'mode=site', 'icon' => 'global.config.icon.png'),
		'模块管理' => array('url' => 'mode=modules', 'icon' => 'modules.icon.png'),
		'附件管理' => array('url' => 'mode=file', 'icon' => 'files.icon.png'),
		'缓存管理' => array('url' => 'mode=cache', 'icon' => 'cache.icon.png'),
		'操作日志' => array('url' => 'mode=logs', 'icon' => 'logs.icon.png')
	);
	
	/***
	 * 模块安装回调函数
	 * 用于完成模块安装时的初始化工作
	*/
	function install()
	{
		return TRUE;
	}
	
	/***
	 * 模块反安装回调函数
	 * 用于完成模块卸载时的清理工作
	*/
	function uninstall()
	{
		return TRUE;
	}
}
?>