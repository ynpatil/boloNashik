<?php
if (isset($sugar_config)) {
 if ($sugar_config['sugar_version'] >= '4.2.0b') {
  if (!defined('sugarEntry') || !sugarEntry) {
   die('Not A Valid Entry Point');
  }
 }else{
  if (empty($GLOBALS['sugarEntry'])) {
   die('Not A Valid Entry Point');
  }
 }
}
?>
