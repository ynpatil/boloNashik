<?php /* Smarty version 2.6.11, created on 2012-10-18 07:26:13
         compiled from modules/Studio/wizards/tpls/wizard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'modules/Studio/wizards/tpls/wizard.tpl', 38, false),)), $this); ?>

<form name='StudioWizard' >
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='module' value='Studio'>
<input type='hidden' name='wizard' value='<?php echo $this->_tpl_vars['wizard']; ?>
'>
<input type='hidden' name='option' value=''>
<table class='tabform' width='100%' cellpadding=4>
<tr><td colspan=16><?php echo $this->_tpl_vars['welcome']; ?>
</td></tr>
<tr>
<?php echo smarty_function_counter(array('name' => 'optionCounter','assign' => 'optionCounter','start' => 0), $this);?>

<?php $_from = $this->_tpl_vars['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['display']):
?>
<?php if ($this->_tpl_vars['optionCounter'] > 0 && $this->_tpl_vars['optionCounter'] % 8 == 0): ?>
</tr><tr>
<?php else: ?>
<?php if ($this->_tpl_vars['optionCounter'] != 0): ?>
	<td nowrap width='1'>|</td>
<?php endif; ?>
<?php endif; ?>
<td nowrap>
	<a href='#' onclick='document.StudioWizard.option.value="<?php echo $this->_tpl_vars['key']; ?>
";document.StudioWizard.submit()'><?php echo $this->_tpl_vars['display']; ?>
</a>
</td>
<?php echo smarty_function_counter(array('name' => 'optionCounter'), $this);?>

<?php endforeach; endif; unset($_from); ?>
</tr>
<tr><td><?php if ($this->_tpl_vars['wizard'] != 'StudioWizard'): ?><input type='submit' class='button' name='back' value='<?php echo $this->_tpl_vars['MOD']['LBL_BTN_BACK']; ?>
'><?php endif; ?></td><td colspan='16'></td><td width='100%' >&nbsp;</td></tr>
</table>
</form> 