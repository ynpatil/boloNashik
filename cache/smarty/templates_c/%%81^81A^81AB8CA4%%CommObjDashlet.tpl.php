<?php /* Smarty version 2.6.11, created on 2012-04-09 02:47:57
         compiled from modules/AccountObjective/Dashlets/CommObjDashlet/CommObjDashlet.tpl */ ?>


<div id='jotpad_<?php echo $this->_tpl_vars['id']; ?>
' ondblclick='JotPad.edit(this, "<?php echo $this->_tpl_vars['id']; ?>
")' style='overflow: auto; width: 100%; height: <?php echo $this->_tpl_vars['height']; ?>
px; border: 1px #ddd solid'><?php echo $this->_tpl_vars['savedText']; ?>
</div>
<textarea id='jotpad_textarea_<?php echo $this->_tpl_vars['id']; ?>
' rows="5" onblur='JotPad.blur(this, "<?php echo $this->_tpl_vars['id']; ?>
")' style='display: none; width: 100%; height: <?php echo $this->_tpl_vars['height']; ?>
px; overflow: auto'></textarea>