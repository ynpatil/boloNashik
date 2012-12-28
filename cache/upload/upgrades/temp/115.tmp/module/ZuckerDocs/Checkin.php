<?
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
require_once('modules/ZuckerDocs/ZuckerDocument.php');

$docId = $_REQUEST['record'];
$comment = $_REQUEST['comment'];
$major = $_REQUEST['major'];

$doc = KT_SugarProvider::getDocument($docId);
if (!isDocumentsError($doc)) {
	if (array_key_exists("contents", $_REQUEST)) {
		$res = KT_SugarProvider::checkinDocument($docId, unhtmlentities($_REQUEST['contents']), $comment, !empty($major));
		if (isDocumentsError($res)) {
			$err = $res;
		}
	} else if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
		$filename = $_FILES['uploadfile']['name'];
		if ($filename == $doc->filename) {
			$contents = file_get_contents($_FILES['uploadfile']['tmp_name']);
			$res = KT_SugarProvider::checkinDocument($docId, $contents, $comment, !empty($major));
			if (isDocumentsError($res)) {
				$err = $res;
			}
		} else {
			$err = new KT_DocumentsError(DOCERROR_INVALIDFILENAME, $doc->filename, $filename);
		}
	} else {
		$err = new KT_DocumentsError(DOCERROR_FILENOTUPLOADED);
	}
} else {
	$err = $doc;
}
if (isset($err)) {
	$_REQUEST['DMS_MANAGE_ERROR'] = KT_SugarProvider::formatError($err);
}

include('modules/ZuckerDocs/DocView.php');
?>
