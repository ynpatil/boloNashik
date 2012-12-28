<?php /* Smarty version 2.6.11, created on 2012-03-21 20:48:52
         compiled from modules/Opportunities/tpls/QuickCreate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Opportunities/tpls/QuickCreate.tpl', 50, false),)), $this); ?>


<form name="opportunitiesQuickCreate" id="opportunitiesQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Opportunities">
<input type="hidden" name="record" value="">
<input type="hidden" name="contact_id" value="<?php echo $this->_tpl_vars['REQUEST']['contact_id']; ?>
">
<input type="hidden" name="contact_name" value="<?php echo $this->_tpl_vars['REQUEST']['contact_name']; ?>
">
<input type="hidden" name="email_id" value="<?php echo $this->_tpl_vars['REQUEST']['email_id']; ?>
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
<input name='currency_id' type='hidden' value='<?php echo $this->_tpl_vars['CURRENCY_ID']; ?>
'>
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="<?php echo $this->_tpl_vars['ASSIGNED_USER_ID']; ?>
" />
<input type="hidden" name="to_pdf" value='1'>



<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('OpportunitiesQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('OpportunitiesQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Opportunities';" value="  <?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_LABEL']; ?>
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
	<td width="15%" class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_OPPORTUNITY_NAME']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td width="35%" class="dataField"><slot><input name='name' type="text" tabindex='1' size='35' maxlength='50' value=""></slot></td>
	<td width="20%" class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_AMOUNT']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td width="30%" class="dataField"><slot><input name='amount' tabindex='2' size='15' maxlength='25' type="text" value=''></slot></td>
	</tr><tr>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DATE_CLOSED']; ?>
&nbsp;<span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td class="dataField"><slot><input name='date_closed' onblur="parseDate(this, '<?php echo $this->_tpl_vars['CALENDAR_DATEFORMAT']; ?>
');" id='jscal_field' type="text" tabindex='1' size='11' maxlength='10' value=""> <img src="themes/<?php echo $this->_tpl_vars['THEME']; ?>
/images/jscalendar.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_ENTER_DATE']; ?>
"  id="jscal_trigger" align="absmiddle"> <span class="dateFormat"><?php echo $this->_tpl_vars['USER_DATEFORMAT']; ?>
</span></slot></td>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_LEAD_SOURCE']; ?>
</slot></td>
	<td class="dataField"><slot><select tabindex='2' name='lead_source'><?php echo $this->_tpl_vars['LEAD_SOURCE_OPTIONS']; ?>
</select></slot></td>
	</tr>
	<tr>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_SALES_STAGE']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td class="dataField"><slot><select tabindex='1' name='sales_stage' id='opportunities_sales_stage'><?php echo $this->_tpl_vars['SALES_STAGE_OPTIONS']; ?>
</select></slot></td>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_PROBABILITY']; ?>
</slot></td>
	<td class="dataField"><slot><input name='probability' id='opportunities_probability' tabindex='2' size='4' maxlength='3' type="text" value=''></slot></td>
	</tr><tr>
	<td class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_ACCOUNT_NAME']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td class="dataField"><slot><?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
<input id='account_name' name='account_name' type="hidden" value='<?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
'><input id='account_id' name='account_id' type="hidden" value='<?php echo $this->_tpl_vars['REQUEST']['parent_id']; ?>
'>&nbsp;</slot></td>
	<td></td>
	<td></td>
	</tr>
</table>
</slot></td></tr></table>
	</form>
<script>
<?php echo '
	Calendar.setup ({
		inputField : "jscal_field", ifFormat : "';  echo $this->_tpl_vars['CALENDAR_DATEFORMAT'];  echo '", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
	});
	prob_array = ';  echo $this->_tpl_vars['prob_array'];  echo '
	document.getElementById(\'opportunities_sales_stage\').onchange = function() {
			if(typeof(document.getElementById(\'opportunities_sales_stage\').value) != "undefined" && prob_array[document.getElementById(\'opportunities_sales_stage\').value]) {
				document.getElementById(\'opportunities_probability\').value = prob_array[document.getElementById(\'opportunities_sales_stage\').value];
			} 
		};
'; ?>


	<?php echo $this->_tpl_vars['additionalScripts']; ?>

</script>