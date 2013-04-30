/***
 * 名称：全局Javascript运行库
 * 依赖：jQeury
 * Alan, 2012.05
 * http://www.fcontex.com/
*/

(function($)
{
	var $$ = {};
	
	if (top.$$)
	{
		$$.dialogs = top.$$.dialogs;
	}
	else
	{
		var dialogs = $$.dialogs =
		{
			names:[],		//已创建的层对象标识组
			zmax:999,		//当前焦点层对象的z序值
			toper:null,		//当前焦点层对象
			stack:[],		//已创建的层对象组
			width:1020,		//默认宽度
			height:560,		//默认高度
			limit:8			//允许创建的层对象数量
		};
		
		//创建弹出层
		dialogs.open = function(options)
		{
			/**
			 * 设置：
			 * name  : 名称 - 对象唯一标识 
			 * model : 模态
			 * title : 标题
			 * url   : 加载页面
			 * text  : 显示文本 - 不指定url时使用
			 * icon  : 图标类型 TIP/ERR
			 * width : 自定义宽度
			 * height: 自定义高度
			 * ctrl	 ：最小化，最大化，关闭按钮是否启用[1, 1, 1]顺序传参
			 *
			 * 事件：
			 * create : 创建
			 * show   : 显示
			 * focus  : 聚焦
			 * min    : 最小化
			 * max    : 最大化
			 * close  : 关闭
			 * ok:    : 确认
			 * cancle : 取消
			 *
			 * 事件触发顺序：
			 * 创建 - 显示 - 显示后触发聚焦
			 * 拖动开始触发聚焦 - 拖动中 - 拖动结束
			 * 最小化前触发失焦 - 另一个层显示
			 * 关闭前触发失焦 - 关闭
			 *
			 * 返回创建的弹出层对象：
			 * {
			 * content:创建的jQuery对象
			 * show: 显示方法
			 * resize: 框架刷新方法
			 * focus: 聚焦方法
			 * blur: 失焦方法
			 * min: 最小化方法
			 * max: 最大化方法
			 * close: 关闭方法
			 * }
			 */
			 
			if (typeof options == 'string')
			{
				options = {url:options};
			}
			
			//内部数据缓存对象
			var cache =
			{
				name  : options.name || '',
				model : options.model || false,
				title : options.title || '',
				ticon : options.ticon || '',
				url   : options.url || '',
				text  : options.text || '',
				icon  : options.icon || 'TIP',
				width : options.width || dialogs.width,
				height: options.height || dialogs.height,
				ctrl  : options.ctrl || [1, 1, 1]
			};
			
			//检查是否已创建
			if ($.inArray(cache.name, dialogs.names) != -1)
			{
				var layer = dialogs.find(cache.name);
				if (layer) layer.show();
				return layer;
			}
			else if (cache.name)
			{
				dialogs.names.push(cache.name);
			}
			
			//开始创建新层
			cache.layerid = '_layer_'+(dialogs.zmax)+'_';
			cache.frameid = '_frame_'+(dialogs.zmax)+'_';
			
			if (cache.url)
			{
				cache.content = '<div class="dialog_loading" style="height:'+(cache.height-30)+'px;"></div><iframe onload="$(\'#'+cache.layerid+' .dialog_loading\').hide();" id="'+cache.frameid+'" width="100%" height="100%" scrolling="auto" frameborder="0" src="'+cache.url+'"></iframe>';
				cache.control = '<div class="ctrl">';
				cache.control += cache.ctrl[0] ? '<span class="min"></span>' : '';
				cache.control += cache.ctrl[1] ? '<span class="max"></span>' : '';
				cache.control += cache.ctrl[2] ? '<span class="close"></span>' : '';
				cache.control += '</div>';
				//宽高改为open可传参数||默认
				//cache.width = dialogs.width;
				//cache.height = dialogs.height;
				
				//弹出层数量限制
				if (dialogs.stack.length >= dialogs.limit)
				{
					dialogs.stack[0].close();
				}
			}
			else
			{
				cache.content = '<div class="html"><span class="'+cache.icon+'"></span><p>'+cache.text+'</p><div><input class="button ok" type="button" value="确认" />';
				if (typeof options.cancle == 'function')
				{
					cache.content += '<input class="button cancle" type="button" value="取消" />';
				}
				cache.content +='</div></div>';
				cache.control = '<div class="ctrl"></span><span class="close"></span></div>';
				cache.width = 340;
				cache.height = 170;
			}
			
			cache.layer = (cache.model ? '<div id="dialog_mask" style="z-index:'+(dialogs.zmax++)+';"></div>' : '') +
							'<div class="dialog" id="'+cache.layerid+'" style="z-index:'+dialogs.zmax+';width:'+cache.width+'px;height:'+cache.height+'px;">'+
							'<div class="back"></div><div class="cover"></div>'+
							cache.control+
							(cache.title ? '<div class="title"><h1>'+cache.ticon+cache.title+'</h1></div>' : '')+'<div class="panel">'+cache.content+'</div></div>';
			
			//层对象layer创建
			var container = $('body'), layer = {content: container.append(cache.layer).find('#'+cache.layerid)};
			var posLeft = container.width()/2 - layer.content.width()/2, posTop = container.height()/2 - layer.content.height()/2;
			layer.content.css('left', (posLeft<0 ? 0 : posLeft) + 'px').css('bottom', (posTop<0 ? 0 : posTop) + 'px');

			//对象压栈
			dialogs.stack.push(layer);
			layer.name = cache.name;
			
			//方法和事件绑定
			layer.show = function(){if(typeof options.show == 'function'){options.show(layer);};dialogs.show(layer);};
			layer.resize = function(){if(typeof options.resize == 'function'){options.resize(layer);};dialogs.resize(layer);};
			layer.focus = function(){if(typeof options.focus == 'function'){options.focus(layer);};dialogs.focus(layer);};
			layer.blur = function(){if(typeof options.blur == 'function'){options.blur(layer);};dialogs.blur(layer);};
			if (cache.url)
			{
				layer.min = function(){if(typeof options.min == 'function'){options.min(layer);};dialogs.min(layer);};
				layer.max = function(){if(typeof options.max == 'function'){options.max(layer);};dialogs.max(layer);};
			}
			layer.close = function(){if(typeof options.close == 'function'){options.close(layer);};dialogs.close(layer);};
			
			//动作绑定
			layer.content.find('.cover').bind('click', function(){layer.focus();return false;});
			layer.content.find('.min').bind('click', function(){layer.min();return false;});
			layer.content.find('.max').bind('click', function(){layer.max();return false;});
			layer.content.find('.close').bind('click', function(){layer.close();return false;});
			if (cache.url && cache.ctrl[1])
			{
				layer.content.find('.title').bind('dblclick', function(){layer.max();return false;}).disableSelection();
			}
			else
			{
				layer.content.find('.title').bind('click', function(){layer.content.find('input.ok').focus();return false;}).disableSelection();
			}
			
			layer.content.find('input.ok').bind('click', function(){if(typeof options.ok == 'function'){options.ok(layer);};});
			layer.content.find('input.cancle').bind('click', function(){if(typeof options.cancle == 'function'){options.cancle(layer);};});
			
			//拖动支持
			layer.content.draggable
			({
				scroll : false,
				handle : layer.content.find('.title,.cover'),
				cancel : '.dialog .title div',
				start  : function(){layer.focus();layer.content.find('.cover').show();},
				stop   : function(){layer.content.find('.cover').hide(); layer.content.find('input.ok').focus();}
			});
			
			//刷新框架尺寸
			layer.resize();
			
			//触发创建完成事件
			if(typeof options.create == 'function'){options.create(layer);};
			
			//创建完成自动显示
			layer.show();
			
			return cache;
		};
		
		dialogs.find = function(name)
		{
			if (name) for (var i=0; i<dialogs.stack.length; i++)
			{
				if (dialogs.stack[i].name == name) return dialogs.stack[i];
			}
			
			return null;
		};
		
		dialogs.show = function(layer)
		{
			layer.content.show();
			
			//显示时自动聚焦
			layer.focus();
			
			return layer;
		};
		
		dialogs.resize = function(layer)
		{
			var panel = layer.content.find('.panel'),
			padding = panel.innerHeight() - panel.height();
			
			layer.content.find('.panel').height(layer.content.height() - layer.content.find('.title').height() - padding);
			
			return layer;
		};
		
		dialogs.focus = function(layer)
		{
			if (dialogs.toper == layer)
			{
				return layer;
			}
			
			if (dialogs.toper)
			{
				dialogs.toper.blur();
			}
			
			layer.content.css('z-index', ++dialogs.zmax);
			layer.content.find('.cover').hide();
			layer.content.find('input.ok').focus();
			
			dialogs.toper = layer;
			
			return layer;
		};
		
		dialogs.blur = function(layer)
		{
			dialogs.toper = null;
			
			layer.content.find('.cover').show();
			
			return layer;
		};
		
		dialogs.min = function(layer)
		{
			//最小化前自动失焦
			layer.blur();
			
			layer.content.hide();
			
			//最小化后自动聚焦新层
			dialogs.autofocus();
			
			return layer;
		};
		
		dialogs.max = function(layer)
		{
			//第一次最大化前初始化缓存
			if (!layer.lastOffset)
			{
				var container = $('body');
				layer.lastOffset = container.offset(); 
				layer.lastOffset.left += 8;
				layer.lastOffset.top += 5;
				layer.lastWidth = container.width()-16;
				layer.lastHeight = container.height()-13;
				layer.dragStatus = false;
			}
			
			//先暂存当前的状态值
			var offset = layer.content.offset();
			var width = layer.content.width();
			var height = layer.content.height();
			var status = !layer.content.draggable("option", "disabled");
			
			//应用已缓存的上次状态值
			layer.content.css({left:layer.lastOffset.left, top:layer.lastOffset.top});
			layer.content.width(layer.lastWidth);
			layer.content.height(layer.lastHeight);
			layer.content.draggable("option", "disabled", !layer.dragStatus);
			
			//用暂存的状态值更新缓存
			layer.lastOffset = offset;
			layer.lastWidth = width;
			layer.lastHeight = height;
			layer.dragStatus = status;
			
			//刷新框架尺寸
			layer.resize();
			
			return layer;
		};
		
		dialogs.close = function(layer)
		{
			var names = [];
			for (var i=0; i<dialogs.names.length; i++)
			{
				if (dialogs.names[i] != layer.name) names.push(dialogs.names[i]);
			}
			dialogs.names = names;
			
			var stack = [];
			for (var i=0; i<dialogs.stack.length; i++)
			{
				if (dialogs.stack[i] != layer) stack.push(dialogs.stack[i]);
			}
			dialogs.stack = stack;
			
			//关闭前自动失焦
			layer.blur();
			
			$('#dialog_mask').remove();
			layer.content.remove();
			
			//关闭后自动聚焦新层
			dialogs.autofocus();
			
			return layer;
		};
		
		dialogs.autofocus = function()
		{
			var layer = null;
			
			for (var i=0,z=0; i<dialogs.stack.length; i++)
			{
				if (dialogs.stack[i].content.css('display') == 'none')
				{
					continue;
				}
				
				var zindex = dialogs.stack[i].content.css('z-index');
				if (zindex > z)
				{
					z = zindex;
					layer = dialogs.stack[i];
				}
			}
			
			if (layer) layer.focus();
		};
	}
	
	/***
	 * Tip Tools
	*/
	
	$$.alert = function(args)
	{//(text, ok, icon, title)
		$$.dialogs.open
		({
		 	name:'alert',
			text:args.text,
			icon:args.icon,
			ok:function(layer)
			{
				if(typeof args.ok == 'function') args.ok(layer);
				layer.close();
			},
			title:args.title||'系统提示',
			model:true
		});
	};
	
	$$.confirm = function(args)
	{//(text, ok, icon, title)
		$$.dialogs.open
		({
			name:'confirm',
			text:args.text,
			icon:args.icon,
			ok:function(layer)
			{
				if(typeof args.ok == 'function') args.ok(layer);
				layer.close();
			},
			cancle:function(layer)
			{
				if(typeof args.cancle == 'function') args.cancle(layer);
				layer.close();
			},
			title:args.title||'系统提示',
			model:true
		});
	};
	
	$$.loading = top.$$ ? top.$$.loading : function(options)
	{
		if (top.loadingTimer)
		{
			top.clearTimeout(top.loadingTimer);
			top.loadingTimer = 0;
		}
		
		if (typeof options == 'string')
		{
			options = {icon:true,text:options};
		}
		
		var cache =
		{
			icon: options.icon===false ? false : true,
			text: options.text || ''
		};
		
		var layer = $('body').find('#loading');
		if (!layer.get(0))
		{
			layer = $('body').append('<div id="loading"><div></div><span>'+options.text+'</span></div>').find('#loading');
		}
		
		if (cache.icon)
		{
			layer.find('span').css('background-position', 'left center').css('padding-left', '16px');
		}
		else
		{
			layer.find('span').css('background-position', '-30px 0').css('padding-left','6px');
		}
		layer.find('span').html(cache.text);
		layer.show();
		
		if (typeof(options.hide) == 'number')
		{
			top.loadingTimer = top.setTimeout(function()
			{
				layer.hide();
				top.loadingTimer = 0;
			},
			options.hide*1000);
		}
	};
	
	/***
	 * Ajax Tools
	*/
	
	$$.post = function(url, data, callback, loading)
	{
		loading = loading === false ? false : true;
		
		if (loading) $$.loading('执行中...');
		
		$.ajax
		({
			type    : 'post',
			url     : url,
			cache   : false,
			data    : data,
			success : function(data, textStatus)
			{
				var a = data ? data.split('|') : ['无效的服务器响应。'];
				if( a[0] == 'YES' )
				{
					if (loading) $$.loading({text:'执行成功。', icon:false, hide:3});
					if(typeof(callback) == 'function') callback();
				}
				else if ( a[0] == 'ERR' )
				{
					if (loading) $$.loading({hide:0});
					if (a[1]) $$.alert({text:a[1], ok:function(){if (a[2]) $('#'+a[2]).focus();}, icon:'ERR', title:'错误提示'});
				}
				else
				{
					if (loading) $$.loading({hide:0});
					if(typeof(callback) == 'function') callback(data);
				}
			},
			error   : function(XMLHttpRequest, textStatus, errorThrown)
			{
				if (loading) $$.loading({hide:0});
				alert(XMLHttpRequest.responseText);
			}
			
		});
	};
	
	$$.get = function(url, callback, loading)
	{
		loading = loading === false ? false : true;
		
		if (loading) $$.loading('执行中...');
		
		$.ajax
		({
			type    : 'get',
			url     : url,
			cache   : false,
			success : function(data, textStatus)
			{
				var a = data ? data.split('|') : ['无效的服务器响应。'];
				if( a[0] == 'YES' )
				{
					if (loading) $$.loading({text:'执行成功。', icon:false, hide:3});
					if(typeof(callback) == 'function') callback();
				}
				else if ( a[0] == 'ERR' )
				{
					if (loading) $$.loading({hide:0});
					if (a[1]) $$.alert({text:a[1], ok:function(){if (a[2]) $('#'+a[2]).focus();}, icon:'ERR', title:'错误提示'});
				}
				else
				{
					if (loading) $$.loading({hide:0});
					if(typeof(callback) == 'function') callback(data);
				}
			},
			error   : function(XMLHttpRequest, textStatus, errorThrown)
			{
				if (loading) $$.loading({hide:0});
				alert(XMLHttpRequest.responseText);
			}
		});
	};
	
	/***
	 * Other Tools
	*/
	
	$$.redirect = function(url)
	{
		location.href = url ? location.pathname + url : location.href;
	};
	
	$$.target = function(target)
	{
		if (target)
		{
			//location.href = location.href.replace(/#.*$/, '') + '#' + target;
			location.hash = target;
		}
		else
		{
			return location.hash;//location.href.replace(/^[^#]+#(.*)$/, '$1');
		}
	};
	
	$$.fullscreen = function(selector)
	{
		var img = $(selector),  win = $(window),
		imgW = img.width(), imgH = img.height();
		
		var resize = function()
		{
			var winW = win.width(), winH = win.height();
			var W, H;
			
			if (imgW/imgH < winW/winH)
			{
				img.width(W = winW);
				img.height(H = winW/imgW*imgH);
			}
			else
			{
				img.width(W = imgW/imgH*winH);
				img.height(H = winH);
			}
			
			img.css('left', winW-W+'px').css('top', winH-H+'px');
		};
		
		resize(); img.show();
		
		win.bind('resize', resize);
	};
	
	$$.selectval = function(selector)
	{
		$val = '', dot = '';
		$(selector).each(function(){$val += dot + $(this).val(); dot = ',';});
		return $val;
	};
	
	$$.filesUploadLayer = top.$$ ? top.$$.filesUploadLayer :
	{
		open: function(param)
		{
			var callback = param.callback || '';
			var type = param.type || 'image';
			var dir  = param.dir || 'content';
			$$.dialogs.open
			({
				name: param.name || 'filesUploadLayer',
				title: param.title || '添加文件',
				url: '?mode=files.upload&callback='+callback+'&type='+type+'&dir='+dir,
				width: param.width || 550,
				height: 450,
				model: true,
				ctrl: [0, 0, 1]
			});
			
			this.editor = param.editor;
			this.opener = param.opener || top;
		},
		close: function()
		{
			$$.dialogs.find('filesUploadLayer').close();
		},
		callbackEditor: function(param)
		{
			var str;
			if (param.image)
			{
				str = '<img' + (param.title ? ' atl="'+param.title+'"' : '') + (param.align ? ' align="'+obj.align+'"' : '') + ' src="'+param.url+'" />';
			}
			else 
			{
				str = '<a key="'+param.id+'" class="fcattached" target="_blank" href="'+param.pipe+'" rel="nofollow">' + param.title + '<span></span></a>';	
			}
			this.editor.insertHtml(str);
			//this.close();
		}
	};
	
	$$.editor = function(options)
	{
		/***
		 * target:	目标选择器
		 * mode:	调用模式[全部, 简单, 自定义] , 默认为 1 [1, 2, 3]
		 * items:	选择插件参数， 数组格式[... , ...]
		 * css:		编辑器内部CSS样式文件
		*/
		if (typeof options == 'string')
		{
			options = {target:options};
		}
		if (!options) options = {};
		var cache = 
		{
			target: options.target,
			mode: options.mode || 1,
			items: options.items || '',
			css: options.css
		};
		//关闭原有上传功能
		var config = {cssPath:cache.css};
		config.resizeType = 1;
		config.syncType = '';
		config.allowFileUpload = true;
		config.allowMediaUpload = false;
		config.allowFlashUpload = false;
		config.allowImageUpload = false;
		//关闭标签过滤
		config.filterMode = false;
		if (cache.mode == 1)
		{
			//默认模式
			config.items =
			[
				'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
				'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock',
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'strikethrough', 'lineheight', 'removeformat', '|', 'fcimage', 'fcattached', 'flash', 'media',
				'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'
			];
			//创建插件
			if (!KindEditor.lang('fcimage'))
			{
				KindEditor.plugin('fcimage', function(K)
				{
					var self = this, name = 'fcimage';
					self.clickToolbar(name, function()
					{
						 $$.filesUploadLayer.open({type:'image',callback:'$$.filesUploadLayer.callbackEditor',editor:self});
					});
				});
				KindEditor.lang({fcimage:'插入图片'});
			}
			if (!KindEditor.lang('fcattached'))
			{
				KindEditor.plugin('fcattached', function(K)
				{
					var self = this, name = 'fcattached';
					self.clickToolbar(name, function()
					{
						 $$.filesUploadLayer.open({type:'file',callback:'$$.filesUploadLayer.callbackEditor',editor:self});
					});
				});
				KindEditor.lang({fcattached:'插入附件'});
			}
		}
		else if (cache.mode == 2)
		{
			//简洁模式
			config.items =
			[
				'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'removeformat', '|', 'emoticons', 'link'
			];
		}
		else if (cache.mode == 3) config.items = cache.items //自定义模式
		
		return KindEditor.create(cache.target, config);
	};
	
	$$.tooltip = function(selector)
	{
		//将后面的Easy Tooltop封装到这里来
	};
	
	window.$$ = $$;
	
})(jQuery);

