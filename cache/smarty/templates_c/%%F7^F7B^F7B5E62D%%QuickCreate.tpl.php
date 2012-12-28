<?php /* Smarty version 2.6.11, created on 2012-07-30 16:22:27
         compiled from modules/ProjectTask/tpls/QuickCreate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/ProjectTask/tpls/QuickCreate.tpl', 49, false),)), $this); ?>


<form name="projectTaskQuickCreate" id="projectTaskQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="ProjectTask">
<input type="hidden" name="project_id" value="<?php echo $this->_tpl_vars['REQUEST']['project_id']; ?>
">
<input type="hidden" name="contact_name" value="<?php echo $this->_tpl_vars['REQUEST']['contact_name']; ?>
">
<input type="hidden" name="email_id" value="<?php echo $this->_tpl_vars['REQUEST']['email_id']; ?>
">
<input type="hidden" name="bug_id" value="<?php echo $this->_tpl_vars['REQUEST']['bug_id']; ?>
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



<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('ProjectTaskQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('ProjectTaskQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='ProjectTask';" value="  <?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_LABEL']; ?>
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
	<th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PROJECTTYPE_INFORMATION']; ?>
</slot></h4></th>
	</tr>
	<tr>
	<td valign="top" class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_NAME']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td><slot><input name="name" tabindex="1" size="35" maxlength="50" type="text" value=""></slot></td>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_STATUS']; ?>
</slot></td>
	<td><slot><select tabindex='1' name='status'><?php echo $this->_tpl_vars['STATUS_OPTIONS']; ?>
</select></slot></td>
	</tr>
	<tr>
	<td valign="top" class="dataLabel" rowspan="2"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DESCRIPTION']; ?>
</slot></td>
	<td rowspan="2"><slot><textarea name='description' tabindex='3' cols="50" rows="4"><?php echo $this->_tpl_vars['DESCRIPTION']; ?>
</textarea></slot></td>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PERCENT_COMPLETE']; ?>
</slot></td>
	<td class="dataField"><slot><input name="percent_complete" type="text" tabindex="2"  size="4" maxlength="3"
	/></slot></td>
	</tr>
	<tr>
	<?php if ($this->_tpl_vars['REQUEST']['project_id'] != ''): ?>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PARENT_ID']; ?>
</slot></td>
	<td class="dataField"><slot><?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
<input id='project_name' name='project_name' type="hidden" value='<?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
'><input id='parent_id' name='parent_id' type="hidden" value='<?php echo $this->_tpl_vars['REQUEST']['parent_id']; ?>
'>&nbsp;</slot></td>
	<?php else: ?>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PARENT_ID']; ?>
</slot></td>
	<td class="dataField"><slot><input type="text" class="sqsEnabled" tabindex="2" autocomplete="off" name="parent_name" id="parent_name"
	value="<?php echo $this->_tpl_vars['parent_name']; ?>
" tabindex="16" /><input type="hidden" name="parent_id" id="parent_id"
	value="<?php echo $this->_tpl_vars['parent_id']; ?>
" />&nbsp;<input
	title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_TITLE']; ?>
"
	accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_KEY']; ?>
" type="button" class="button"
	value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
" name="change_parent" tabindex="2"
	onclick='open_popup("Project", 600, 400, "", true, false, <?php echo $this->_tpl_vars['encoded_parent_popup_request_data']; ?>
);'
	/></slot></td>	<?php endif; ?>
	</tr>
	</table>
	</form>
<script>
	<?php echo $this->_tpl_vars['additionalScripts']; ?>

</script>