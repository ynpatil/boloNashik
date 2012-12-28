<?php /* Smarty version 2.6.11, created on 2012-04-05 15:03:28
         compiled from modules/Configurator/EditView.tpl */ ?>


<BR>
<form name="ConfigureSettings" enctype='multipart/form-data' method="POST" action="index.php?action=EditView&module=Configurator" onSubmit="return (add_checks(document.ConfigureSettings) && check_form('ConfigureSettings'));">
<span class='error'><?php echo $this->_tpl_vars['error']['main']; ?>
</span>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
			
	<td style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button"  type="submit" name="save" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		&nbsp;<input title="<?php echo $this->_tpl_vars['MOD']['LBL_SAVE_BUTTON_TITLE']; ?>
"  class="button"  type="submit" name="restore" value="  <?php echo $this->_tpl_vars['MOD']['LBL_RESTORE_BUTTON_LABEL']; ?>
  " > 
		&nbsp;<input title="<?php echo $this->_tpl_vars['MOD']['LBL_CANCEL_BUTTON_TITLE']; ?>
"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " > </td>	
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['DEFAULT_SYSTEM_SETTINGS']; ?>
</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LIST_ENTRIES_PER_LISTVIEW']; ?>
: </td>
		<td  class="dataField">
			<input type='text' size='4' name='list_max_entries_per_page' value='<?php echo $this->_tpl_vars['config']['list_max_entries_per_page']; ?>
'>
		</td>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LIST_ENTRIES_PER_SUBPANEL']; ?>
: </td>
		<td  class="dataField">
			<input type='text' size='4' name='list_max_entries_per_subpanel' value='<?php echo $this->_tpl_vars['config']['list_max_entries_per_subpanel']; ?>
