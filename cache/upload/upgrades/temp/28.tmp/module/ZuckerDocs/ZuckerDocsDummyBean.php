<?php
require_once('data/SugarBean.php');

class ZuckerDocsDummyBean extends SugarBean {
   var $new_schema = true;

   function ZuckerDocsDummyBean() {
   }

   function retrieve($id = -1, $encode=true) {
   }
   function save($check_notify = FALSE) {
   }
   function create_tables() {
   }
}

?>
