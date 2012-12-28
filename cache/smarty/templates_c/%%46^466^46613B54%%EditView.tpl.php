<?php /* Smarty version 2.6.11, created on 2012-08-08 17:32:55
         compiled from modules/Studio/DropDowns/EditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'modules/Studio/DropDowns/EditView.tpl', 56, false),array('function', 'counter', 'modules/Studio/DropDowns/EditView.tpl', 84, false),array('modifier', 'default', 'modules/Studio/DropDowns/EditView.tpl', 105, false),)), $this); ?>


<?php echo '
<br>	
<style type=\'text/css\'>
.slot {
	border-width:1px;border-color:#999999;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}

.slotB {
	border-width:0;cursor:move;

}
</style>
'; ?>

<?php echo $this->_tpl_vars['buttons']; ?>

<table class='tabForm'>
<tr><td>
<span class='error'><?php echo $this->_tpl_vars['error']; ?>
</span>
<table >
<tr><td colspan='2'>
<?php if (empty ( $this->_tpl_vars['newDropDown'] )): ?>
<form method='post' action='index.php' name='dropdownsform'>
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='wizard' value='EditDropDownWizard'>
<input type='hidden' name='option' value='EditDropdown'>
<input type='hidden' name='module' value='Studio'>
<?php echo smarty_function_html_options(array('name' => 'dropdown_module','options' => $this->_tpl_vars['dropdown_modules'],'selected' => $this->_tpl_vars['dropdown_module'],'onchange' => "document.dropdownsform.submit();"), $this);?>

<?php echo smarty_function_html_options(array('name' => 'dropdown_name','output' => $this->_tpl_vars['dropdowns'],'values' => $this->_tpl_vars['dropdowns'],'selected' => $this->_tpl_vars['dropdown_name'],'onchange' => "document.dropdownsform.submit();"), $this);?>

<?php echo smarty_function_html_options(array('name' => 'dropdown_lang','options' => $this->_tpl_vars['dropdown_languages'],'selected' => $this->_tpl_vars['dropdown_lang'],'onchange' => "document.dropdownsform.submit();"), $this);?>

</form>
<?php endif; ?>
</td></tr>
</table>
</td></tr><tr><td>
<?php if (! empty ( $this->_tpl_vars['dropdown'] ) || ! empty ( $this->_tpl_vars['newDropDown'] )): ?>
<form method='post' action='index.php' name='editdropdown'>
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='wizard' value='EditDropDownWizard'>
<input type='hidden' name='option' value='SaveDropDown'>
<input type='hidden' name='module' value='Studio'>
<input type='hidden' name='dropdown_module' value='<?php echo $this->_tpl_vars['dropdown_module']; ?>
'>
<input type='hidden' name='dropdown_lang' value='<?php echo $this->_tpl_vars['dropdown_lang']; ?>
'>
<?php if (empty ( $this->_tpl_vars['newDropDown'] )): ?>
<input type='hidden' name='dropdown_name' value='<?php echo $this->_tpl_vars['dropdown_name']; ?>
'>
<?php else: ?>
<table><tr><td>
Dropdown Name:
</td><td><input type='text' name='dropdown_name' value='<?php echo $this->_tpl_vars['dropdown_name']; ?>
'>
</td></tr><tr><td>
Dropdown Language:</td><td><?php echo smarty_function_html_options(array('name' => 'dropdown_lang','options' => $this->_tpl_vars['dropdown_languages'],'select' => $this->_tpl_vars['dropdown_lang']), $this);?>

</td></tr></table>
<?php endif; ?>
<table name='tabDropdown' id='tabDropdown'>
<tr><td><?php echo $this->_tpl_vars['MOD']['LBL_DD_DATABASEVALUE']; ?>
<hr></td><td><?php echo $this->_tpl_vars['MOD']['LBL_DD_DISPALYVALUE']; ?>
<hr></td></tr>
<?php echo smarty_function_counter(array('start' => 0,'name' => 'rowCounter','print' => false,'assign' => 'rowCounter'), $this);?>

<?php $_from = $this->_tpl_vars['dropdown']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>

<tr><td>
<span id='slot<?php echo $this->_tpl_vars['rowCounter']; ?>
' class='slot' style='cursor: move'>
    <span id='slot<?php echo $this->_tpl_vars['rowCounter']; ?>
_key'><?php echo $this->_tpl_vars['key']; ?>
</span>
</span>
</td><td>
<span id='slot<?php echo $this->_tpl_vars['rowCounter']; ?>
b' >
    <span onclick='deleteDropDownValue(<?php echo $this->_tpl_vars['rowCounter']; ?>
, document.getElementById("delete_<?php echo $this->_tpl_vars['rowCounter']; ?>
"), true);'>
        <?php echo $this->_tpl_vars['deleteImage']; ?>

    </span>
    <span onclick='prepChangeDropDownValue(<?php echo $this->_tpl_vars['rowCounter']; ?>
, document.getElementById("slot<?php echo $this->_tpl_vars['rowCounter']; ?>
_value"));'><?php echo $this->_tpl_vars['editImage']; ?>
</span>
        &nbsp;
    <span id ='slot<?php echo $this->_tpl_vars['rowCounter']; ?>
_value' onclick='prepChangeDropDownValue(<?php echo $this->_tpl_vars['rowCounter']; ?>
, this);'><?php echo $this->_tpl_vars['value']['lang']; ?>
</span>
    <span id='slot<?php echo $this->_tpl_vars['rowCounter']; ?>
_textspan' style='display:none'>
        <input id='slot<?php echo $this->_tpl_vars['rowCounter']; ?>
_text' value='' type='text'  onchange='setDropDownValue(<?php echo $this->_tpl_vars['rowCounter']; ?>
, this.value, true)' >
        <?php echo $this->_tpl_vars['value']['user_lang']; ?>

    </span>
     <input name='slot_<?php echo $this->_tpl_vars['rowCounter']; ?>
' id='slot_<?php echo $this->_tpl_vars['rowCounter']; ?>
' value='<?php echo $this->_tpl_vars['rowCounter']; ?>
' type = 'hidden'> 
    <input name='value_<?php echo $this->_tpl_vars['rowCounter']; ?>
' id='value_<?php echo $this->_tpl_vars['rowCounter']; ?>
' value='<?php echo $this->_tpl_vars['value']['lang']; ?>
' type = 'hidden'>
    <input type='hidden' name='key_<?php echo $this->_tpl_vars['rowCounter']; ?>
' id='key_<?php echo $this->_tpl_vars['rowCounter']; ?>
' value='<?php echo ((is_array($_tmp=@$this->_tpl_vars['key'])) ? $this->_run_mod_handler('default', true, $_tmp, 'BLANK') : smarty_modifier_default($_tmp, 'BLANK')); ?>
'>
    <input type='hidden' id='delete_<?php echo $this->_tpl_vars['rowCounter']; ?>
' name='delete_<?php echo $this->_tpl_vars['rowCounter']; ?>
' value='0'>
 </span>

    </td></tr>
<?php echo smarty_function_counter(array('name' => 'rowCounter'), $this);?>

<?php endforeach; endif; unset($_from); ?>
<tr><td><input type='text' name='addKey' id='addKey' value=''></td><td><input type='text' name='addValue' id='addValue' value=''><input type='button' onclick='addDropDown();' value='+' class='button'></td>

</table>

<?php echo '
<script type="text/javascript" src="modules/Studio/JSTransaction.js" ></script>
			<script>
			var jstransaction = new JSTransaction();
			</script>
<script src = "include/javascript/yui/dragdrop.js" ></script>
				 	
			<script type="text/javascript" src="modules/Studio/studiodd.js" ></script>	
			<script type="text/javascript" src="modules/Studio/studio.js" ></script>	
			<script>
			var lastField = \'\';
			var lastRowCount = -1;
			var undoDeleteDropDown = function(transaction){
			    deleteDropDownValue(transaction[\'row\'], document.getElementById(transaction[\'id\']), false);
			}
			jstransaction.register(\'deleteDropDown\', undoDeleteDropDown, undoDeleteDropDown);
			function deleteDropDownValue(rowCount, field, record){
			    if(record){
			        jstransaction.record(\'deleteDropDown\',{\'row\':rowCount, \'id\': field.id });
			    }
			    //We are deleting if the value is 0
			    if(field.value == \'0\'){
			        field.value = \'1\';
			        document.getElementById(\'slot\' + rowCount + \'_key\').style.textDecoration = \'line-through\';
			        document.getElementById(\'slot\' + rowCount + \'_value\').style.textDecoration = \'line-through\';
			    }else{
			        field.value = \'0\';
			        document.getElementById(\'slot\' + rowCount + \'_key\').style.textDecoration = \'none\';
			        document.getElementById(\'slot\' + rowCount + \'_value\').style.textDecoration = \'none\';
			    }
			    
			   
			}
			function prepChangeDropDownValue(rowCount, field){
			  
			    var tempLastField = lastField;
			     if(lastRowCount != -1){
			        setDropDownValue(lastRowCount, lastField.innerHTML, true);
			    }
			     if(tempLastField == field)return;
			    lastField = field;
			    lastRowCount = rowCount;
			    
			    field.style.display="none";
			    
			    var textspan =  document.getElementById(\'slot\' + rowCount + \'_textspan\');
			    var text = document.getElementById("slot" + rowCount + "_text");
			    text.value=field.innerHTML;
			    textspan.style.display=\'inline\'
			    text.focus();
			}
			var undoDropDownChange = function(transaction){
			    setDropDownValue(transaction[\'row\'], transaction[\'old\'], false);
			}
			var redoDropDownChange = function(transaction){
			    setDropDownValue(transaction[\'row\'], transaction[\'new\'], false);
			}
			jstransaction.register(\'changeDropDownValue\', undoDropDownChange, redoDropDownChange);
			function setDropDownValue(rowCount, val, record){
			  
			    var key = document.getElementById(\'slot\' + rowCount + \'_key\').innerHTML;
			    if(key == \'\'){
			        key = \'BLANK\';
			    }
			    if(record){
			        jstransaction.record(\'changeDropDownValue\', {\'row\':rowCount, \'new\':val, \'old\':document.getElementById(\'value_\'+ rowCount).value});
			    }
			    document.getElementById(\'value_\' + rowCount).value = val;
			    var text =  document.getElementById(\'slot\' + rowCount + \'_text\');
			    var textspan =  document.getElementById(\'slot\' + rowCount + \'_textspan\');
			    var span = document.getElementById(\'slot\' + rowCount + \'_value\');
			    span.innerHTML  = val;
			    textspan.style.display = \'none\';
			    text.value = \'\';
			    span.style.display = \'inline\';
			    lastField = \'\';
			    lastRowCount = -1;
			    
			}

		  function addDropDown(){
		      var addKey =  document.getElementById(\'addKey\');
		      var keyValue = addKey.value;
		      if(trim(keyValue) == \'\'){
		          keyValue = \'BLANK\';
		      }
		      var addValue =  document.getElementById(\'addValue\')
		      for(var i = 0; i < slotCount ; i++){
		          if(typeof(document.getElementById(\'key_\' + i)) != \'undefined\'){
		              if(document.getElementById(\'key_\' + i).value == keyValue){
		                  alert(\'key already exists\');
		                  return;
		              }
		          }
		      }
		      var table = document.getElementById(\'tabDropdown\');
		      var row = table.insertRow(table.rows.length - 1);
			  var cell = row.insertCell(0);
			  var cell2 = row.insertCell(1);
			  
			 
			 
			  var span1 = document.createElement(\'span\');
			  span1.id = \'slot\' + slotCount;
			  span1.className = \'slot\';
			  var keyspan = document.createElement(\'span\');
			  keyspan.id = \'slot\' + slotCount + \'_key\'
			  keyspan.innerHTML  = addKey.value;
			  span1.appendChild(keyspan);
			  var span2 = document.createElement(\'span\');
			  span2.id = \'slot\' + slotCount + \'b\';
			  var delimage = document.createElement(\'span\');
			  delimage.innerHTML = "';  echo $this->_tpl_vars['deleteImage'];  echo '&nbsp;";
			  delimage.slotCount = slotCount
			  delimage.recordKey = keyValue;
			  delimage.onclick = function(){
			      deleteDropDownValue(this.slotCount, document.getElementById( \'delete_\' + this.recordKey), true);
			  };
			  var span2image = document.createElement(\'span\');
			  span2image.innerHTML = "';  echo $this->_tpl_vars['editImage'];  echo '&nbsp;";
			  span2image.slotCount = slotCount
			  span2image.onclick = function(){
			      prepChangeDropDownValue(this.slotCount, document.getElementById(\'slot\' + this.slotCount + \'_value\'));
			  };
			  var span2inner = document.createElement(\'span\');
			  span2inner.innerHTML = addValue.value;
			  span2inner.id = \'slot\' + slotCount + \'_value\';
			  span2inner.slotCount = slotCount
			  span2inner.onclick = function(){
			      prepChangeDropDownValue(this.slotCount, this);
			  };
			  var text2span = document.createElement(\'span\');
			  text2span.id = \'slot\' + slotCount + \'_textspan\'
			  text2span.style.display = \'none\';
			  
			  
			  var text2 = document.createElement(\'input\');
			  text2.type = \'text\';
			  text2.id = \'slot\' + slotCount + \'_text\'
			  
			  text2.slotCount = slotCount;
			  text2.onchange = function(){
			      setDropDownValue(this.slotCount, this.value, true);
			  }
			   
			  
			  var text3 = document.createElement(\'input\');
			  text3.type = \'hidden\';
			  text3.id = \'value_\' + slotCount;
			  text3.name = \'value_\' + slotCount;
			  text3.value = addValue.value;
			  
			  var text4 = document.createElement(\'input\');
			  text4.type = \'hidden\';
			  text4.id = \'key_\' + slotCount;
			  text4.name = \'key_\' + slotCount;
			  text4.value = keyValue;
               var text5 = document.createElement(\'input\');
			  text5.type = \'hidden\';
			  text5.id = \'delete_\' + slotCount  ;
			  text5.name = \'delete_\' + slotCount  ;
			  text5.value = \'0\';
			  var text6 = document.createElement(\'input\');
			  text6.type = \'hidden\';
			  text6.id = \'slot_\' + slotCount;
			  text6.name = \'slot_\' + slotCount;
			  text6.value = slotCount;
			  cell.appendChild(span1);
			  span2.appendChild(delimage);
			  span2.appendChild(span2image);
			  span2.appendChild(span2inner);
			  cell2.appendChild(span2);
			  text2span.appendChild(text2);
			  cell2.appendChild(text2span);
			  cell2.appendChild(text3);
			  cell2.appendChild(text4);
			  cell2.appendChild(text5);
			  cell2.appendChild(text6);
			  addKey.value = \'\';
			  addValue.value = \'\';
			  yahooSlots["slot" + slotCount] = new ygDDSlot("slot" + slotCount, "studio");
			   slotCount++;
		  }
			var gLogger = new ygLogger("Studio");
		  var slotCount = ';  echo $this->_tpl_vars['rowCounter'];  echo ';

			 var yahooSlots = [];
			function dragDropInit(){
					if (typeof(ygLogger) != "undefined") {
				ygLogger.init(document.getElementById("logDiv"));
			}
				YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
				
				 gLogger.debug("point mode");
				for(mj = 0; mj <= slotCount; mj++){
					yahooSlots["slot" + mj] = new ygDDSlot("slot" + mj, "studio");
				}
				  // initPointMode();
			}
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			
			
</script>	
'; ?>



<div id='logDiv' style='display:none'> 
</div>

<?php endif; ?>
</form>
</td></tr>
</table>