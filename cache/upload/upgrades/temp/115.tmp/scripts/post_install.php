<?php


function post_install( ) {
	echo "<h1>Please follow these steps to complete installation</h1><br/><ul>";
	echo "<li>Add the line <em><? include(\"modules/ZuckerDocs/SubPanelView.php\"); ?></em> at the very bottom of the \"DetailView.php\" in every module you want to have documents managed by ZuckerDocs</li>";
	echo "<li>You will find a new \"Documents\" section in the DetailView for those objects</li>";
	echo "</ul>";
}

?>
