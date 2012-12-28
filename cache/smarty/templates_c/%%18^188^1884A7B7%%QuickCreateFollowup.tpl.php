<?php /* Smarty version 2.6.11, created on 2012-03-29 22:51:02
         compiled from modules/Tasks/tpls/QuickCreateFollowup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Tasks/tpls/QuickCreateFollowup.tpl', 52, false),)), $this); ?>

<form name="tasksQuickCreate" id="tasksQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Tasks">
<input type="hidden" name="record" value="">
<input type="hidden" name="lead_id" value="<?php echo $this->_tpl_vars['REQUEST']['lead_id']; ?>
">
<input type="hidden" name="contact_id" value="<?php echo $this->_tpl_vars['REQUEST']['contact_id']; ?>
">
<input type="hidden" name="contact_name" value="<?php echo $this->_tpl_vars['REQUEST']['contact_name']; ?>
">
<input type="hidden" name="email_id" value="<?php echo $this->_tpl_vars['REQUEST']['email_id']; ?>
">
<input type="hidden" name="account_id" value="<?php echo $this->_tpl_vars['REQUEST']['account_id']; ?>
">			
<input type="hidden" name="opportunity_id" value="<?php echo $this->_tpl_vars['REQUEST']['opportunity_id']; ?>
">
<input type="hidden" name="acase_id" value="<?php echo $this->_tpl_vars['REQUEST']['acase_id']; ?>
">
<input type="hidden" name="return_action" value="<?php echo $this->_tpl_vars['REQUEST']['return_action']; ?>
">
<input type="hidden" name="return_module" value="<?php echo $this->_tpl_vars['REQUEST']['return_module']; ?>
">
<input type="hidden" name="return_id" value="<?php echo $this->_tpl_vars['REQUEST']['return_id']; ?>
">
<input type="hidden" name="action" value='Save'>
<input type="hidden" name="duplicate_parent_id" value="<?php echo $this->_tpl_vars['REQUEST']['duplicate_parent_id']; ?>
">
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="<?php echo $this->_tpl_vars['ASSIGNED_USER_ID']; ?>
" />
<input type="hidden" name="to_pdf" value='1'>
<input type="hidden" name="followup_for_id" value="<?php echo $this->_tpl_vars['REQUEST']['return_id']; ?>
">	
<input type="hidden" name="isassoc_activity" value="true">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('tasksQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('tasksQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Tasks';" value="  <?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_LABEL']; ?>
  "></td>
	<td align="right" nowrap><span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span> <?php echo $this->_tpl_vars['APP']['NTC_REQUIRED']; ?>
</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_NEW_FORM_TITLE']; ?>
</slot></h4></th>
	</tr>
	<tr>
	<td valign="top" class="dataLabel" rowspan="2"><slot><?php echo $this->_tpl_vars['MOD']['LBL_SUBJECT']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td rowspan="2"><slot><textarea name='name' cols="50" tabindex='1' rows="1"><?php echo $this->_tpl_vars['NAME']; ?>
</textarea></slot></td>
	<td class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PRIORITY']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td class="dataField" nowrap width="35%"><slot><select  tabindex='2' name='priority'><?php echo $this->_tpl_vars['PRIORITY_OPTIONS']; ?>
</select></slot></td>
	</tr>
	<tr>
	<td class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_STATUS']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td width="35%"><slot><select tabindex='2' name='status'><?php echo $this->_tpl_vars['STATUS_OPTIONS']; ?>
</select></slot></td>
	</tr>
	<tr>
	<td valign="top" class="dataLabel" rowspan="2"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DESCRIPTION']; ?>
</slot></td>
	<td rowspan="2"><slot><textarea name='description' tabindex='1' cols="50" rows="4"><?php echo $this->_tpl_vars['DESCRIPTION']; ?>
</textarea></slot></td>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DUE_DATE_AND_TIME']; ?>
</slot></td>
	<td class="dataField" nowrap="nowrap"><slot>
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td nowrap="nowrap"><input name='date_due' id='jscal_field' onblur="parseDate(this, '<?php echo $this->_tpl_vars['CALENDAR_DATEFORMAT']; ?>
');" tabindex='2' maxlength='10' size='11' <?php echo $this->_tpl_vars['READONLY']; ?>
 type="text" value="<?php echo $this->_tpl_vars['DATE_DUE']; ?>
">
			<img src="themes/<?php echo $this->_tpl_vars['THEME']; ?>
/images/jscalendar.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_ENTER_DATE']; ?>
" id="jscal_trigger" align="absmiddle">&nbsp;</td>
		<td nowrap="nowrap"><input name='time_due' size='5' maxlength='5' tabindex='2' <?php echo $this->_tpl_vars['READONLY']; ?>
 type="text" value='<?php echo $this->_tpl_vars['TIME_DUE']; ?>
'><?php echo $this->_tpl_vars['DUE_TIME_MERIDIEM']; ?>
 </td>
		</tr>
		<tr>
		<td nowrap="nowrap"><span class="dateFormat"><?php echo $this->_tpl_vars['USER_DATEFORMAT']; ?>
</span></td>
		<td nowrap="nowrap"><span class="dateFormat"><?php echo $this->_tpl_vars['TIME_FORMAT']; ?>
</span></td>
		</tr>
	</table></slot>
	</td>	
	</tr>
	<tr>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_START_DATE_AND_TIME']; ?>
</slot></td>
	<td class="dataField" nowrap="nowrap"><slot>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td nowrap="nowrap"><input name='date_start' id='date_start' onblur="parseDate(this, '<?php echo $this->_tpl_vars['CALENDAR_DATEFORMAT']; ?>
');" tabindex='2' maxlength='10' size='11' <?php echo $this->_tpl_vars['READONLY']; ?>
 type="text" value="<?php echo $this->_tpl_vars['DATE_START']; ?>
"> <img src="themes/<?php echo $this->_tpl_vars['THEME']; ?>
/images/jscalendar.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_ENTER_DATE']; ?>
"  id="date_start_trigger" align="absmiddle">&nbsp;</td>
		<td nowrap="nowrap"><input name='time_start' size='5' maxlength='5' tabindex='2' <?php echo $this->_tpl_vars['READONLY']; ?>
 type="text" value='<?php echo $this->_tpl_vars['TIME_START']; ?>
'><?php echo $this->_tpl_vars['START_TIME_MERIDIEM']; ?>
 </td>
		</tr>
		<tr>
		<td nowrap="nowrap"><span class="dateFormat"><?php echo $this->_tpl_vars['USER_DATEFORMAT']; ?>
</span></td>
		<td nowrap="nowrap"><span class="dateFormat"><?php echo $this->_tpl_vars['TIME_FORMAT']; ?>
</span></td>
		</tr>
		</table></slot>
	</td>
	
	</tr>
	</table>
	</form>
<script type="text/javascript">
<?php echo '
Calendar.setup ({
	inputField : "jscal_field", ifFormat : "';  echo $this->_tpl_vars['CALENDAR_DATEFORMAT'];  echo '", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
});
Calendar.setup ({
	inputField : "date_start", ifFormat : "';  echo $this->_tpl_vars['CALENDAR_DATEFORMAT'];  echo '", showsTime : false, button : "date_start_trigger", singleClick : true, step : 1
});
'; ?>

	<?php echo $this->_tpl_vars['additionalScripts']; ?>

</script>