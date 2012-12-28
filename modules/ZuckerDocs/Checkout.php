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

require_once('include/formbase.php');
require_once('dms/sugarprovider.inc');

$docId = $_POST['record'];
$comment = $_POST['comment'];
$doc = KT_SugarProvider::getDocument($docId);
if (!isDocumentsError($doc)) {
	$res = KT_SugarProvider::checkoutDocument($docId, $comment);
	if (!isDocumentsError($res)) {
		$_REQUEST['DMS_MANAGE_SCRIPT'] = '<script type="text/javascript">f1 = window.open("download.php?module=ZuckerDocs&action=ViewDocument&record='.$doc->id.'", "zuckerdocs_download");</script>';
	} else {
		$err = $res;
	}
} else {
	$err = $doc;	
}
if (isset($err)) {
	$_REQUEST['DMS_MANAGE_ERROR'] = KT_SugarProvider::formatError($err);
}
include('modules/ZuckerDocs/DocView.php');
?>
