{*

/**
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 */

// $Id: EditViewTabs.tpl,v 1.5 2006/08/23 00:15:55 awu Exp $

*}


{literal}
<br>
<form name='edittabs' id='edittabs' method='POST' action='index.php'>	
<script type="text/javascript" src="modules/Studio/JSTransaction.js" ></script>
<script>
	var jstransaction = new JSTransaction();
</script>
<script src = "include/javascript/yui/dragdrop.js" ></script>
<script src='modules/Studio/studiotabgroups.js'></script>
<script src = "modules/Studio/ygDDListStudio.js" ></script>				 	
<script type="text/javascript" src="modules/Studio/studiodd.js" ></script>	
<script type="text/javascript" src="modules/Studio/studio.js" ></script>	
<style type='text/css'>
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
{/literal}
{$buttons}

<table><tr><td valign='top' class='tabForm' nowrap>
<table  cellpadding="0" cellspacing="0" border="1" width="100%"   id='s_field_delete'>
							<tr><td ><ul id='trash' class='listContainer'>
<li class='nobullet' id='trashcan'>{$deleteImage}&nbsp;Delete&nbsp;Module</li>

</ul>
						</td></tr></table>


<div class='noBullet'><h2>{$MOD.LBL_MODULES}</h2>
<ul class='listContainer'>
{counter start=0 name="modCounter" print=false assign="modCounter"}
{foreach from=$availableModuleList key='key' item='value'}


<li  id='modSlot{$modCounter}'><span class='slotB'>{$value.label}</span></li>
<script>
tabLabelToValue['{$value.label}'] = '{$value.value}';
subtabModules['modSlot{$modCounter}'] = '{$value.label}'</script>
{counter name="modCounter"}
{/foreach}
</ul>
</td>
<td valign='top' nowrap>
<table class='tableContainer' id='groupTable'><tr>
{counter start=0 name="tabCounter" print=false assign="tabCounter"}

{foreach from=$tabs item='tab' key='tabName'}
{if $tabCounter > 0 && $tabCounter % 6 == 0}
</tr><tr>
{/if}
<td valign='top' class='tdContainer'>
<div id='slot{$tabCounter}' class='noBullet'><h2 id='handle{$tabCounter}' ><span id='tabname_{$tabCounter}' class='slotB'>{sugar_translate label=$tab.label}</span><br><span id='tabother_{$tabCounter}'><span onclick='studiotabs.editTabGroupLabel({$tabCounter}, false)'>{$editImage}</span>&nbsp;<span onclick='studiotabs.deleteTabGroup({$tabCounter})'>{$deleteImage}</span></span></h2><input type='hidden' name='tablabelid_{$tabCounter}' id='tablabelid_{$tabCounter}'  value='{$tab.label}'><input type='text' name='tablabel_{$tabCounter}' id='tablabel_{$tabCounter}' style='display:none' value='{sugar_translate label=$tab.label}' onblur='studiotabs.editTabGroupLabel({$tabCounter}, true)'>
<ul id='ul{$tabCounter}' class='listContainer'>
{counter start=0 name="subtabCounter" print=false assign="subtabCounter"}
{foreach from=$tab.modules item='list'}

<li id='subslot{$tabCounter}_{$subtabCounter}' class='listStyle' name='{$list}'><span class='slotB' >{$availableModuleList[$list].label}</span></li>
<script>subtabModules['subslot{$tabCounter}_{$subtabCounter}'] = '{$availableModuleList[$list].label}'</script>
{counter name="subtabCounter"}
{/foreach}
<li class='noBullet' id='noselectbottom{$tabCounter}'>&nbsp;</li>
<script>subtabCount[{$tabCounter}] = {$subtabCounter};</script>
</ul>
</div>
<div id='slot{$tabCounter}b'>
<input type='hidden' name='slot_{$tabCounter}' id='slot_{$tabCounter}' value ='{$tabCounter}'>
<input type='hidden' name='delete_{$tabCounter}' id='delete_{$tabCounter}' value ='0'>
</div>
{counter name="tabCounter"}
</td>
{/foreach}

</tr>
<tr><td><input type='button' class='button' onclick='addTabGroup()' value='Add Group'></td></tr>
</table>