'>
		</td>
	</tr>
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['DISPLAY_RESPONSE_TIME']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['calculate_response_time'] )): ?>
			<?php $this->assign('calculate_response_time_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('calculate_response_time_checked', ''); ?>
		<?php endif; ?>
		<td class="dataField"><input type='hidden' name='calculate_response_time' value='false'><input name='calculate_response_time'  type="checkbox" value="true" <?php echo $this->_tpl_vars['calculate_response_time_checked']; ?>
></td>
	
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['DISPLAY_LOGIN_NAV']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['login_nav'] )): ?>
			<?php $this->assign('login_nav_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('login_nav_checked', ''); ?>
		<?php endif; ?>
		<td class="dataField"><input type='hidden' name='login_nav' value='false'><input name='login_nav'  type="checkbox" value="true" <?php echo $this->_tpl_vars['login_nav_checked']; ?>
></td>
	</tr>
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LOCK_HOMEPAGE']; ?>
: </td>
		<td  class="dataField">
			<?php if (! empty ( $this->_tpl_vars['config']['lock_homepage'] )): ?>
				<?php $this->assign('lock_homepage_checked', 'CHECKED'); ?>
			<?php else: ?>
				<?php $this->assign('lock_homepage_checked', ''); ?>
			<?php endif; ?>
			<input type='hidden' name='lock_homepage' value='false'>
			<input type='checkbox' name='lock_homepage' value='true' <?php echo $this->_tpl_vars['lock_homepage_checked']; ?>
>
		</td>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LOCK_SUBPANELS']; ?>
: </td>
		<td  class="dataField">
			<?php if (! empty ( $this->_tpl_vars['config']['lock_subpanels'] )): ?>
				<?php $this->assign('lock_subpanels_checked', 'CHECKED'); ?>
			<?php else: ?>
				<?php $this->assign('lock_subpanels_checked', ''); ?>
			<?php endif; ?>
			<input type='hidden' name='lock_subpanels' value='false'>
			<input type='checkbox' name='lock_subpanels' value='true' <?php echo $this->_tpl_vars['lock_subpanels_checked']; ?>
>
		</td>
	</tr>
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['MAX_DASHLETS']; ?>
: </td>
		<td  class="dataField">
			<input type='text' size='4' name='max_dashlets_homepage' value='<?php echo $this->_tpl_vars['config']['max_dashlets_homepage']; ?>
'>
		</td>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_USE_REAL_NAMES']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['use_real_names'] )): ?>
			<?php $this->assign('use_real_names', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('use_real_names', ''); ?>
		<?php endif; ?>
		<td class="dataField">
			<input type='hidden' name='use_real_names' value='false'>
			<input name='use_real_names'  type="checkbox" value="true" <?php echo $this->_tpl_vars['use_real_names']; ?>
>
		</td>
	</tr>
</table>
</td></tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['IMAGES']; ?>
</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel" nowrap><?php echo $this->_tpl_vars['MOD']['CURRENT_LOGO']; ?>
: </td>
		<td  class="dataField">
			<img src='include/images/company_logo.png' height="40" width="212">
		</td>
	</tr>
	
	<tr>
		<td  class="dataLabel" nowrap><?php echo $this->_tpl_vars['MOD']['NEW_LOGO']; ?>
: </td>
		<td  class="dataField">
			<input type='file' name='company_logo'>
		</td>
	</tr>














</table>
</td>
</tr>
</table>
<br>
<?php if (! empty ( $this->_tpl_vars['settings']['system_ldap_enabled'] )): ?>
		<?php $this->assign('system_ldap_enabled_checked', 'CHECKED'); ?>
		<?php $this->assign('ldap_display', 'inline'); ?>
	<?php else: ?>
		<?php $this->assign('system_ldap_enabled_checked', ''); ?>
		<?php $this->assign('ldap_display', 'none'); ?>
<?php endif; ?>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan='3'><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_TITLE']; ?>
</h4></th>
	</tr>
	<tr><td width="15%">
	<?php echo $this->_tpl_vars['MOD']['LBL_LDAP_ENABLE']; ?>

	</td><td width="35%"><input type='hidden' name="system_ldap_enabled" value="0" ><input name="system_ldap_enabled" value="1" class="checkbox" tabindex='1' type="checkbox" <?php echo $this->_tpl_vars['system_ldap_enabled_checked']; ?>
 onclick='toggleDisplay("ldap_display")'></td><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td colspan='4'>
	<table  cellspacing='0' cellpadding='0' id='ldap_display' style='display:<?php echo $this->_tpl_vars['ldap_display']; ?>
' width='100%'>	
	<tr>
		<td class="dataLabel" valign='top' nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_SERVER_HOSTNAME']; ?>
</td><?php echo $this->_tpl_vars['settings']['proxy_host']; ?>

		<td align="left" class="dataField" valign='top'><input name="ldap_hostname" size='25' type="text" value="<?php echo $this->_tpl_vars['settings']['ldap_hostname']; ?>
"></td>
		<td align="left" class="dataField" valign='top'><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_SERVER_HOSTNAME_DESC']; ?>
</td>
	</tr>
	<tr>
		<td class="dataLabel" valign='middle' nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_BASE_DN']; ?>
</td>
		<td align="left" class="dataField" valign='middle'><input name="ldap_base_dn" size='35' type="text" value="<?php echo $this->_tpl_vars['settings']['ldap_base_dn']; ?>
"></td>
		 <td align="left" class="dataField" valign='middle'><em><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_BASE_DN_DESC']; ?>
</em></td>
	</tr>
	<tr>
		<td class="dataLabel" valign='top' nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_BIND_ATTRIBUTE']; ?>
</td>
		<td align="left" class="dataField" valign='top'><input name="ldap_bind_attr" size='25' type="text" value="<?php echo $this->_tpl_vars['settings']['ldap_bind_attr']; ?>
"> </td>
		<td align="left" class="dataField" valign='top'><em><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_BIND_ATTRIBUTE_DESC']; ?>
</em></td>
	</tr>
	
	<tr>
		<td class="dataLabel" valign='middle' nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_LOGIN_ATTRIBUTE']; ?>
</td>
		<td align="left" class="dataField" valign='middle'><input name="ldap_login_attr" size='25' type="text" value="<?php echo $this->_tpl_vars['settings']['ldap_login_attr']; ?>
"></td>
		 <td align="left" class="dataField" valign='middle'><em><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_LOGIN_ATTRIBUTE_DESC']; ?>
</em></td>
	</tr>

	<tr>
		<td class="dataLabel" valign='top'nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_ADMIN_USER']; ?>
</td>
		<td align="left" class="dataField" valign='top'><input name="ldap_admin_user" size='35' type="text" value="<?php echo $this->_tpl_vars['settings']['ldap_admin_user']; ?>
"></td>
		<td align="left" class="dataField" valign='top'><em><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_ADMIN_USER_DESC']; ?>
</em></td>
	</tr>
	<tr>
		<td class="dataLabel" valign='middle' nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_ADMIN_PASSWORD']; ?>
</td>
		<td align="left" class="dataField" valign='middle'><input name="ldap_admin_password" size='35' type="password" value="<?php echo $this->_tpl_vars['settings']['ldap_admin_password']; ?>
"> </td>
		<td align="left" class="dataField" valign='top'><em><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_ADMIN_PASSWORD_DESC']; ?>
</em></td>
	</tr>
	

	<tr>
		<td class="dataLabel" valign='top' nowrap><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_AUTO_CREATE_USERS']; ?>
</td>
		<?php if (! empty ( $this->_tpl_vars['settings']['ldap_auto_create_users'] )): ?>
			<?php $this->assign('ldap_auto_create_users_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('ldap_auto_create_users_checked', ''); ?>
		<?php endif; ?>
		<td align="left" class="dataField" valign='top'><input type='hidden' name='ldap_auto_create_users' value='0'><input name="ldap_auto_create_users" value="1" class="checkbox" type="checkbox" <?php echo $this->_tpl_vars['ldap_auto_create_users_checked']; ?>
></td>
		<td align="left" class="dataField" valign='top'> <em><?php echo $this->_tpl_vars['MOD']['LBL_LDAP_AUTO_CREATE_USERS_DESC']; ?>
</em></td>
	</tr>

	
</table>
</td></tr></table></td></tr></table>
<BR>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_TITLE']; ?>
</h4></th>
	</tr><tr>
	<td width="15%" class="dataLabel" valign='middle'><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_ON']; ?>
</td>
		<?php if (! empty ( $this->_tpl_vars['settings']['proxy_on'] )): ?>
		<?php $this->assign('proxy_on_checked', 'CHECKED'); ?>
	<?php else: ?>
		<?php $this->assign('proxy_on_checked', ''); ?>
	<?php endif; ?>
	<td width="85%" align="left" class="dataField" valign='middle' colspan='3'><input type='hidden' name='proxy_on' value='0'><input name="proxy_on" value="1" class="checkbox" tabindex='1' type="checkbox" <?php echo $this->_tpl_vars['proxy_on_checked']; ?>
 onclick='toggleDisplay("proxy_config_display")'> <em><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_ON_DESC']; ?>
</em></td>
	</tr><tr>
	<td colspan="4">
	<div id="proxy_config_display" style='display:<?php echo $this->_tpl_vars['PROXY_CONFIG_DISPLAY']; ?>
'>
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_HOST']; ?>
<span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></td>
		<td width="35%" class="dataField"><input type="text" name="proxy_host" size="25"  value="<?php echo $this->_tpl_vars['settings']['proxy_host']; ?>
" tabindex='1' ></td>
		<td width="15%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_PORT']; ?>
<span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></td>
		<td width="35%" class="dataField"><input type="text" name="proxy_port" size="6"  value="<?php echo $this->_tpl_vars['settings']['proxy_port']; ?>
" tabindex='1' ></td>
		</tr><tr>
		<td width="15%" class="dataLabel" valign='middle'><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_AUTH']; ?>
</td>
	<?php if (! empty ( $this->_tpl_vars['settings']['proxy_auth'] )): ?>
		<?php $this->assign('proxy_auth_checked', 'CHECKED'); ?>
	<?php else: ?>
		<?php $this->assign('proxy_auth_checked', ''); ?>
	<?php endif; ?>
		<td width="35%" align="left" class="dataField" valign='middle' ><input type='hidden' name='proxy_auth' value='0'><input name="proxy_auth" value="1" class="checkbox" tabindex='1' type="checkbox" <?php echo $this->_tpl_vars['proxy_auth_checked']; ?>
 onclick='toggleDisplay("proxy_auth_display")'> </td>
		</tr></table>
		
		<div id="proxy_auth_display" style='display:<?php echo $this->_tpl_vars['PROXY_AUTH_DISPLAY']; ?>
'>
		
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_USERNAME']; ?>
<span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></td>
		
		<td width="35%" class="dataField"><input type="text" name="proxy_username" size="25"  value="<?php echo $this->_tpl_vars['settings']['proxy_username']; ?>
" tabindex='1' ></td>
		<td width="15%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_PROXY_PASSWORD']; ?>
<span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></td>
		<td width="35%" class="dataField"><input type="password" name="proxy_password" size="25"  value="<?php echo $this->_tpl_vars['settings']['proxy_password']; ?>
" tabindex='1' ></td>
		</tr></table>
		</div>
	</div>
  </td></tr></table>
</td></tr></table>
<BR>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_PORTAL_TITLE']; ?>
</h4></th>
	</tr><tr>
	<td width="25%" class="dataLabel" valign='middle'><?php echo $this->_tpl_vars['MOD']['LBL_PORTAL_ON']; ?>
</td>
	<?php if (! empty ( $this->_tpl_vars['settings']['portal_on'] )): ?>
		<?php $this->assign('portal_on_checked', 'CHECKED'); ?>
	<?php else: ?>
		<?php $this->assign('portal_on_checked', ''); ?>
	<?php endif; ?>
		<td width="75%" align="left" class="dataField" valign='middle'><input type='hidden' name='portal_on' value='0'><input name="portal_on" value="1" class="checkbox" tabindex='1' type="checkbox" <?php echo $this->_tpl_vars['portal_on_checked']; ?>
> <em><?php echo $this->_tpl_vars['MOD']['LBL_PORTAL_ON_DESC']; ?>
</em></td>
	</tr><tr>
	<td colspan="4">
	<div id="portal_config">
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel">&nbsp;</td>
		<td width="35%" class="dataField">&nbsp;</td>
		</tr></table>
	</div>
  </td></tr></table>
</td></tr></table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LBL_SKYPEOUT_TITLE']; ?>
</h4></th>
	</tr><tr>
	<td width="25%" class="dataLabel" valign='middle'><?php echo $this->_tpl_vars['MOD']['LBL_SKYPEOUT_ON']; ?>
</td>
	<?php if (! empty ( $this->_tpl_vars['settings']['system_skypeout_on'] )): ?>
		<?php $this->assign('system_skypeout_on_checked', 'CHECKED'); ?>
	<?php else: ?>
		<?php $this->assign('system_skypeout_on_checked', ''); ?>
	<?php endif; ?>
	<td width="75%" align="left" class="dataField" valign='middle'><input type='hidden' name='system_skypeout_on' value='0'><input name="system_skypeout_on" value="1" class="checkbox" tabindex='1' type="checkbox" <?php echo $this->_tpl_vars['system_skypeout_on_checked']; ?>
 > <em><?php echo $this->_tpl_vars['MOD']['LBL_SKYPEOUT_ON_DESC']; ?>
</em></td>
	</tr><tr>
	<td colspan="4">
	<div id="portal_config">
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel">&nbsp;</td>
		<td width="35%" class="dataField">&nbsp;</td>
		</tr></table> 
	</div>
  </td></tr></table>
</td></tr></table>



































<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['EXPORT']; ?>
</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap width="10%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['EXPORT_DELIMITER']; ?>
: </td>
		<td width="25%" class="dataField">
			<input type='text' name='export_delimiter' size="5" value='<?php echo $this->_tpl_vars['config']['export_delimiter']; ?>
'>
		</td>
		<td nowrap width="10%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['EXPORT_CHARSET']; ?>
: </td>
		<td width="25%" class="dataField">
			<select name="default_export_charset"><?php echo $this->_tpl_vars['exportCharsets']; ?>
</select>
		</td>

		<td nowrap width="10%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['DISABLE_EXPORT']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['disable_export'] )): ?>
			<?php $this->assign('disable_export_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('disable_export_checked', ''); ?>
		<?php endif; ?>
		<td width="25%" class="dataField"><input type='hidden' name='disable_export' value='false'><input name='disable_export'  type="checkbox" value="true" <?php echo $this->_tpl_vars['disable_export_checked']; ?>
></td>
		<td nowrap width="10%" class="dataLabel"><?php echo $this->_tpl_vars['MOD']['ADMIN_EXPORT_ONLY']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['admin_export_only'] )): ?>
			<?php $this->assign('admin_export_only_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('admin_export_only_checked', ''); ?>
		<?php endif; ?>
		<td width="20%" class="dataField"><input type='hidden' name='admin_export_only' value='false'><input name='admin_export_only'  type="checkbox" value="true" <?php echo $this->_tpl_vars['admin_export_only_checked']; ?>
></td>
		
	</tr>
</table>
</td></tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><?php echo $this->_tpl_vars['MOD']['ADVANCED']; ?>
</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['VERIFY_CLIENT_IP']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['verify_client_ip'] )): ?>
			<?php $this->assign('verify_client_ip_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('verify_client_ip_checked', ''); ?>
		<?php endif; ?>
		<td  class="dataField"><input type='hidden' name='verify_client_ip' value='false'><input name='verify_client_ip'  type="checkbox" value="1" <?php echo $this->_tpl_vars['verify_client_ip_checked']; ?>
></td>
	
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LOG_MEMORY_USAGE']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['log_memory_usage'] )): ?>
			<?php $this->assign('log_memory_usage_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('log_memory_usage_checked', ''); ?>
		<?php endif; ?>
		<td  class="dataField"><input type='hidden' name='log_memory_usage' value='false'><input name='log_memory_usage'  type="checkbox" value='true' <?php echo $this->_tpl_vars['log_memory_usage_checked']; ?>
></td>
		
	</tr>
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['LOG_SLOW_QUERIES']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['dump_slow_queries'] )): ?>
			<?php $this->assign('dump_slow_queries_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('dump_slow_queries_checked', ''); ?>
		<?php endif; ?>
		<td class="dataField"><input type='hidden' name='dump_slow_queries' value='false'><input name='dump_slow_queries'  type="checkbox" value='true' <?php echo $this->_tpl_vars['dump_slow_queries_checked']; ?>
></td>
	
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['SLOW_QUERY_TIME_MSEC']; ?>
: </td>
		<td  class="dataField">
			<input type='text' size='5' name='slow_query_time_msec' value='<?php echo $this->_tpl_vars['config']['slow_query_time_msec']; ?>
'>
		</td>
		
	</tr>
	<tr>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['UPLOAD_MAX_SIZE']; ?>
: </td>
		<td  class="dataField">
			<input type='text' size='8' name='upload_maxsize' value='<?php echo $this->_tpl_vars['config']['upload_maxsize']; ?>
'>
		</td>
		<td  class="dataLabel"><?php echo $this->_tpl_vars['MOD']['STACK_TRACE_ERRORS']; ?>
: </td>
		<?php if (! empty ( $this->_tpl_vars['config']['stack_trace_errors'] )): ?>
			<?php $this->assign('stack_trace_errors_checked', 'CHECKED'); ?>
		<?php else: ?>
			<?php $this->assign('stack_trace_errors_checked', ''); ?>
		<?php endif; ?>
		<td class="dataField"><input type='hidden' name='stack_trace_errors' value='false'><input name='stack_trace_errors'  type="checkbox" value='true' <?php echo $this->_tpl_vars['stack_trace_errors_checked']; ?>
></td>
	
		
		
	</tr>








	
</table>
</td></tr>
</table>
<br />
<div style="padding-top: 2px;">
<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" class="button"  type="submit" name="save" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " />
		&nbsp;<input title="<?php echo $this->_tpl_vars['MOD']['LBL_SAVE_BUTTON_TITLE']; ?>
"  class="button"  type="submit" name="restore" value="  <?php echo $this->_tpl_vars['MOD']['LBL_RESTORE_BUTTON_LABEL']; ?>
  " /> 
		&nbsp;<input title="<?php echo $this->_tpl_vars['MOD']['LBL_CANCEL_BUTTON_TITLE']; ?>
"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " />
</div>
<?php echo $this->_tpl_vars['JAVASCRIPT']; ?>

</form>