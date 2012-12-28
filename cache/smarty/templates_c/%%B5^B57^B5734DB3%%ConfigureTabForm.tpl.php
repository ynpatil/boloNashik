<?php /* Smarty version 2.6.11, created on 2012-12-27 13:46:54
         compiled from modules/Administration/ConfigureTabForm.tpl */ ?>


<form name="EditView" id='EditView' method="POST" action="index.php">
<input type="hidden" name="module" value="Administration">
<input type="hidden" name="action">
<input type="hidden" name="return_module" value="<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
">
<input type="hidden" name="return_action" value="<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
			

		<td style="padding-bottom: 2px;"><input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" onclick="studiotabs.generateGroupForm('EditView');this.form.action.value='SaveTabs'; " type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " > <input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="button" onclick="this.form.action.value='<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
'; this.form.module.value='<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
';" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  "></td>
</tr><tr>		
		<td style="padding-bottom: 2px;" valign='top'><input type='checkbox' name='user_edit_tabs' value=1 class='checkbox' <?php if (! empty ( $this->_tpl_vars['user_can_edit'] )): ?>CHECKED<?php endif; ?>>&nbsp;<b onclick='document.EditView.user_edit_tabs.checked= !document.EditView.user_edit_tabs.checked' style='cursor:default'><?php echo $this->_tpl_vars['MOD']['LBL_ALLOW_USER_TABS']; ?>
</b>
	
</tr>
</table>