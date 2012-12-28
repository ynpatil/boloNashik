<?php

$note = new Note();

$focus_notes_list = $note->get_full_list("notes.date_entered", $where);

if(count($focus_notes_list)>0)
foreach ($focus_notes_list as $note) {
	$open_activity_list[] = Array('name' => $note->name,
									 'id' => $note->id,
									 'type' => "Note",
									 'direction' => '',
									 'module' => "Notes",
									 'status' => '',
									 'parent_id' => $note->parent_id,
									 'parent_type' => $note->parent_type,
									 'parent_name' => $note->parent_name,
									 'contact_id' => $note->contact_id,
									 'contact_name' => $note->contact_name,
									 'date_modified' => $note->date_modified
									 );
	if (!empty($note->filename))
	{
		$count = count($open_activity_list);
		$count--;
		$open_activity_list[$count]['filename'] = $note->filename;
		$open_activity_list[$count]['fileurl'] = UploadFile::get_url($note->filename,$note->id);
	}
}

?>