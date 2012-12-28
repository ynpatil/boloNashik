<?php /* Smarty version 2.6.11, created on 2012-03-15 12:31:22
         compiled from modules/Contacts/tpls/QuickCreate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Contacts/tpls/QuickCreate.tpl', 59, false),)), $this); ?>


<form name="contactsQuickCreate" id="contactsQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Contacts">
<input type="hidden" name="email_id" value="<?php echo $this->_tpl_vars['REQUEST']['email_id']; ?>
">
<input type="hidden" name="account_id" value="<?php echo $this->_tpl_vars['REQUEST']['account_id']; ?>
">			
<input type="hidden" name="case_id" value="<?php echo $this->_tpl_vars['REQUEST']['acase_id']; ?>
">
<input type="hidden" name="bug_id" value="<?php echo $this->_tpl_vars['REQUEST']['bug_id']; ?>
">
<input type="hidden" name="opportunity_id" value="<?php echo $this->_tpl_vars['REQUEST']['opportunity_id']; ?>
">
<input type="hidden" name="reports_to_id" value="<?php echo $this->_tpl_vars['REQUEST']['contact_id']; ?>
">
<input type="hidden" name="return_action" value="<?php echo $this->_tpl_vars['REQUEST']['return_action']; ?>
">
<input type="hidden" name="return_module" value="<?php echo $this->_tpl_vars['REQUEST']['return_module']; ?>
">
<input type="hidden" name="return_id" value="<?php echo $this->_tpl_vars['REQUEST']['return_id']; ?>
">
<input type="hidden" name="primary_address_street" value="<?php echo $this->_tpl_vars['REQUEST']['primary_address_street']; ?>
">
<input type="hidden" name="primary_address_city" value="<?php echo $this->_tpl_vars['REQUEST']['primary_address_city']; ?>
">
<input type="hidden" name="primary_address_state" value="<?php echo $this->_tpl_vars['REQUEST']['primary_address_state']; ?>
">
<input type="hidden" name="primary_address_country" value="<?php echo $this->_tpl_vars['REQUEST']['primary_address_country']; ?>
">
<input type="hidden" name="primary_address_postalcode" value="<?php echo $this->_tpl_vars['REQUEST']['primary_address_postalcode']; ?>
">
<input type="hidden" name="phone_work" value="<?php echo $this->_tpl_vars['REQUEST']['phone_work']; ?>
">
<input type="hidden" name="action" value='Save'>
<input type="hidden" name="is_ajax_call" value='1'>
<input type="hidden" name="to_pdf" value='1'>

<input type="hidden" name="duplicate_parent_id" value="<?php echo $this->_tpl_vars['REQUEST']['duplicate_parent_id']; ?>
">
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="<?php echo $this->_tpl_vars['ASSIGNED_USER_ID']; ?>
" />



<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('ContactsQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('ContactsQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0'; this.form.action.value='EditView'; this.form.module.value='Contacts';" value="  <?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_LABEL']; ?>
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
	<th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_CONTACT_INFORMATION']; ?>
</slot></h4></th>
	</tr>
	<tr>
	<td  class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_FIRST_NAME']; ?>
</slot></td>
	<td class="dataField" nowrap><slot><select  tabindex='1' name='salutation'><?php echo $this->_tpl_vars['SALUTATION_OPTIONS']; ?>
</select>&nbsp;<input name='first_name' tabindex='1' size='11' maxlength='25' type="text" value=""></slot></td>
	<td  class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_OFFICE_PHONE']; ?>
</slot></td>
	<td class="dataField"><slot><input name='phone_work' type="text" tabindex='2' size='20' maxlength='25' value='<?php echo $this->_tpl_vars['REQUEST']['phone_work']; ?>
'></slot></td>
	</tr><tr>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_LAST_NAME']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td class="dataField"><slot><input name='last_name' type="text" tabindex='1' size='25' maxlength='25' value=""></slot></td>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_EMAIL_ADDRESS']; ?>
</slot></td>
	<td class="dataField"><slot><input name='email1' type="text" tabindex='2' size='35' maxlength='100' value=''></slot></td>
	</tr>
	<?php if ($this->_tpl_vars['REQUEST']['account_id'] != ''): ?>
	<tr>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_ACCOUNT_NAME']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td class="dataField"><slot><?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
<input id='account_name' name='account_name' type="hidden" value='<?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
'><input id='account_id' name='account_id' type="hidden" value='<?php echo $this->_tpl_vars['REQUEST']['parent_id']; ?>
'>&nbsp;</slot></td>
	</tr>
	<?php endif; ?>
	<td></td><td></td>
	</table>
	</form>
<script>
	<?php echo $this->_tpl_vars['additionalScripts']; ?>

</script>