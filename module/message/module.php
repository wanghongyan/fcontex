<?php
/***
 * 名称：消息模块类
 *      模块命名方式必须是module加首字段大写的目录名才会被内核识别
 * Alan 2012.03.06
 * www.fcontex.com
*/

final class FCModuleMessage
{
	/***
	 * 模块基本信息
	*/
	public $basic = array
	(
		'for'     => '1.0',
		'name'    => '消息管理',
		'icon'    => 'module.png',
		'desc'    => '消息模块，提供留言、文章评论等功能。',
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
		'gbook.select' => '查看留言',
		'gbook.update' => '编辑留言',
		'gbook.delete' => '删除留言',
		'comment.select' => '评论列表',
		'comment.update' => '编辑评论',
		'comment.delete' => '删除评论'
	);
	
	public $menus = array
	(
		'留言管理' => array('url' => 'mode=gbook.select', 'icon' => 'gbook.select.png'),
		'评论管理' => array('url' => 'mode=comment.select', 'icon' => 'comment.select.png')
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