/**
 * Easy Tooltip 1.0 - jQuery plugin
 * written by Alen Grakalic	
 * http://cssglobe.com/post/4380/easy-tooltip--jquery-plugin
 *
 * Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * Edit by Alan
 * http://www.fcontex.com
 *
 * Built for jQuery library
 * http://jquery.com
 **/
 
(function($){$.fn.easyTooltip = function(options)
{
	var defaults =
	{	
		xOffset: 20,		
		yOffset: 20,
		tooltipId: "easyTooltip",
		clickRemove: false,
		content: "",
		useElement: ""
	};
	
	var options = $.extend(defaults, options); 
	var content;
	
	this.each(function()
	{
		var title = $(this).attr("title");
		$(this).hover(function(e)
		{
			content = (options.content != "") ? options.content : title;
			content = (options.useElement != "") ? $("#" + options.useElement).html() : content;
			$(this).attr("title","");
			if (content != "" && content != undefined)
			{
				$("body").append("<div id='"+ options.tooltipId +"'>"+ content +"</div>");
				$("#" + options.tooltipId)
					.css("position","absolute")
					.css("top",(e.pageY - options.yOffset) + "px")
					.css("left",(e.pageX + options.xOffset) + "px")
					.css("display","none")
					.fadeIn("fast");
			}
		}, function()
		{
			$("#" + options.tooltipId).remove();
			$(this).attr("title",title);
		});
		$(this).mousemove(function(e)
		{
			$("#" + options.tooltipId)
				.css("top",(e.pageY - options.yOffset) + "px")
				.css("left",(e.pageX + options.xOffset) + "px");
		});
		if(options.clickRemove)
		{
			$(this).mousedown(function(e)
			{
				$("#" + options.tooltipId).remove();
				$(this).attr("title",title);
			});
		}
	});
	
}})(jQuery);
