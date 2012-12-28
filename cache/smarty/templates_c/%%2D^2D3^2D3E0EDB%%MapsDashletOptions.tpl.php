<?php /* Smarty version 2.6.11, created on 2007-02-06 08:17:43
         compiled from custom/modules/Home/Dashlets/MapsDashlet/MapsDashletOptions.tpl */ ?>


<div style='width: 500px'>
<form name='configure_<?php echo $this->_tpl_vars['id']; ?>
' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm("configure_<?php echo $this->_tpl_vars['id']; ?>
", SUGAR.sugarHome.uncoverPage);'>
<input type='hidden' name='id' value='<?php echo $this->_tpl_vars['id']; ?>
'>
<input type='hidden' name='module' value='Home'>
<input type='hidden' name='action' value='ConfigureDashlet'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' name='configure' value='true'>
<table width="400" cellpadding="0" cellspacing="0" border="0" class="tabForm" align="center">
<tr>
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['titleLbl']; ?>
</td>
    <td valign='top' class='dataField'>
    	<input class="text" name="title" size='20' value='<?php echo $this->_tpl_vars['title']; ?>
'>
    </td>
</tr>
<tr>
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['heightLbl']; ?>
</td>
    <td valign='top' class='dataField'>
    	<input class="text" name="height" size='3' value='<?php echo $this->_tpl_vars['height']; ?>
'>
    </td>
</tr>
<tr>
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['displayOnStartupLbl']; ?>
</td>
    <td valign='top' class='dataField'>
    	 <input type='checkbox' <?php if ($this->_tpl_vars['display_on_startup'] == 'true'): ?>checked<?php endif; ?> name='display_on_startup' value='true'>
    </td>
</tr>
<tr>
    <td align="right" colspan="2">
        <input type='submit' class='button' value='<?php echo $this->_tpl_vars['saveLbl']; ?>
'>
   	</td>
</tr>
</table>
</form>
</div>