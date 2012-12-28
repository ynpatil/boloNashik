<?php
	$request =  'http://10.100.109.253/crm/servlet/crm.SAPAccountSearch';
        $postargs = 'source=crm_master_CompanyDetailsSAP';
        $postargs .= "&NAME1=".$_POST['NAME1']."&ISPADRBSND=".$_POST['ISPADRBSND'];
 
        // Get the curl session object
        $session = curl_init($request);
 
        // Set the POST options.
        curl_setopt ($session, CURLOPT_POST, true);
        curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
 
        // Do the POST and then close the session
        $response = curl_exec($session);
	echo $response;
?>
