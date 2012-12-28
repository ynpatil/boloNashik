<?php
require_once('modules/ZuckerDocs/ZuckerDocument.php');
$mod_strings = return_module_language($current_language, 'ZuckerDocs');

function dmsToSoapFault($e) {
	return new soap_fault($e->errorCode, "dms", KT_SugarProvider::formatError($e));
}

function getStorageInfo() {
	return "ZuckerDocs ".ZUCKERDOCS_VERSION;
}
function getStorageCaps() {
	return "attach,lock,history,folders";
}

function convert_document_array($doc) {
	$result = array(
		"id" => $doc->id,
		"filename" => $doc->filename,
		"last_modified" => $doc->modified,
		"description" => $doc->description,
		"parent_type" => $doc->parent_type,
		"parent_id" => $doc->parent_id,
		"parent_name" => $doc->parent_name,
		"cat_name" => $doc->cat_name,
		"status" => ($doc->status." - ".$doc->version),
		);
	return $result;
}
function convert_folder_array($folder) {
	$result = array(
		"id" => $folder->id,
		"name" => $folder->name,
		"description" => $folder->description,
		);
	return $result;
}


function convert_history_array($hist) {
	$result = array(
		"version" => $hist->version,
		"user" => $hist->user,
		"datetime" => $hist->datetime,
		"comment" => $hist->comment,
		"type" => $hist->type,
		);
	return $result;
}

function get_folder_contents_impl($folder_id) {
    global $log;

	if ($folder_id == "-1") {
		$folder_id = FOLDER_MYDOCUMENTS_ID;
	}
	$folder = KT_SugarProvider::getFolderDetails($folder_id);
	if (isDocumentsError($folder)) {
		return dmsToSoapFault($folder);
	}

	$folders = KT_SugarProvider::getSubFolders($folder->id);
	$folders_result = array();
	foreach ($folders as $f) {
		$folders_result[] = convert_folder_array($f);
	}
	
	$docs = KT_SugarProvider::getSubDocuments($folder->id);
	$docs_result = array();
	foreach ($docs as $doc) {
		$bean = new ZuckerDocument();
		$bean->fromDocument($doc);
		$docs_result[] = convert_document_array($bean);
	}

	$result = array();
	$result["folder_detail_array"] = $folders_result;
	$result["attached_document_array"] = $docs_result;
	return $result;
}


function attach_document_impl($filename, $description, $parent_type, $parent_id, $contents, $base64 = TRUE) {
    global $log;

	if ($parent_type == 'Folders') {
		$folder = KT_SugarProvider::getFolderDetails($parent_id);
		if (isDocumentsError($folder)) {
			return dmsToSoapFault($folder);
		}
		$docs = KT_SugarProvider::getSubDocuments($folder->id);
	} else {
		$docs = KT_SugarProvider::getDocumentsForParent($parent_type, $parent_id);
	}
	if (isDocumentsError($docs)) {
		return dmsToSoapFault($docs);
	}
	foreach ($docs as $doc) {
		if ($doc->filename == $filename) {
			$docFound = $doc;
			break;
		}
	}
	if ($base64) {
		$contents = base64_decode($contents);
	}
	if (isset($docFound)) {
		$res = KT_SugarProvider::checkinDocument($docFound->id, $contents, $description);
		if (isDocumentsError($res)) {
			return dmsToSoapFault($res);
		} else {
			return $docFound->id;
		}
	} else {
		if ($parent_type == 'Folders') {
			$res = KT_SugarProvider::addDocumentToFolder($contents, $filename, $folder->id, $cat_name, $description);
		} else {
			$res = KT_SugarProvider::addDocument($contents, $filename, $parent_type, $parent_id, $cat_name, $description);
		}
		if (isDocumentsError($res)) {
			return dmsToSoapFault($res);
		} else {
			return $res;
		}
	}
}


function attached_document_search_impl($filename) {
    global $log;

	$log->debug("searching file ".$filename);
	$docs = KT_SugarProvider::searchDocuments(NULL, NULL, $filename);
	$log->debug("ready ".$filename);
	if (isDocumentsError($docs)) {
		return dmsToSoapFault($docs);
	}
	$result = array();
	foreach ($docs as $doc) {
		$bean = new ZuckerDocument();
		$bean->fromDocument($doc);
		$result[] = convert_document_array($bean);
	}
	return $result;
}

function get_attached_documents_impl($sugar_ids) {
    global $log;

	$result = array();
	$sugar_id_list = parse_sugar_ids($sugar_ids);
	foreach ($sugar_id_list as $sugar_id) {
		$parent_type = $sugar_id['parent_type'];
		$parent_id = $sugar_id['parent_id'];
		
		if ($parent_type == 'Folders') {
			$folder = KT_SugarProvider::getFolderDetails($parent_id);
			if (isDocumentsError($folder)) {
				return dmsToSoapFault($folder);
			}
			$docs = KT_SugarProvider::getSubDocuments($folder->id);
		} else {
			$docs = KT_SugarProvider::getDocumentsForParent($parent_type, $parent_id);
		}

		foreach ($docs as $doc) {
			$bean = new ZuckerDocument();
			$bean->fromDocument($doc);
			$result[] = convert_document_array($bean);
		}
	}
	return $result;
}

function get_document_history_impl($id) {
    global $log;

	$result = array();
	
	$hist_array = KT_SugarProvider::getDocumentTransactions($id);
	if (isDocumentsError($hist_array)) {
		return dmsToSoapFault($hist_array);
	}
	foreach ($hist_array as $hist) {
		$result[] = convert_history_array($hist);
	}
	return $result;
}

function load_attached_document_impl($id, $lock, $base64 = TRUE) {
    global $log;

	$doc = KT_SugarProvider::getDocument($id);
	if (isDocumentsError($doc)) {
		return dmsToSoapFault($doc);
	}
	$bean = new ZuckerDocument();
	$bean->fromDocument($doc);
	$result = convert_document_array($bean);

	$lock = strtolower($lock);
	if ($lock == "y" || $lock == "j" || $lock == "yes" || $lock == "ja" || $lock == "true" || $lock == "1") {
		$contents = KT_SugarProvider::checkoutDocument($doc->id);
	} else {
		$contents = KT_SugarProvider::getDocumentContents($doc->id);
	}
	if (isDocumentsError($contents)) {
		return dmsToSoapFault($contents);
	}
	if ($base64) {
		$contents = base64_encode($contents);
	}
	$result["contents"] = $contents;
	return $result;
}
?>
