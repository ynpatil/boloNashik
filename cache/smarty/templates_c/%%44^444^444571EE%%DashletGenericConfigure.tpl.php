<?php /* Smarty version 2.6.11, created on 2012-04-03 00:38:33
         compiled from include/Dashlets/DashletGenericConfigure.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'include/Dashlets/DashletGenericConfigure.tpl', 60, false),)), $this); ?>


<div style='width: 500px'>
<form action='index.php' id='configure_<?php echo $this->_tpl_vars['id']; ?>
' method='post' onSubmit='SUGAR.sugarHome.setChooser(); return SUGAR.dashlets.postForm("configure_<?php echo $this->_tpl_vars['id']; ?>
", SUGAR.sugarHome.uncoverPage);'>
<input type='hidden' name='id' value='<?php echo $this->_tpl_vars['id']; ?>
'>
<input type='hidden' name='module' value='Home'>
<input type='hidden' name='action' value='ConfigureDashlet'>
<input type='hidden' name='configure' value='true'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' id='displayColumnsDef' name='displayColumnsDef' value=''>
<input type='hidden' id='hideTabsDef' name='hideTabsDef' value=''>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm">
	<tr>
        <td class='dataLabel' colspan='4' align='left'>
        	<h2><?php echo $this->_tpl_vars['strings']['general']; ?>
</h2>
        </td>
    </tr>
    <tr>
	    <td class='dataLabel'>
		    <?php echo $this->_tpl_vars['strings']['title']; ?>

        </td>
        <td class='dataField' colspan='3'>
            <input type='text' name='dashletTitle' value='<?php echo $this->_tpl_vars['dashletTitle']; ?>
'>
        </td>
	</tr>
    <tr>
	    <td class='dataLabel'>
		    <?php echo $this->_tpl_vars['strings']['displayRows']; ?>

        </td>
        <td class='dataField' colspan='3'>
            <select name='displayRows'>
				<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['displayRowOptions'],'output' => $this->_tpl_vars['displayRowOptions'],'selected' => $this->_tpl_vars['displayRowSelect']), $this);?>

           	</select>
        </td>
    </tr>
    <tr>
        <td colspan='4' align='center'>
        	<table border='0' cellpadding='0' cellspacing='0'>
        	<tr><td>
			    <?php echo $this->_tpl_vars['columnChooser']; ?>

		    </td>
		    </tr></table>
	    </td>    
	</tr>
	<tr>
        <td class='dataLabel' colspan='4' align='left'>
	        <br>
        	<h2><?php echo $this->_tpl_vars['strings']['filters']; ?>
</h2>
        </td>
    </tr>
    <tr>
	    <td class='dataLabel'>
            <?php echo $this->_tpl_vars['strings']['myItems']; ?>

        </td>
        <td class='dataField'>
            <input type='checkbox' <?php if ($this->_tpl_vars['myItemsOnly'] == 'true'): ?>checked<?php endif; ?> name='myItemsOnly' value='true'>
        </td>
    </tr>
    <tr>
    <?php $_from = $this->_tpl_vars['searchFields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['searchIteration'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['searchIteration']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['params']):
        $this->_foreach['searchIteration']['iteration']++;
?>
        <td class='dataLabel' valign='top'>
            <?php echo $this->_tpl_vars['params']['label']; ?>

        </td>
        <td class='dataField' valign='top' style='padding-bottom: 5px'>
            <?php echo $this->_tpl_vars['params']['input']; ?>

        </td>
        <?php if (( !(1 & $this->_foreach['searchIteration']['iteration']) ) && $this->_foreach['searchIteration']['iteration'] != ($this->_foreach['searchIteration']['iteration'] == $this->_foreach['searchIteration']['total'])): ?>
        </tr><tr>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </tr>
    <tr>
	    <td colspan='4' align='right'>
	        <input type='submit' class='button' value='<?php echo $this->_tpl_vars['strings']['save']; ?>
'>
	    </td>    
	</tr>
</table>
</form>
</div>