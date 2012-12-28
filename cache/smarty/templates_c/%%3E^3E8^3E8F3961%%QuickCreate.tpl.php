<?php /* Smarty version 2.6.11, created on 2007-08-13 11:30:03
         compiled from modules/Bugs/tpls/QuickCreate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Bugs/tpls/QuickCreate.tpl', 49, false),)), $this); ?>


<form name="bugsQuickCreate" id="bugsQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Bugs">
<input type="hidden" name="email_id" value="<?php echo $this->_tpl_vars['REQUEST']['email_id']; ?>
">
<input type="hidden" name="account_id" value="<?php echo $this->_tpl_vars['REQUEST']['account_id']; ?>
">			
<input type="hidden" name="case_id" value="<?php echo $this->_tpl_vars['REQUEST']['acase_id']; ?>
">
<input type="hidden" name="contact_id" value="<?php echo $this->_tpl_vars['REQUEST']['contact_id']; ?>
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
<input type="hidden" name="to_pdf" value='1'>
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="<?php echo $this->_tpl_vars['ASSIGNED_USER_ID']; ?>
" />



<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('BugsQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('BugsQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Bugs';" value="  <?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_LABEL']; ?>
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
	<th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_BUG_INFORMATION']; ?>
</slot></h4></th>
	</tr>
	<tr>
	<td valign="top" class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_SUBJECT']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td width="35%"><slot><textarea name='name' cols="40" tabindex='1' rows="1"><?php echo $this->_tpl_vars['NAME']; ?>
</textarea></slot></td>
	<td class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_TYPE']; ?>
</slot></td>
	<td width="35%"><slot><select tabindex='2' name='type'><?php echo $this->_tpl_vars['TYPE_OPTIONS']; ?>
</select></slot></td>
	</tr>
	<tr>
	<td valign="top" class="dataLabel" rowspan="2" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DESCRIPTION']; ?>
</slot></td>
	<td rowspan="2" width="35%"><slot><textarea name='description' tabindex='1' cols="40" rows="4"><?php echo $this->_tpl_vars['DESCRIPTION']; ?>
</textarea></slot></td>
	<td class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PRIORITY']; ?>
</slot></td>
	<td class="dataField" nowrap width="35%"><slot><select  tabindex='2' name='priority'><?php echo $this->_tpl_vars['PRIORITY_OPTIONS']; ?>
</select></slot></td>
	</tr>
	<tr>
	<td class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['MOD']['LBL_STATUS']; ?>
</slot></td>
	<td width="35%"><slot><select tabindex='2' name='status'><?php echo $this->_tpl_vars['STATUS_OPTIONS']; ?>
</select></slot></td>
	</tr>
	</table>
	</form>
<script>
	<?php echo $this->_tpl_vars['additionalScripts']; ?>

</script>