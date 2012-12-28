<?php
require_once('include/upload_file.php');

function getStorageInfo() {
	return "Sugar Notes";
}
function getStorageCaps() {
	return "attach";
}

function convert_document_array($note) {
	$result = array(
		"id" => $note->id,
		"filename" => $note->filename,
		"last_modified" => $note->date_modified,
		"status" => "",
		"description" => $note->description);

	if ($note->contact_name != '') {
		$result['parent_type'] = 'Contacts';
		$result['parent_name'] = $note->contact_name;
		$result['parent_id'] = $note->contact_id;
	} else {
		$result['parent_type'] = $note->parent_type;
		$result['parent_name'] = $note->parent_name;
		$result['parent_id'] = $note->parent_id;
	}
	return $result;
}

function get_folder_contents_impl($folder_id) {
	return notImplementedSoapFault("folders");
}



function attach_document_impl($filename, $description, $parent_type, $parent_id, $contents, $base64 = TRUE) {
    global $log,$upload_dir;

	$note = new Note();
	$note->name = $filename;
	$note->description = $description;
	$note->filename = $filename;
	$note->parent_type = $parent_type;
	$note->parent_id = $parent_id;
	if ($parent_type == 'Contacts') {
		$note->contact_id = $parent_id;
	}
	$id = $note->save();
	if ($base64) {
		$contents = base64_decode($contents);
	}

	$uf = new UploadFile("upload");
	$uf->set_for_soap($filename, $contents);
	$uf->stored_file_name = $uf->create_stored_filename();
	$uf->final_move($id);

	return $id;
}


function attached_document_search_impl($filename) {
 	$filename = str_replace('*', '%', $filename);

	$obj_note = new Note();
	$notesList = $obj_note->get_full_list("filename", "filename != '' and filename like '%".PearDatabase::quote($filename)."%'");

	$result = array();
	foreach ($notesList as $note) {
		$result[] = convert_document_array($note);	
	}

	return $result;
}

function get_attached_documents_impl($sugar_ids) {
	$result = array();

	$obj_note = new Note();

	$sugar_id_list = parse_sugar_ids($sugar_ids);
	foreach ($sugar_id_list as $sugar_id) {
		$where = '';
		if ($sugar_id['parent_type'] == 'Contacts') {
			$where = "filename != '' and contact_id = '".PearDatabase::quote($sugar_id['parent_id'])."'";			
		} else {
			$where = "filename != '' and parent_type = '".PearDatabase::quote($sugar_id['parent_type'])."' and parent_id = '".PearDatabase::quote($sugar_id['parent_id'])."'";			
		}
		$notesList = $obj_note->get_full_list("filename", $where);
		foreach ($notesList as $note) {
			$result[] = convert_document_array($note);	
		}
	}
	return $result;
}

function get_document_history_impl($id) {
	return notImplementedSoapFault("history");
}

function load_attached_document_impl($id, $lock, $base64 = TRUE) {
   	global $upload_dir;

	$note = new Note();
	$note->retrieve($id);
	        
	$result = convert_document_array($note);

	$uf = new UploadFile("download");
	$uf->set_for_soap($note->filename, NULL);
	$infile = $uf->get_upload_path($id);
	if (is_file($infile) && is_readable($infile)) {
		$contents = file_get_contents($infile);
		if ($base64) {
			$contents = base64_encode($contents);
		}
		$result['contents'] = $contents;	
	}
	return $result;
}

?>
