<?php
function get_validate_record_js () {

global $mod_strings;
global $app_strings;

$lbl_doc_name = $mod_strings['LBL_LIST_NAME'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}

function verify_doc_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.name.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_doc_name";
	}

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}
function verify_link_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.doc_id.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_doc_name";
	}

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}

</script>

EOQ;

return $the_script;
}

function get_new_record_form () {
	$result = <<<EOQ
	
	<table width='100%' cellpadding='0' cellspacing='0' border='0'>
<tr><td valign='top'><a href="http://kt-dms.sourceforge.net/"><img border="0" src="modules/ZuckerDocs/images/ktlogo_small.jpg"/></a></td></tr>
</table>

EOQ;
	
	return $result;
}


?>
