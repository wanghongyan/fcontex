<?php
/***
 * 名称：留言本模块类
 *      模块命名方式必须是module加首字段大写的目录名才会被内核识别
 * Joe 2012.03.06
 * www.fcontex.com
*/

final class FCModuleContent
{
	/***
	 * 模块基本信息
	*/
	public $basic = array
	(
		'for'     => '1.0',
		'name'    => '内容管理',
		'icon'    => 'module.png',
		'desc'    => '内容模块，提供文章栏目分类及内容管理功能。',
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
		'content.category.select' => '栏目查看',
		'content.category.insert' => '栏目添加',
		'content.category.update' => '栏目编辑',
		'content.category.order'  => '栏目排序',
		'content.category.delete' => '栏目删除',
		'content.select' => '查看信息',
		'content.insert' => '添加信息',
		'content.update' => '编辑信息',
		'content.delete' => '删除信息',
		'content.page.select' => '查看页面',
		'content.page.insert' => '创建页面',
		'content.page.update' => '编辑页面',
		'content.page.delete' => '删除页面',
		'content.tags.select' => '查看标签',
		'content.tags.update' => '编辑标签',
		'content.tags.delete' => '删除标签',
		'content.navigate.select'  => '查看导航',
		'content.navigate.insert'  => '添加导航',
		'content.navigate.update'  => '修改导航',
		'content.navigate.delete'  => '删除导航'
	);
	
	public $menus = array
	(
		'查看栏目' => array('url' => 'mode=category.select', 'icon' => 'category.select.png'),
		'添加栏目' => array('url' => 'mode=category.insert', 'icon' => 'category.insert.png'),
		'查看信息' => array('url' => 'mode=content.select', 'icon' => 'content.select.png'),
		'添加信息' => array('url' => 'mode=content.insert', 'icon' => 'content.insert.png'),
		'页面管理' => array('url' => 'mode=page.select', 'icon' => 'page.select.png'),
		'创建页面' => array('url' => 'mode=page.insert', 'icon' => 'page.insert.png'),
		'标签管理' => array('url' => 'mode=tags.select', 'icon' => 'tags.select.png'),
		'导航管理' => array('url' => 'mode=navigate.select', 'icon' => 'navigate.select.png')
	);
	
	/***
	 * 模块安装回调函数
	 * 用于完成模块安装时的初始化工作
	*/
	function install ()
	{
	}
	
	/***
	 * 模块反安装回调函数
	 * 用于完成模块卸载时的清理工作
	*/
	function uninstall ()
	{
	}
}
?>