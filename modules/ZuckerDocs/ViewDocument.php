<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * ZuckerDocs by go-mobile
 * Copyright (C) 2005 Florian Treml, go-mobile
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
 * Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not,
 * write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
//#$# Start
//Changes for enabling Logging in PHP
require_once('include/logging.php');
global $dmsLog;                                                                                     
$dmsLog = &LoggerManager::getLogger('dms');                                           
require_once('dms/conf.inc');
require_once('dms/db.inc');
$dmsLog->debug("#$# *********** Inside ViewDocument.php Test Log in sugarcrm.log ***********"); 
// #$# End

// #$# sole cause of not getting any log messages
$dmsQuietMode = false;
require_once('dms/sugarprovider.inc');

$docId = $_REQUEST['record'];
$version = $_REQUEST['version'];
$mode = $_REQUEST['mode'];

$enableIE6Hotfix = FALSE; 
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') && strpos($_SERVER['HTTP_USER_AGENT'], 'SV1')) { 
	$enableIE6Hotfix = TRUE; 
	ini_set( 'zlib.output_compression','Off' ); 
} 

global $current_language; 
$current_module_strings = return_module_language($current_language, 'ZuckerDocs'); 

$doc = KT_SugarProvider::getDocument($docId); 
if (!isDocumentsError($doc)) { 
	$contents = KT_SugarProvider::getDocumentContents($docId, $version); 
	if (!isDocumentsError($contents)) {
	
		if ($dmsDownloadUseTitle) {

			$doc_name = $doc->name;
			$doc_filename = $doc->filename;
			
			if (strstr($doc_name, ".")) $doc_name = substr($doc_name, 0, strrpos($doc_name, '.'));
			if (strstr($doc_filename, ".")) $extension = ".".strtolower(substr($doc_filename, strrpos($doc_filename, '.') + 1));
			
			$filename = $doc_name.$extension;
		} else {
			$filename = $doc->filename;
		}
	
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		
		if ($mode == "attachment") {
			header("Content-Type: ".$doc->mimetype);
			header("Content-Length: ".strlen($contents));
			header("Content-Disposition: attachment; filename=\"".$filename."\";");
			header("Content-Transfer-Encoding: binary");
		} else {
			$disp="attachment"; 
			if ($enableIE6Hotfix = TRUE){
				switch( $doc->mimetype ) { 
				case "application/pdf": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "application/msword": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "application/vnd.ms-excel": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "application/vnd.ms-powerpoint": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "text/html": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "image/gif": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "image/tiff": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "image/png": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "image/jpeg": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				case "image/jpg": $disp="inline"; header("Content-Type: ".$doc->mimetype);break; 
				default: header("Content-Type: application/force-download"); 
				} 
			} 
			header("Content-Disposition: ".$disp."; filename=\"" . $filename."\""); 
			header("Content-Length: ".strlen($contents));
		}
			
		echo $contents; 
		
		// $#$ query added by surya on 17th May 2006 to update the record of document searchable and transaction text table
		$sql = updateTransactionText($docId, "download");
		dmsQuery($sql);   

		$sql = updateSearchableText($docId, "download",$doc->filename, $doc->description);
		dmsQuery($sql);   

		die; 
	} else { 
		echo KT_SugarProvider::formatError($contents); 
	} 
} else { 
	echo KT_SugarProvider::formatError($doc); 
} 
?> 