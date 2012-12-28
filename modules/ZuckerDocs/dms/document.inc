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

define('DOCERROR_DMSAUTH', "101");
define('DOCERROR_USERNOTFOUND', "102");
define('DOCERROR_FOLDERNOTFOUND', "110");
define('DOCERROR_DOCNOTFOUND', "111");
define('DOCERROR_DOCCHECKEDOUT', "120");
define('DOCERROR_DOCCHECKEDIN', "121");
define('DOCERROR_INVALIDFILENAME', "122");
define('DOCERROR_FILENOTUPLOADED', "123");
define('DOCERROR_FILEEXISTS', "124");
define('DOCERROR_NOTDELETED', "125");
define('DOCERROR_FOLDERNOTEMPTY', "126");
define('DOCERROR_PARENTNOTSPECIFIED', "130");


define('DOCSTATUS_ALL', '0');
define('DOCSTATUS_LIVE', '1');
define('DOCSTATUS_PUBLISHED', '2');
define('DOCSTATUS_DELETED', '3');
define('DOCSTATUS_ARCHIVED', '4');

define('CHECKIN_MAJOR', "1");
define('CHECKIN_MINOR', "2");
define('HIST_CREATE', "1");
define('HIST_UPDATE', "2");
define('HIST_DELETE', "3");
define('HIST_CHECKIN', "7");
define('HIST_CHECKOUT', "8");

define('DOCLINK_DEFAULT', '0');

function isDocumentsError($obj) {
	return (strtolower(get_class($obj)) == 'kt_documentserror');
}

class KT_DocumentsError {
	var $errorCode;
	var $arg1;
	var $arg2;
	var $arg3;
	var $arg4;
	
	function KT_DocumentsError($errorCode, $arg1 = '', $arg2 = '', $arg3 = '', $arg4 = '') {
		$this->errorCode = $errorCode;
		$this->arg1 = $arg1;
		$this->arg2 = $arg2;
		$this->arg3 = $arg3;
		$this->arg4 = $arg4;
	}	
}


class KT_Folder {
	var $id;
	var $name;
	var $userhaswriteaccess;
	
	var $description;
	var $creator;
	var $full_path;
	var $parent_id;
	var $unit_id;
	
	function KT_Folder($id) {
		$this->id = $id;
	}
}

class KT_DocumentType {
	var $id;
	var $name;
	
	function KT_DocumentType($id = '', $name = '') {
		$this->id = $id;
		$this->name = $name;
	}	
}

class KT_DocumentTransaction {
	var $version;
	var $user;
	var $datetime;
	var $comment;
	var $type;

	var $file_exists;
	
	function KT_DocumentTransaction($version = '', $user = '', $datetime = '', $comment = 
'', $type = '') {
		$this->version = $version;
		$this->user = $user;
		$this->datetime = $datetime;
		$this->comment = $comment;
		$this->type = $type;
	}
}

class KT_Document {
	var $id;
	var $name;
	var $description;
	var $filename;
	var $created;
	var $modified;
	var $author_userid;
	var $author;
	var $is_checked_out;
	var $checkedout_userid;
	var $checkedout_username;
	var $statusId;
	var $major_version;
	var $minor_version;
	var $folder_id;
	var $full_path;

	var $mimetype;
	var $icon_path;
	
	var $parentType;
	var $parentId;
	var $category;
	var $version;

	var $score;
	
	function KT_Document($id) {
		$this->id = $id;
	}
}

class KT_MetaDataField {
	var $id;
	var $name;
	var $datatype;
	var $mandatory;
	
	function KT_MetaDataField($id = '', $name = '', $datatype = '', $mandatory = '') {
		$this->id = $id;
		$this->name = $name;
		$this->name = $datatype;
		$this->name = $mandatory;
	}
}

	
?>