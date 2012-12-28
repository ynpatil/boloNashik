<?php

require_once("include/modules.php");

global $beanList,$beanFiles;

//if (is_array($beanList))
//print("Bean list is array <br>");

//echo "Module :".$module;

//foreach($beanList as $bean)
//print("Bean :".$bean."<br>");

$entity = $beanList[$module];
//echo "Entity :".$entity;

require_once($beanFiles[$entity]);
$focus = new $entity();

$rows = $focus->get_count($where);

?>
