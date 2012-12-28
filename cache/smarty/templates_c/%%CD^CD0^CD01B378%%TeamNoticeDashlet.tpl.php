<?php /* Smarty version 2.6.11, created on 2012-12-27 13:42:59
         compiled from modules/Home/Dashlets/TeamNoticeDashlet/TeamNoticeDashlet.tpl */ ?>


<div id='teamnotice_<?php echo $this->_tpl_vars['id']; ?>
' style='overflow: auto; width: 100%; height: <?php echo $this->_tpl_vars['height']; ?>
px; border: 1px #ddd solid'><?php echo $this->_tpl_vars['savedText']; ?>
</div>
<textarea id='teamnotice_textarea_<?php echo $this->_tpl_vars['id']; ?>
' rows="5" onblur='JotPad.blur(this, "<?php echo $this->_tpl_vars['id']; ?>
")' style='display: none; width: 100%; height: <?php echo $this->_tpl_vars['height']; ?>
px; overflow: auto'></textarea>