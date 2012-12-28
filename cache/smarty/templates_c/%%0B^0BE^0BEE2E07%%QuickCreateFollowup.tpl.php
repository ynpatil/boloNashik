<?php /* Smarty version 2.6.11, created on 2012-05-29 16:02:44
         compiled from modules/Reviews/tpls/QuickCreateFollowup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Reviews/tpls/QuickCreateFollowup.tpl', 48, false),)), $this); ?>

<form name="reviewsQuickCreate" id="reviewsQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Reviews">
<input type="hidden" name="record" value="">
<input type="hidden" name="return_action" value="<?php echo $this->_tpl_vars['REQUEST']['return_action']; ?>
">
<input type="hidden" name="return_module" value="<?php echo $this->_tpl_vars['REQUEST']['parent_type']; ?>
">
<input type="hidden" name="parent_type" value="<?php echo $this->_tpl_vars['REQUEST']['parent_type']; ?>
">
<input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['REQUEST']['return_id']; ?>
">
<input type="hidden" name="parent_name" value="<?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
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
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('reviewsQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('reviewsQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Reviews';" value="  <?php echo $this->_tpl_vars['APP']['LBL_FULL_FORM_BUTTON_LABEL']; ?>
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
		<td valign="top" class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_SUBJECT']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
		<td><slot><textarea name='name' cols="50" tabindex='1' rows="1"><?php echo $this->_tpl_vars['NAME']; ?>
</textarea></slot></td>
		<td valign="top" class="dataLabel" colspan="2"><slot>Review for <?php echo $this->_tpl_vars['REQUEST']['parent_type']; ?>
:&nbsp;<?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
</slot></td>
	</tr>
	<tr>
	<td class="dataLabel" width="15%"><slot><?php echo $this->_tpl_vars['APP']['LBL_ASSIGNED_TO']; ?>
 <span class="required"><?php echo $this->_tpl_vars['APP']['LBL_REQUIRED_SYMBOL']; ?>
</span></slot></td>
	<td width="35%"><slot>
	<select tabindex='2' name='assigned_user_id'><?php echo $this->_tpl_vars['ASSIGNED_USER_OPTIONS']; ?>
</select>			
	</slot></td>
	</tr>
	<tr>
		<td valign="top" class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DESCRIPTION']; ?>
</slot></td>
		<td><slot><textarea name='description' tabindex='3' cols="50" rows="4"><?php echo $this->_tpl_vars['DESCRIPTION']; ?>
</textarea></slot></td>
	</tr>
	</table>
</td>
</tr>
</table>
</form>
<script type="text/javascript">
<?php echo '
'; ?>

	<?php echo $this->_tpl_vars['additionalScripts']; ?>

</script>