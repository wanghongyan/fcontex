<ul>
	<?php
	//主菜单
	$moduleDefaultIcon = URL_SKIN.'module.default.png';
	foreach ($A->loadconfig('system.modules') as $name => $module)
	{
	?>
	<li module="<?php echo $name; ?>" title="<?php echo $module['name']; ?>" onmouseover="$(this).addClass('hover');" onmouseout="$(this).removeClass('hover');" onclick="naviClick(this);">
		<img src="<?php echo isset($module['icon']) ? DIR_SITE.DIR_MODULE.'/'.$name.'/icons/'.$module['icon'] : $moduleDefaultIcon; ?>" />
		<dl>
			<?php
			//子菜单
			$i = -45;
			foreach ($module['menus'] as $text => $value)
			{
			?>
			<dd navigate="<?php echo $name.'/'.$value['url']; ?>" onmouseover="$(this).addClass('hover');" onmouseout="$(this).removeClass('hover');" onclick="menuClick(this);">
				<img src="<?php echo !empty($value['icon']) ? DIR_SITE.DIR_MODULE.'/'.$name.'/icons/'.$value['icon'] : $moduleDefaultIcon; ?>" />
				<div class="icon"><img src="<?php echo !empty($value['icon']) ? DIR_SITE.DIR_MODULE.'/'.$name.'/icons/'.$value['icon'] : $moduleDefaultIcon; ?>" /></div>
				<div class="text"><span class="L"><?php echo $text; ?></span><span class="R"></span><span class="T"><?php echo DIR_SITE.DIR_MODULE.'/'.($name=='system' ? $name : $name.'/system').'/?'.$value['url']; ?></span></div>
			</dd>
			<?php
				$i -= 45;
			}
			?>
		</dl>
	</li>
	<?php
	}
	?>
</ul>