</td>
</table>



<span class='error'>{$error}</span>



{literal}


			<script>
		  function addTabGroup(){
		  	var table = document.getElementById('groupTable');
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
		  	cell.className='tdContainer';
		  	cell.vAlign='top';
		  	var slotDiv = document.createElement('div');
		  	slotDiv.id = 'slot'+ slotCount;
		  	var header = document.createElement('h2');
		  	header.id = 'handle' + slotCount;
		  	headerSpan = document.createElement('span');
		  	headerSpan.innerHTML = 'New Group';
		  	headerSpan.id = 'tabname_' + slotCount;
		  	header.appendChild(headerSpan);
		  	header.appendChild(document.createElement('br'));
		  	headerSpan2 = document.createElement('span');
		  	headerSpan2.id = 'tabother_' + slotCount;
		  	subspan1 = document.createElement('span');
		  	subspan1.slotCount=slotCount;
		  	subspan1.innerHTML = "{/literal}{$editImage}{literal}&nbsp;";
		  	subspan1.onclick= function(){
		  		studiotabs.editTabGroupLabel(this.slotCount, false);
		  	};
		  	subspan2 = document.createElement('span');
		  	subspan2.slotCount=slotCount;
		  	subspan2.innerHTML = "{/literal}{$deleteImage}{literal}&nbsp;";
		  	subspan2.onclick= function(){
		  		studiotabs.deleteTabGroup(this.slotCount);
		  	};
		  	headerSpan2.appendChild(subspan1);
		  	headerSpan2.appendChild(subspan2);
		  	
		  	var editLabel = document.createElement('input');
		  	editLabel.style.display = 'none';
		  	editLabel.type = 'text';
		  	editLabel.value = 'New Group';
		  	editLabel.id = 'tablabel_' + slotCount;
		  	editLabel.name = 'tablabel_' + slotCount;
		  	editLabel.slotCount = slotCount;
		  	editLabel.onblur = function(){
		  		studiotabs.editTabGroupLabel(this.slotCount, true);
		  	}
		  	
		  	
		  	var list = document.createElement('ul');
		  	list.id = 'ul' + slotCount;
		  	list.className = 'listContainer';
		  	header.appendChild(headerSpan2);
		  	var li = document.createElement('li');
		  	li.id = 'noselectbottom' + slotCount;
		  	li.className = 'noBullet';
		  	li.innerHTML = '[DROP HERE]';
		  	list.appendChild(li);
		  	
		  	slotDiv.appendChild(header);
		  	slotDiv.appendChild(editLabel);
		  	slotDiv.appendChild(list);
			var slotB = document.createElement('div');
		  	slotB.id = 'slot' + slotCount + 'b';
		  	var slot = document.createElement('input');
		  	slot.type = 'hidden';
		  	slot.id =  'slot_' + slotCount;
		  	slot.name =  'slot_' + slotCount; 
		  	slot.value = slotCount;
		  	var deleteSlot = document.createElement('input');
		  	deleteSlot.type = 'hidden';
		  	deleteSlot.id =  'delete_' + slotCount;
		  	deleteSlot.name =  'delete_' + slotCount; 
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
		  var slotCount = {/literal}{$tabCounter}{literal};
		  var modCount = {/literal}{$modCounter}{literal};
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
						yahooSlots["subslot"+ mj + '_' + msi] = new ygDDListStudio("subslot"+ mj + '_' + msi, "subTabs", 0);
						
					}
					
				}
				for(msi = 0; msi <= modCount ; msi++){
						yahooSlots["modSlot"+ msi] = new ygDDListStudio("modSlot" + msi, "subTabs", 1);
						
				}
				var trash1  = new ygDDListStudio("trashcan" , "subTabs", 'trash');
				ygDDListStudio.prototype.updateTabs();
			
			}
			
			YAHOO.util.DDM.mode = YAHOO.util.DDM.INTERSECT; 
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			
			
</script>	
{/literal}


<div id='logDiv' style='display:none'> 
</div>


	<input type='hidden' name='action' value='SaveTabs'>
	<input type='hidden' name='module' value='Studio'>
</form>


