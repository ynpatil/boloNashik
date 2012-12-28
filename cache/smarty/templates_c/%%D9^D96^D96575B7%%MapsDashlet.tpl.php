<?php /* Smarty version 2.6.11, created on 2007-02-06 09:19:12
         compiled from custom/modules/Home/Dashlets/MapsDashlet/MapsDashlet.tpl */ ?>
<form name="EditView" method="POST" action="index.php">
<select id='maps_mapping_type_<?php echo $this->_tpl_vars['id']; ?>
' onChange="selectDiv();"><option value="closest">Accounts within radius</option><option value="find">Map Account/Contact</option></select>&nbsp;<div id='maps_find_div_<?php echo $this->_tpl_vars['id']; ?>
'><select id='maps_type_<?php echo $this->_tpl_vars['id']; ?>
' onChange="changeQS();"><option value="Accounts">Accounts</option><option value="Contacts">Contacts</option></select>&nbsp;<input id='maps_input_<?php echo $this->_tpl_vars['id']; ?>
' style='width: 25%; overflow: auto'  class="sqsEnabled" value='<?php echo $this->_tpl_vars['name']; ?>
'><input id='maps_input_id_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden"><input id='maps_input_primary_address_street_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden"><input id='maps_input_primary_address_city_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden"><input id='maps_input_primary_address_state_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden"><input id='maps_input_primary_address_postalcode_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden"><input id='maps_input_primary_address_country_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden"><input id='maps_input_phone_work_<?php echo $this->_tpl_vars['id']; ?>
' type="hidden">&nbsp;<input title='Select' tabindex='2' accessKey='Select' type='button' class='button' value='Select' id='maps_input_select_<?php echo $this->_tpl_vars['id']; ?>
' name='maps_input_select_<?php echo $this->_tpl_vars['id']; ?>
' onclick='Maps.openPopup();' /></div><div id='maps_closest_div_<?php echo $this->_tpl_vars['id']; ?>
'><?php echo $this->_tpl_vars['zipLbl']; ?>
:&nbsp;<input id='maps_input_my_address_<?php echo $this->_tpl_vars['id']; ?>
'>&nbsp;<?php echo $this->_tpl_vars['radiusLbl']; ?>
:&nbsp;<select id='maps_input_my_dist_<?php echo $this->_tpl_vars['id']; ?>
'><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select></div>&nbsp;<input id='maps_submit_<?php echo $this->_tpl_vars['id']; ?>
' type="button" class='button' onclick='Maps.click("<?php echo $this->_tpl_vars['id']; ?>
")' value='Map'>
<div id='maps_output_num_found_<?php echo $this->_tpl_vars['id']; ?>
' style='width: 100%; border: 1px #ddd solid'></div>
<div id='maps_output_<?php echo $this->_tpl_vars['id']; ?>
' style='width: 100%; height: <?php echo $this->_tpl_vars['height']; ?>
px; border: 1px #ddd solid'><?php echo $this->_tpl_vars['mapsOutput']; ?>
</div>
</form>
<?php echo '
<script>
selectDiv();
';  if ($this->_tpl_vars['displayOnStartup'] == 'true'):  echo '
Maps.drawMap(\'';  echo $this->_tpl_vars['id'];  echo '\', true);
';  endif;  echo '
</script>'; ?>