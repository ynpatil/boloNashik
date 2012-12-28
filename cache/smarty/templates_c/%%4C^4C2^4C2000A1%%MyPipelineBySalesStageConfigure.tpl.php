<?php /* Smarty version 2.6.11, created on 2012-03-24 23:24:01
         compiled from modules/Charts/Dashlets/MyPipelineBySalesStageDashlet/MyPipelineBySalesStageConfigure.tpl */ ?>


<div style='width: 400px'>
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
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['LBL_DATE_START']; ?>
 <br><i><?php echo $this->_tpl_vars['user_date_format']; ?>
</i></td>
    <td valign='top' class='dataField'>
    	<input onblur="parseDate(this, '<?php echo $this->_tpl_vars['cal_dateformat']; ?>
');" class="text" name="mypbss_date_start" size='12' maxlength='10' id='date_start' value='<?php echo $this->_tpl_vars['date_start']; ?>
'>
    	<img src="<?php echo $this->_tpl_vars['image_path']; ?>
jscalendar.gif" alt="<?php echo $this->_tpl_vars['LBL_ENTER_DATE']; ?>
" id="date_start_trigger" align="absmiddle">
    </td>
</tr>
<tr>
    <td valign='top' nowrap class='dataLabel'><?php echo $this->_tpl_vars['LBL_DATE_END']; ?>
<br><i><?php echo $this->_tpl_vars['user_date_format']; ?>
</i></td>
    <td valign='top' class='dataField'>
    	<input onblur="parseDate(this, '<?php echo $this->_tpl_vars['cal_dateformat']; ?>
');" class="text" name="mypbss_date_end" size='12' maxlength='10' id='date_end' value='<?php echo $this->_tpl_vars['date_end']; ?>
'>
    	<img src="<?php echo $this->_tpl_vars['image_path']; ?>
jscalendar.gif" alt="<?php echo $this->_tpl_vars['LBL_ENTER_DATE']; ?>
" id="date_end_trigger" align="absmiddle">
    </td>
</tr>

    <tr>
    <td valign='top' class='dataLabel' nowrap><?php echo $this->_tpl_vars['LBL_SALES_STAGES']; ?>
</td>
    <td valign='top' class='dataField'>
    	<select name="mypbss_sales_stages[]" multiple size='3'>
    		<?php echo $this->_tpl_vars['selected_datax']; ?>

    	</select></td>
    </tr>

<tr>
    <td align="right" colspan="2">
        <input type='submit' onclick="" class='button' value='Submit'>
   	</td>
</tr>
</table>
</form>
<?php echo '
<script type="text/javascript">
Calendar.setup ({
    inputField : "date_start", ifFormat : "';  echo $this->_tpl_vars['cal_dateformat'];  echo '", showsTime : false, button : "date_start_trigger", singleClick : true, step : 1
});
Calendar.setup ({
    inputField : "date_end", ifFormat : "';  echo $this->_tpl_vars['cal_dateformat'];  echo '", showsTime : false, button : "date_end_trigger", singleClick : true, step : 1
});
'; ?>

</script>
</div>