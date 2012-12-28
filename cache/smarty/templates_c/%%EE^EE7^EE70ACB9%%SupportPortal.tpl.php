<?php /* Smarty version 2.6.11, created on 2012-04-03 21:54:03
         compiled from modules/Administration/SupportPortal.tpl */ ?>


<?php if ($this->_tpl_vars['helpFileExists']): ?>
	<html>
	<head>
	<title><?php echo $this->_tpl_vars['title']; ?>
</title>
	<link href='<?php echo $this->_tpl_vars['styleSheet']; ?>
' rel='stylesheet' type='text/css' />
<?php if (isset ( $this->_tpl_vars['styleColor'] )): ?>
	<link href='<?php echo $this->_tpl_vars['styleColor']; ?>
' rel='stylesheet' type='text/css' />
<?php endif; ?>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
">
	</head>
	<body onLoad='window.focus();'>
	<?php echo $this->_tpl_vars['helpBar']; ?>

	<table class='tabForm'>
		<tr>
		<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['helpPath']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		</tr>
	</table>
	<?php echo $this->_tpl_vars['bookmarkScript']; ?>

	</body>
	</html>	
<?php else: ?>
	<IFRAME frameborder="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF" SRC="<?php echo $this->_tpl_vars['iframeURL']; ?>
"  NAME="SUGARIFRAME" ID="SUGARIFRAME" WIDTH="100%" height="700"></IFRAME>
<?php endif; ?>