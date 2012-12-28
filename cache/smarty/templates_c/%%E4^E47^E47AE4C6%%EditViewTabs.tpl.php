<?php /* Smarty version 2.6.11, created on 2007-01-07 17:18:46
         compiled from modules/Studio/TabGroups/EditViewTabs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'modules/Studio/TabGroups/EditViewTabs.tpl', 94, false),array('function', 'sugar_translate', 'modules/Studio/TabGroups/EditViewTabs.tpl', 115, false),)), $this); ?>


<?php echo '
<br>
<form name=\'edittabs\' id=\'edittabs\' method=\'POST\' action=\'index.php\'>	
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


	
}

</style>
'; ?>

<?php echo $this->_tpl_vars['buttons']; ?>


<table><tr><td valign='top' class='tabForm' nowrap>
<table  cellpadding="0" cellspacing="0" border="1" width="100%"   id='s_field_delete'>
							<tr><td ><ul id='trash' class='listContainer'>
<li class='nobullet' id='trashcan'><?php echo $this->_tpl_vars['deleteImage']; ?>
&nbsp;Delete&nbsp;Module</li>

</ul>
						</td></tr></table>


<div class='noBullet'><h2><?php echo $this->_tpl_vars['MOD']['LBL_MODULES']; ?>
</h2>
<ul class='listContainer'>
<?php echo smarty_function_counter(array('start' => 0,'name' => 'modCounter','print' => false,'assign' => 'modCounter'), $this);?>

<?php $_from = $this->_tpl_vars['availableModuleList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>


<li  id='modSlot<?php echo $this->_tpl_vars['modCounter']; ?>
'><span class='slotB'><?php echo $this->_tpl_vars['value']['label']; ?>
</span></li>
<script>
tabLabelToValue['<?php echo $this->_tpl_vars['value']['label']; ?>
'] = '<?php echo $this->_tpl_vars['value']['value']; ?>
';
subtabModules['modSlot<?php echo $this->_tpl_vars['modCounter']; ?>
'] = '<?php echo $this->_tpl_vars['value']['label']; ?>
'</script>
<?php echo smarty_function_counter(array('name' => 'modCounter'), $this);?>

<?php endforeach; endif; unset($_from); ?>
</ul>
</td>
<td valign='top' nowrap>
<table class='tableContainer' id='groupTable'><tr>
<?php echo smarty_function_counter(array('start' => 0,'name' => 'tabCounter','print' => false,'assign' => 'tabCounter'), $this);?>


<?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tabName'] => $this->_tpl_vars['tab']):
?>
<?php if ($this->_tpl_vars['tabCounter'] > 0 && $this->_tpl_vars['tabCounter'] % 6 == 0): ?>
</tr><tr>
<?php endif; ?>
<td valign='top' class='tdContainer'>
<div id='slot<?php echo $this->_tpl_vars['tabCounter']; ?>
' class='noBullet'><h2 id='handle<?php echo $this->_tpl_vars['tabCounter']; ?>
' ><span id='tabname_<?php echo $this->_tpl_vars['tabCounter']; ?>
' class='slotB'><?php echo smarty_function_sugar_translate(array('label' => $this->_tpl_vars['tab']['label']), $this);?>
</span><br><span id='tabother_<?php echo $this->_tpl_vars['tabCounter']; ?>
'><span onclick='studiotabs.editTabGroupLabel(<?php echo $this->_tpl_vars['tabCounter']; ?>
, false)'><?php echo $this->_tpl_vars['editImage']; ?>
</span>&nbsp;<span onclick='studiotabs.deleteTabGroup(<?php echo $this->_tpl_vars['tabCounter']; ?>
)'><?php echo $this->_tpl_vars['deleteImage']; ?>
</span></span></h2><input type='hidden' name='tablabelid_<?php echo $this->_tpl_vars['tabCounter']; ?>
' id='tablabelid_<?php echo $this->_tpl_vars['tabCounter']; ?>
'  value='<?php echo $this->_tpl_vars['tab']['label']; ?>
'><input type='text' name='tablabel_<?php echo $this->_tpl_vars['tabCounter']; ?>
' id='tablabel_<?php echo $this->_tpl_vars['tabCounter']; ?>
' style='display:none' value='<?php echo smarty_function_sugar_translate(array('label' => $this->_tpl_vars['tab']['label']), $this);?>
' onblur='studiotabs.editTabGroupLabel(<?php echo $this->_tpl_vars['tabCounter']; ?>
, true)'>
<ul id='ul<?php echo $this->_tpl_vars['tabCounter']; ?>
' class='listContainer'>
<?php echo smarty_function_counter(array('start' => 0,'name' => 'subtabCounter','print' => false,'assign' => 'subtabCounter'), $this);?>

<?php $_from = $this->_tpl_vars['tab']['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>

<li id='subslot<?php echo $this->_tpl_vars['tabCounter']; ?>
_<?php echo $this->_tpl_vars['subtabCounter']; ?>
' class='listStyle' name='<?php echo $this->_tpl_vars['list']; ?>
'><span class='slotB' ><?php echo $this->_tpl_vars['availableModuleList'][$this->_tpl_vars['list']]['label']; ?>
</span></li>
<script>subtabModules['subslot<?php echo $this->_tpl_vars['tabCounter']; ?>
_<?php echo $this->_tpl_vars['subtabCounter']; ?>
'] = '<?php echo $this->_tpl_vars['availableModuleList'][$this->_tpl_vars['list']]['label']; ?>
'</script>
<?php echo smarty_function_counter(array('name' => 'subtabCounter'), $this);?>

<?php endforeach; endif; unset($_from); ?>
<li class='noBullet' id='noselectbottom<?php echo $this->_tpl_vars['tabCounter']; ?>
'>&nbsp;</li>
<script>subtabCount[<?php echo $this->_tpl_vars['tabCounter']; ?>
] = <?php echo $this->_tpl_vars['subtabCounter']; ?>
;</script>
</ul>
</div>
<div id='slot<?php echo $this->_tpl_vars['tabCounter']; ?>
b'>
<input type='hidden' name='slot_<?php echo $this->_tpl_vars['tabCounter']; ?>
' id='slot_<?php echo $this->_tpl_vars['tabCounter']; ?>
' value ='<?php echo $this->_tpl_vars['tabCounter']; ?>
'>
<input type='hidden' name='delete_<?php echo $this->_tpl_vars['tabCounter']; ?>
' id='delete_<?php echo $this->_tpl_vars['tabCounter']; ?>
' value ='0'>
</div>
<?php echo smarty_function_counter(array('name' => 'tabCounter'), $this);?>

</td>
<?php endforeach; endif; unset($_from); ?>

</tr>
<tr><td><input type='button' class='button' onclick='addTabGroup()' value='Add Group'></td></tr>
</table>

</td>
</table>



<span class='error'><?php echo $this->_tpl_vars['error']; ?>
</span>



<?php echo '


			<script>
		  function addTabGroup(){
		  	var table = document.getElementById(\'groupTable\');
		  	var rowIndex = table.rows.length - 1;
		  	var rowExists = false;
		  	for(var i = 0; i < rowIndex;i++){
		  		if(table.rows[i].cells.length < 6){
		  			rowIndex = i;
		  			rowExists = true;
		  		}
		  	}
		  	
		  	if(!rowExists)table.insertRow(rowIndex);
		  	cell = table.rows[rowIndex].insertCell(table.rows[rowIndex].cells.length);
		  	cell.className=\'tdContainer\';
		  	cell.vAlign=\'top\';
		  	var slotDiv = document.createElement(\'div\');
		  	slotDiv.id = \'slot\'+ slotCount;
		  	var header = document.createElement(\'h2\');
		  	header.id = \'handle\' + slotCount;
		  	headerSpan = document.createElement(\'span\');
		  	headerSpan.innerHTML = \'New Group\';
		  	headerSpan.id = \'tabname_\' + slotCount;
		  	header.appendChild(headerSpan);
		  	header.appendChild(document.createElement(\'br\'));
		  	headerSpan2 = document.createElement(\'span\');
		  	headerSpan2.id = \'tabother_\' + slotCount;
		  	subspan1 = document.createElement(\'span\');
		  	subspan1.slotCount=slotCount;
		  	subspan1.innerHTML = "';  echo $this->_tpl_vars['editImage'];  echo '&nbsp;";
		  	subspan1.onclick= function(){
		  		studiotabs.editTabGroupLabel(this.slotCount, false);
		  	};
		  	subspan2 = document.createElement(\'span\');
		  	subspan2.slotCount=slotCount;
		  	subspan2.innerHTML = "';  echo $this->_tpl_vars['deleteImage'];  echo '&nbsp;";
		  	subspan2.onclick= function(){
		  		studiotabs.deleteTabGroup(this.slotCount);
		  	};
		  	headerSpan2.appendChild(subspan1);
		  	headerSpan2.appendChild(subspan2);
		  	
		  	var editLabel = document.createElement(\'input\');
		  	editLabel.style.display = \'none\';
		  	editLabel.type = \'text\';
		  	editLabel.value = \'New Group\';
		  	editLabel.id = \'tablabel_\' + slotCount;
		  	editLabel.name = \'tablabel_\' + slotCount;
		  	editLabel.slotCount = slotCount;
		  	editLabel.onblur = function(){
		  		studiotabs.editTabGroupLabel(this.slotCount, true);
		  	}
		  	
		  	
		  	var list = document.createElement(\'ul\');
		  	list.id = \'ul\' + slotCount;
		  	list.className = \'listContainer\';
		  	header.appendChild(headerSpan2);
		  	var li = document.createElement(\'li\');
		  	li.id = \'noselectbottom\' + slotCount;
		  	li.className = \'noBullet\';
		  	li.innerHTML = \'[DROP HERE]\';
		  	list.appendChild(li);
		  	
		  	slotDiv.appendChild(header);
		  	slotDiv.appendChild(editLabel);
		  	slotDiv.appendChild(list);
			var slotB = document.createElement(\'div\');
		  	slotB.id = \'slot\' + slotCount + \'b\';
		  	var slot = document.createElement(\'input\');
		  	slot.type = \'hidden\';
		  	slot.id =  \'slot_\' + slotCount;
		  	slot.name =  \'slot_\' + slotCount; 
		  	slot.value = slotCount;
		  	var deleteSlot = document.createElement(\'input\');
		  	deleteSlot.type = \'hidden\';
		  	deleteSlot.id =  \'delete_\' + slotCount;
		  	deleteSlot.name =  \'delete_\' + slotCount; 
		  	deleteSlot.value = 0;
		  	slotB.appendChild(slot);
		  	slotB.appendChild(deleteSlot);
		  	cell.appendChild(slotDiv);
		  	cell.appendChild(slotB);
		  	
		  	yahooSlots["slot" + slotCount] = new ygDDSlot("slot" + slotCount, "mainTabs");
			yahooSlots["slot" + slotCount].setHandleElId("handle" + slotCount);
		  	yahooSlots["noselectbottom"+ slotCount] = new ygDDListStudio("noselectbottom"+ slotCount , "subTabs", -1);
		  	subtabCount[slotCount] = 0;
		  	slotCount++;
		  	ygDDListStudio.prototype.updateTabs();
		  }
			var gLogger = new ygLogger("Studio");
		  var slotCount = ';  echo $this->_tpl_vars['tabCounter'];  echo ';
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
				for(mj = 0; mj <= slotCount; mj++){
					yahooSlots["slot" + mj] = new ygDDSlot("slot" + mj, "mainTabs");
					yahooSlots["slot" + mj].setHandleElId("handle" + mj);
					
					yahooSlots["noselectbottom"+ mj] = new ygDDListStudio("noselectbottom"+ mj , "subTabs", -1);
					for(msi = 0; msi <= subtabCount[mj]; msi++){
						yahooSlots["subslot"+ mj + \'_\' + msi] = new ygDDListStudio("subslot"+ mj + \'_\' + msi, "subTabs", 0);
						
					}
					
				}
				for(msi = 0; msi <= modCount ; msi++){
						yahooSlots["modSlot"+ msi] = new ygDDListStudio("modSlot" + msi, "subTabs", 1);
						
				}
				var trash1  = new ygDDListStudio("trashcan" , "subTabs", \'trash\');
				ygDDListStudio.prototype.updateTabs();
			
			}
			
			YAHOO.util.DDM.mode = YAHOO.util.DDM.INTERSECT; 
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			
			
</script>	
'; ?>



<div id='logDiv' style='display:none'> 
</div>


	<input type='hidden' name='action' value='SaveTabs'>
	<input type='hidden' name='module' value='Studio'>
</form>

