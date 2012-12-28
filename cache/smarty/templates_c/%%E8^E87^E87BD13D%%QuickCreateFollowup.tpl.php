<?php /* Smarty version 2.6.11, created on 2012-04-02 16:15:34
         compiled from modules/Comments/tpls/QuickCreateFollowup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Comments/tpls/QuickCreateFollowup.tpl', 49, false),)), $this); ?>

<form name="commentsQuickCreate" id="commentsQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Comments">
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
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="<?php echo $this->_tpl_vars['REQUEST']['parent_assigned_user_id']; ?>
" />
<input type="hidden" name="to_pdf" value='1'>
<input type="hidden" name="followup_for_id" value="<?php echo $this->_tpl_vars['REQUEST']['return_id']; ?>
">	
<input type="hidden" name="isassoc_activity" value="true">
<input type="hidden" name="status" value="Not Applicable">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['saveOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"return check_form('commentsQuickCreate');\"") : smarty_modifier_default($_tmp, "onclick=\"return check_form('commentsQuickCreate');\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " >
		<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" type="submit" name="button" <?php echo ((is_array($_tmp=@$this->_tpl_vars['cancelOnclick'])) ? $this->_run_mod_handler('default', true, $_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"") : smarty_modifier_default($_tmp, "onclick=\"this.form.action.value='".($this->_tpl_vars['RETURN_ACTION'])."'; this.form.module.value='".($this->_tpl_vars['RETURN_MODULE'])."'; this.form.record.value='".($this->_tpl_vars['RETURN_ID'])."'\"")); ?>
 value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
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
		<th align="left" class="dataLabel" colspan="2"><h4 class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_NEW_FORM_TITLE']; ?>
</slot></h4></th>
	</tr>
	<tr>
		<td valign="top" class="dataLabel" colspan="2"><slot>Comment for <?php echo $this->_tpl_vars['REQUEST']['parent_type']; ?>
:&nbsp;<?php echo $this->_tpl_vars['REQUEST']['parent_name']; ?>
</slot></td>
	</tr>
	<tr>
		<td valign="top" class="dataLabel"><slot><?php echo $this->_tpl_vars['MOD']['LBL_DESCRIPTION']; ?>
</slot></td>
		<td><slot><textarea name='name' tabindex='3' cols="50" rows="4"><?php echo $this->_tpl_vars['NAME']; ?>
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