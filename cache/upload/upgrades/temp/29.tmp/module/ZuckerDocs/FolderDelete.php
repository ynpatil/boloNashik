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

require_once('modules/ZuckerDocs/dms/sugarprovider.inc');
require_once('include/formbase.php');

$redirect = FALSE;
if(!empty($_REQUEST['record'])) {
	$folder = KT_SugarProvider::getFolderDetails($_REQUEST['record']);
	if (isDocumentsError($folder)) {
		echo KT_SugarProvider::formatError($folder);
	} else {
		$result = KT_SugarProvider::deleteFolder($folder->id);
		if (isDocumentsError($result)) {
			echo KT_SugarProvider::formatError($result);
		} else {
			$redirect = TRUE;
		}
	}
}
if ($redirect) {
	handleRedirect("", "ZuckerDocs");
}
?>
