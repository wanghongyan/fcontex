<?php
/*自动化配置文件*/
return array (
  'system' => 
  array (
    'for' => '1.0',
    'name' => '系统配置',
    'icon' => 'module.png',
    'desc' => '系统核心，提供基础运行库、模块控制、全局配置、栏目管理等最核心的功能。',
    'author' => 'alan',
    'contact' => 'alan#fcontex.com',
    'version' => '1.0',
    'update' => '2012.05',
    'support' => 'www.fcontex.com',
    'menus' => 
    array (
      '站点配置' => 
      array (
        'url' => 'mode=site',
        'icon' => 'global.config.icon.png',
      ),
      '模块管理' => 
      array (
        'url' => 'mode=modules',
        'icon' => 'modules.icon.png',
      ),
      '附件管理' => 
      array (
        'url' => 'mode=file',
        'icon' => 'files.icon.png',
      ),
      '缓存管理' => 
      array (
        'url' => 'mode=cache',
        'icon' => 'cache.icon.png',
      ),
      '操作日志' => 
      array (
        'url' => 'mode=logs',
        'icon' => 'logs.icon.png',
      ),
    ),
    'rights' => 
    array (
      'system.login' => '控制台登录',
      'system.site.select' => '配置查看',
      'system.site.update' => '配置更新',
      'system.modules.select' => '模块查看',
      'system.modules.insert' => '模块安装',
      'system.modules.update' => '模块更新',
      'system.modules.disable' => '模块禁用',
      'system.modules.order' => '模块排序',
      'system.modules.delete' => '模块卸载',
      'system.logs.select' => '日志查看',
      'system.logs.delete' => '日志清理',
      'system.file.select' => '附件查看',
      'system.file.delete' => '附件删除',
      'system.file.upload' => '上传文件',
      'system.cache' => '缓存管理',
      'system.skins' => '控制台皮肤',
      'system.themes' => '网站主题',
    ),
  ),
  'user' => 
  array (
    'for' => '1.0',
    'name' => '用户管理',
    'icon' => 'module.png',
    'desc' => '用户模块，提供用户管理和权限控制功能。',
    'author' => 'Joe',
    'contact' => 'Joe@fcontex.com',
    'version' => '1.0',
    'update' => '2012.05',
    'support' => 'www.fcontex.com',
    'menus' => 
    array (
      '分组查看' => 
      array (
        'url' => 'mode=group.select',
        'icon' => 'group.select.png',
      ),
      '分组添加' => 
      array (
        'url' => 'mode=group.insert',
        'icon' => 'group.insert.png',
      ),
      '用户查看' => 
      array (
        'url' => 'mode=user.select',
        'icon' => 'user.select.png',
      ),
      '用户添加' => 
      array (
        'url' => 'mode=user.insert',
        'icon' => 'user.insert.png',
      ),
    ),
    'rights' => 
    array (
      'user.group.select' => '分组查看',
      'user.group.insert' => '分组添加',
      'user.group.update' => '分组编辑',
      'user.group.delete' => '分组删除',
      'user.select' => '用户查看',
      'user.insert' => '用户添加',
      'user.update' => '用户编辑',
      'user.delete' => '用户删除',
    ),
  ),
  'content' => 
  array (
    'for' => '1.0',
    'name' => '内容管理',
    'icon' => 'module.png',
    'desc' => '内容模块，提供文章栏目分类及内容管理功能。',
    'author' => 'alan',
    'contact' => 'alan#fcontex.com',
    'version' => '1.0',
    'update' => '2012.05',
    'support' => 'www.fcontex.com',
    'menus' => 
    array (
      '查看栏目' => 
      array (
        'url' => 'mode=category.select',
        'icon' => 'category.select.png',
      ),
      '添加栏目' => 
      array (
        'url' => 'mode=category.insert',
        'icon' => 'category.insert.png',
      ),
      '查看信息' => 
      array (
        'url' => 'mode=content.select',
        'icon' => 'content.select.png',
      ),
      '添加信息' => 
      array (
        'url' => 'mode=content.insert',
        'icon' => 'content.insert.png',
      ),
      '页面管理' => 
      array (
        'url' => 'mode=page.select',
        'icon' => 'page.select.png',
      ),
      '创建页面' => 
      array (
        'url' => 'mode=page.insert',
        'icon' => 'page.insert.png',
      ),
      '标签管理' => 
      array (
        'url' => 'mode=tags.select',
        'icon' => 'tags.select.png',
      ),
      '导航管理' => 
      array (
        'url' => 'mode=navigate.select',
        'icon' => 'navigate.select.png',
      ),
    ),
    'rights' => 
    array (
      'content.category.select' => '栏目查看',
      'content.category.insert' => '栏目添加',
      'content.category.update' => '栏目编辑',
      'content.category.order' => '栏目排序',
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
      'content.navigate.select' => '查看导航',
      'content.navigate.insert' => '添加导航',
      'content.navigate.update' => '修改导航',
      'content.navigate.delete' => '删除导航',
    ),
  ),
  'message' => 
  array (
    'for' => '1.0',
    'name' => '消息管理',
    'icon' => 'module.png',
    'desc' => '消息模块，提供留言、文章评论等功能。',
    'author' => 'alan',
    'contact' => 'alan#fcontex.com',
    'version' => '1.0',
    'update' => '2012.05',
    'support' => 'www.fcontex.com',
    'menus' => 
    array (
      '留言管理' => 
      array (
        'url' => 'mode=gbook.select',
        'icon' => 'gbook.select.png',
      ),
      '评论管理' => 
      array (
        'url' => 'mode=comment.select',
        'icon' => 'comment.select.png',
      ),
    ),
    'rights' => 
    array (
      'gbook.select' => '查看留言',
      'gbook.update' => '编辑留言',
      'gbook.delete' => '删除留言',
      'comment.select' => '评论列表',
      'comment.update' => '编辑评论',
      'comment.delete' => '删除评论',
    ),
  ),
);
?>