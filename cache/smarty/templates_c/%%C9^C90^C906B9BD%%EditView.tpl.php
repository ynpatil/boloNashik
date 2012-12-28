<?php /* Smarty version 2.6.11, created on 2012-12-27 13:46:54
         compiled from modules/Studio/ListViewEditor/EditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'modules/Studio/ListViewEditor/EditView.tpl', 96, false),array('function', 'sugar_translate', 'modules/Studio/ListViewEditor/EditView.tpl', 106, false),)), $this); ?>


<?php echo '
<br>


<script type="text/javascript" src="modules/Studio/JSTransaction.js" ></script>
<script>
	var jstransaction = new JSTransaction();
</script>
<script src = "include/javascript/yui/dragdrop.js" ></script>
<script src=\'modules/Studio/studiotabgroups.js\'></script>
<script src = "modules/Studio/ygDDListStudio.js" ></script>				 	
<script type="text/javascript" src="modules/Studio/studiodd.js" ></script>	
<script type="text/javascript" src="modules/Studio/studio.js" ></script>	
<style type=\'text/css\'>
.slot {
	border-width:1px;border-color:#999999;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}


.slotSub {
	border-width:1px;border-color:#006600;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}
.slotB {
	border-width:0;cursor:move;

}
.listContainer
{
	margin-left: 4;
	padding-left: 4;
	margin-right: 4;
	padding-right: 4;
	list-style-type: none;
}

.tableContainer
{
	
}
.tdContainer{
	border: thin solid gray;
	padding: 10;
}
.fieldValue{
	color: #999;
	font-size: 75%;
	cursor:move;
}


	
}

</style>
'; ?>





<table>
<tr><td colspan='100'><h2><?php echo $this->_tpl_vars['title']; ?>
</h2></td></tr>
<tr><td colspan='100'>
<?php echo $this->_tpl_vars['description']; ?>

</td></tr><tr><td><br></td></tr><tr><td colspan='100'><?php echo $this->_tpl_vars['buttons']; ?>
</td></tr><tr>
<?php echo smarty_function_counter(array('start' => 0,'name' => 'slotCounter','print' => false,'assign' => 'slotCounter'), $this);?>

<?php echo smarty_function_counter(array('start' => 0,'name' => 'modCounter','print' => false,'assign' => 'modCounter'), $this);?>

<?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['list']):
?>
<td valign='top' class='tabForm' nowrap>
<h3><?php echo $this->_tpl_vars['label']; ?>
</h3>
<ul class='listContainer' id='ul<?php echo $this->_tpl_vars['slotCounter']; ?>
'>

<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>


<li  id='subslot<?php echo $this->_tpl_vars['modCounter']; ?>
'><span class='slotB'><?php if (! empty ( $this->_tpl_vars['translate'] )):  echo smarty_function_sugar_translate(array('label' => $this->_tpl_vars['value']['label'],'module' => $this->_tpl_vars['module']), $this); else:  echo $this->_tpl_vars['value']['label'];  endif; ?></span><?php if (empty ( $this->_tpl_vars['hideKeys'] )): ?> <br><span class='fieldValue'>[<?php echo $this->_tpl_vars['key']; ?>
]<?php endif; ?></span>

</li>
<script>
tabLabelToValue['<?php echo $this->_tpl_vars['value']['label']; ?>
|<?php echo $this->_tpl_vars['key']; ?>
'] = '<?php echo $this->_tpl_vars['key']; ?>
';
if(typeof(subtabModules['subslot<?php echo $this->_tpl_vars['modCounter']; ?>
']) == 'undefined')subtabModules['subslot<?php echo $this->_tpl_vars['modCounter']; ?>
'] = '<?php echo $this->_tpl_vars['value']['label']; ?>
|<?php echo $this->_tpl_vars['key']; ?>
';
</script>
<?php echo smarty_function_counter(array('name' => 'modCounter'), $this);?>

<?php endforeach; endif; unset($_from); ?>
<li  id='topslot<?php echo $this->_tpl_vars['slotCounter']; ?>
' class='noBullet'>&nbsp;</span>
</ul>
</td>
<?php echo smarty_function_counter(array('name' => 'slotCounter'), $this);?>

<?php endforeach; endif; unset($_from); ?>
<td width='100%'>&nbsp;</td>
</tr></table>


<span class='error'><?php echo $this->_tpl_vars['error']; ?>
</span>



<?php echo '


			<script>
		 
			var gLogger = new ygLogger("Studio");
		  var slotCount = ';  echo $this->_tpl_vars['slotCounter'];  echo ';
		  var modCount = ';  echo $this->_tpl_vars['modCounter'];  echo ';
			var subSlots = [];
			 var yahooSlots = [];
			function dragDropInit(){
					if (typeof(ygLogger) != "undefined") {
				ygLogger.init(document.getElementById("logDiv"));
			}
				YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
				gLogger.loggerEnabled = false;
				gLogger.debug("point mode");
				for(msi = 0; msi <= slotCount ; msi++){
					yahooSlots["topslot"+ msi] = new ygDDListStudio("topslot" + msi, "subTabs", true);
						
				
				}
				for(msi = 0; msi <= modCount ; msi++){
						yahooSlots["subslot"+ msi] = new ygDDListStudio("subslot" + msi, "subTabs", false);
						
				}
				
				yahooSlots["subslot"+ (msi - 1) ].updateTabs();
				  // initPointMode();
			}
			YAHOO.util.DDM.mode = YAHOO.util.DDM.INTERSECT; 
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			
			
</script>	
'; ?>



<div id='logDiv' style='display:none'> 
</div>

<?php echo $this->_tpl_vars['additionalFormData']; ?>

	
</form>

