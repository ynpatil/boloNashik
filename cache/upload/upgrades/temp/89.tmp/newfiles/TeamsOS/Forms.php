<?php

/*********************************************************************************
 * $Id: Forms.php
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 */

function get_validate_record_js () {

}


/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 */
function get_new_record_form () {
	require_once('modules/TeamsOS/TeamFormBase.php');
	$form = new TeamFormBase();
	return $form->getForm('','TeamsOS');
}

?>
