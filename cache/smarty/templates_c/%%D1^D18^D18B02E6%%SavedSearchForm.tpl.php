<?php /* Smarty version 2.6.11, created on 2012-03-19 17:54:03
         compiled from modules/SavedSearch/SavedSearchForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/SavedSearch/SavedSearchForm.tpl', 84, false),)), $this); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm" style="border-top: 0px none; margin-bottom: 4px" >
<tr>
<tr valign='top'>
	<td align='left' rowspan='2' colspan='2'>
		<input id='displayColumnsDef' type='hidden' name='displayColumns'>
		<input id='hideTabsDef' type='hidden' name='hideTabs'>
		<?php echo $this->_tpl_vars['columnChooser']; ?>

		<br>
	</td>
	<td class='dataLabel' nowrap align='left' width='1%'>
		<?php echo $this->_tpl_vars['MOD']['LBL_ORDER_BY_COLUMNS']; ?>

	</td>
	<td class='dataField'>
		<select name='orderBy' id='orderBySelect'>
		</select>
	</td>
</tr>
<tr valign='top'>
	<td nowrap class='dataLabel'>
		<?php echo $this->_tpl_vars['MOD']['LBL_DIRECTION']; ?>

	</td>
	<td class='dataField'>
		<input id='sort_order_desc_radio' type='radio' name='sortOrder' value='DESC' <?php if ($this->_tpl_vars['selectedSortOrder'] == 'DESC'): ?>checked<?php endif; ?>> <span onclick='document.getElementById("sort_order_desc_radio").checked = true' style="cursor: pointer; cursor: hand">Descending</span>
		<input id='sort_order_asc_radio' type='radio' name='sortOrder' value='ASC' <?php if ($this->_tpl_vars['selectedSortOrder'] == 'ASC'): ?>checked<?php endif; ?>> <span onclick='document.getElementById("sort_order_asc_radio").checked = true' style="cursor: pointer; cursor: hand">Ascending</span>
	</td>
</tr>
<tr>
	<td class='dataLabel' nowrap width='1%'>
		<?php echo $this->_tpl_vars['MOD']['LBL_SAVE_SEARCH_AS']; ?>
 <img border='0' src='<?php echo $this->_tpl_vars['imagePath']; ?>
help.gif' onmouseover="return overlib('<?php echo $this->_tpl_vars['MOD']['LBL_SAVE_SEARCH_AS_HELP']; ?>
', FGCLASS, 'olFgClass', CGCLASS, 'olCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olCapFontClass', CLOSEFONTCLASS, 'olCloseFontClass' );" onmouseout="return nd();">
	</td>
	<td class='dataField'>
		<input type='text' name='saved_search_name'>
		<input type='hidden' name='search_module' value=''>
		<input type='hidden' name='saved_search_action' value=''>
		<input value='<?php echo $this->_tpl_vars['SAVE']; ?>
' title='<?php echo $this->_tpl_vars['MOD']['LBL_SAVE_BUTTON_TITLE']; ?>
' class='button' type='button' name='saved_search_submit' onclick='SUGAR.savedViews.setChooser(); return SUGAR.savedViews.saved_search_action("save");'>
	</td>
	<td nowrap class='dataLabel'>
		<?php echo $this->_tpl_vars['MOD']['LBL_PREVIOUS_SAVED_SEARCH']; ?>
 <img border='0' src='<?php echo $this->_tpl_vars['imagePath']; ?>
help.gif' onmouseover="return overlib('<?php echo $this->_tpl_vars['MOD']['LBL_PREVIOUS_SAVED_SEARCH_HELP']; ?>
', FGCLASS, 'olFgClass', CGCLASS, 'olCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olCapFontClass', CLOSEFONTCLASS, 'olCloseFontClass' );" onmouseout="return nd();">
	</td>
	<td class='dataField'>
		<input type='hidden' value='true' name='fromAdvanced'>
		<select name='saved_search_select' onChange='SUGAR.savedViews.saved_search_action("");'>
			<?php echo $this->_tpl_vars['SAVED_SEARCHES_OPTIONS']; ?>

		</select>
		&nbsp;<input <?php if ($this->_tpl_vars['lastSavedView'] == ''): ?>style='display: none'<?php endif; ?> class='button' onclick='SUGAR.savedViews.setChooser(); return SUGAR.savedViews.saved_search_action("update")' value='Update' title='<?php echo $this->_tpl_vars['MOD']['LBL_DELETE_BUTTON_TITLE']; ?>
' name='ss_update' type='button'>&nbsp;
		<input <?php if ($this->_tpl_vars['lastSavedView'] == ''): ?>style='display: none'<?php endif; ?> class='button' onclick='return SUGAR.savedViews.saved_search_action("delete", "<?php echo $this->_tpl_vars['MOD']['LBL_DELETE_CONFIRM']; ?>
")' value='<?php echo $this->_tpl_vars['DELETE']; ?>
' title='<?php echo $this->_tpl_vars['MOD']['LBL_DELETE_BUTTON_TITLE']; ?>
' name='ss_delete' type='button'>

	</td>
</tr>
</table>
<script>
	SUGAR.savedViews.columnsMeta = <?php echo $this->_tpl_vars['columnsMeta']; ?>
;
	columnsMeta = <?php echo $this->_tpl_vars['columnsMeta']; ?>
;
	saved_search_select = "<?php echo $this->_tpl_vars['SAVED_SEARCH_SELECT']; ?>
";
	selectedSortOrder = "<?php echo ((is_array($_tmp=@$this->_tpl_vars['selectedSortOrder'])) ? $this->_run_mod_handler('default', true, $_tmp, 'DESC') : smarty_modifier_default($_tmp, 'DESC')); ?>
";
	selectedOrderBy = "<?php echo $this->_tpl_vars['selectedOrderBy']; ?>
";
</script>