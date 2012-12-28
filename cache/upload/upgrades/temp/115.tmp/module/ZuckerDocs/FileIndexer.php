<?php
	echo "<h1>FileIndexer doesn't work with this release!</h1><br />";
	echo "<h2>Please use the KnowledgeTree UI for doing file indexing!</h2><br />";

	/*

	require_once("modules/ZuckerDocs/dms/conf.inc");
	
	if ($dmsUseSugarMysqlServer) {
		global $sugar_config;
		$dbHost = $sugar_config['dbconfig']['db_host_name'];
		$dbName = $dmsDbName;
		$dbUser = $sugar_config['dbconfig']['db_user_name'];
		$dbPwd = $sugar_config['dbconfig']['db_password'];
	} else {
		$dbHost = $dmsDbHost;
		$dbName = $dmsDbName;
		$dbUser = $dmsDbUser;
		$dbPwd = $dmsDbPwd;
	}
	

	$f = fopen("modules/ZuckerDocs/fileIndexer/KTDataSource.xml", "w");
	fwrite($f, "<mysql>\n");
	fwrite($f, " <server value=\"".$dbHost."\"/>\n");
	fwrite($f, " <database value=\"".$dbName."\"/>\n");
	fwrite($f, " <user value=\"".$dbUser."\"/>\n");
	fwrite($f, " <pwd value=\"".$dbPwd."\"/>\n");
	fwrite($f, " <documentRoot value=\"". realpath($dmsRootDir)."\"/>\n");
	fwrite($f, "</mysql>\n");
	fclose($f);
	$f = fopen("modules/ZuckerDocs/fileIndexer/KTDataStore.xml", "w");
	fwrite($f, "<mysql>\n");
	fwrite($f, " <server value=\"".$dbHost."\"/>\n");
	fwrite($f, " <database value=\"".$dbName."\"/>\n");
	fwrite($f, " <user value=\"".$dbUser."\"/>\n");
	fwrite($f, " <pwd value=\"".$dbPwd."\"/>\n");
	fwrite($f, " <updatepermissions value=\"0\"/>\n");
	fwrite($f, "</mysql>\n");
	fclose($f);
	
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$cmdline = 'javaw -jar FileIndexer-1.1.5.jar 2>&1';
	} else {
		$cmdline = "java -Djava.awt.headless=true -jar FileIndexer-1.1.5.jar 2>&1";
	}
	chdir('modules/ZuckerDocs/fileIndexer');
	exec($cmdline, $output, &$return_var);		
	unlink("KTDataSource.xml");
	unlink("KTDataStore.xml");

	echo join("<br/>", $output);	
	
	*/
	
?>