<?php /* Smarty version 2.6.11, created on 2012-10-23 15:52:30
         compiled from modules/ACLRoles/EditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'modules/ACLRoles/EditView.tpl', 85, false),)), $this); ?>


<script>
<?php echo '
function set_focus(){
	document.getElementById(\'name\').focus();
}
'; ?>

</script>
<form method='POST' name='EditView'>
<input type='hidden' name='record' value='<?php echo $this->_tpl_vars['ROLE']['id']; ?>
'>
<input type='hidden' name='module' value='ACLRoles'>
<input type='hidden' name='action' value='Save'>
<input type='hidden' name='return_record' value='<?php echo $this->_tpl_vars['RETURN']['record']; ?>
'>
<input type='hidden' name='return_action' value='<?php echo $this->_tpl_vars['RETURN']['action']; ?>
'>
<input type='hidden' name='return_module' value='<?php echo $this->_tpl_vars['RETURN']['module']; ?>
'> &nbsp;
<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="button" onclick="this.form.action.value='Save';return check_form('EditView');" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " > &nbsp;
<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
"   class='button' accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" type='submit' name='save' value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
 " class='button' onclick='document.EditView.action.value="<?php echo $this->_tpl_vars['RETURN']['action']; ?>
";document.EditView.module.value="<?php echo $this->_tpl_vars['RETURN']['module']; ?>
";document.EditView.record.value="<?php echo $this->_tpl_vars['RETURN']['record']; ?>
";document.EditView.submit();'>
</p>
<p>
<TABLE width='100%' class="tabForm"  border='0' cellpadding=0 cellspacing = 0  >
<TR>
<td class="dataLabel" align='right'><?php echo $this->_tpl_vars['MOD']['LBL_NAME']; ?>
:</td><td class="dataField">
<input id='name' name='name' type='text' value='<?php echo $this->_tpl_vars['ROLE']['name']; ?>
'>
</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td class="dataLabel" align='right'><?php echo $this->_tpl_vars['MOD']['LBL_DESCRIPTION']; ?>
:</td>
<td class="dataField"><textarea name='description' cols="80" rows="8"><?php echo $this->_tpl_vars['ROLE']['description']; ?>
</textarea></td>
</tr>
</table>
</p>
<b><?php echo $this->_tpl_vars['MOD']['LBL_EDIT_VIEW_DIRECTIONS']; ?>
</b>
<TABLE width='100%' class='tabDetailView' border='0' cellpadding=0 cellspacing = 1  >
<TR>
<td></td>

<?php $_from = $this->_tpl_vars['ACTION_NAMES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ACTION_NAME']):
?>
	<td nowrap align='center' class="tabDetailViewDL"><div align='center'><b><?php echo $this->_tpl_vars['ACTION_NAME']; ?>
</b></div></td>
<?php endforeach; else: ?>

          <td colspan="2">&nbsp;</td>

<?php endif; unset($_from); ?>
</TR>
<?php echo '

	'; ?>

<?php $_from = $this->_tpl_vars['CATEGORIES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CATEGORY_NAME'] => $this->_tpl_vars['TYPES']):
?>
<TR>
<td nowrap width='1%' class="tabDetailViewDL"><b><?php echo $this->_tpl_vars['APP_LIST']['moduleList'][$this->_tpl_vars['CATEGORY_NAME']]; ?>
</b></td>
	<?php $_from = $this->_tpl_vars['TYPES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ACTIONS']):
?>
		<?php $_from = $this->_tpl_vars['ACTIONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ACTION']):
?>
	
	<td  width='<?php echo $this->_tpl_vars['TDWIDTH']; ?>
%' class="tabDetailViewDF" style="text-align: center;" ondblclick="toggleDisplay('<?php echo $this->_tpl_vars['ACTION']['id']; ?>
');//document.getElementById('act_guid<?php echo $this->_tpl_vars['ACTION']['id']; ?>
').focus()">
	<div  style="display: none" id="<?php echo $this->_tpl_vars['ACTION']['id']; ?>
">
	<select class="acl<?php echo $this->_tpl_vars['ACTION']['accessName']; ?>
" name='act_guid<?php echo $this->_tpl_vars['ACTION']['id']; ?>
' id = 'act_guid<?php echo $this->_tpl_vars['ACTION']['id']; ?>
' onblur="document.getElementById('<?php echo $this->_tpl_vars['ACTION']['id']; ?>
link').innerHTML=this.options[this.selectedIndex].text; toggleDisplay('<?php echo $this->_tpl_vars['ACTION']['id']; ?>
');" onchange="document.getElementById('<?php echo $this->_tpl_vars['ACTION']['id']; ?>
link').innerHTML=this.options[this.selectedIndex].text; toggleDisplay('<?php echo $this->_tpl_vars['ACTION']['id']; ?>
');">
   		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ACTION']['accessOptions'],'selected' => $this->_tpl_vars['ACTION']['aclaccess']), $this);?>

	</select>
	</div>
	<div class="acl<?php echo $this->_tpl_vars['ACTION']['accessName']; ?>
" style="display: inline;" id="<?php echo $this->_tpl_vars['ACTION']['id']; ?>
link"><?php echo $this->_tpl_vars['ACTION']['accessName']; ?>
</div>
	</td>
	<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>


</TR>
	<?php endforeach; else: ?>

         <tr> <td colspan="2">No Actions Defined</td></tr>

<?php endif; unset($_from); ?>
</TABLE>
<div style="padding-top:10px;">
&nbsp;<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" class="button" onclick="this.form.action.value='Save';return check_form('EditView');" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " /> &nbsp;
<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
"   class='button' type='submit' name='save' value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
 " class='button' onclick='document.EditView.action.value="<?php echo $this->_tpl_vars['RETURN']['action']; ?>
";document.EditView.module.value="<?php echo $this->_tpl_vars['RETURN']['module']; ?>
";document.EditView.record.value="<?php echo $this->_tpl_vars['RETURN']['record']; ?>
";document.EditView.submit();' />
</div>
</form>