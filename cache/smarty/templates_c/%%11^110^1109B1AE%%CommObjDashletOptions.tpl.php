<?php /* Smarty version 2.6.11, created on 2007-05-30 14:59:58
         compiled from modules/AccountObjective/Dashlets/CommObjDashlet/CommObjDashletOptions.tpl */ ?>

<div style='width: 500px'>
<form name='configure_<?php echo $this->_tpl_vars['id']; ?>
' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postFormWithMethodParams("configure_<?php echo $this->_tpl_vars['id']; ?>
", SUGAR.accountObjective.uncoverPage);'>
<input type='hidden' name='id' value='<?php echo $this->_tpl_vars['id']; ?>
'>
<input type='hidden' name='record' value='<?php echo $this->_tpl_vars['record']; ?>
'>
<input type='hidden' name='module' value='AccountObjective'>
<input type='hidden' name='action' value='ConfigureDashlet'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' name='configure' value='true'>
<table width="400" cellpadding="0" cellspacing="0" border="0" class="tabForm" align="center">
<!-- 
<tr>
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['titleLbl']; ?>
</td>
    <td valign='top' class='dataField'>
    	<input class="text" name="title" size='20' value='<?php echo $this->_tpl_vars['title']; ?>
'>
    </td>
</tr>
-->

<tr>
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['heightLbl']; ?>
</td>
    <td valign='top' class='dataField'>
    	<input class="text" name="height" size='3' value='<?php echo $this->_tpl_vars['height']; ?>
'>
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