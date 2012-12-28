<?php
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

$dictionary['ZuckerDocsDummyBean'] = array(
);
 
$dictionary['ZuckerDocument'] = array(
	'fields' => array (
		'id' => array (
				'name' => 'id',
				'type' => 'varchar',
				),
		'name' => array (
			'name' => 'name',
			'type' => 'varchar',
			),
		'filename' => array (
			'name' => 'filename',
			'type' => 'varchar',
			),
		'description' => array (
			'name' => 'description',
			'type' => 'varchar',
			),
		'modified' => array (
			'name' => 'modified',
			'type' => 'varchar',
			),
		'is_checked_out' => array (
			'name' => 'is_checked_out',
			'type' => 'varchar',
			),
		'checkedout_username' => array (
			'name' => 'checkedout_username',
			'type' => 'varchar',
			),
		'version' => array (
			'name' => 'version',
			'type' => 'varchar',
			),
		'mimetype' => array (
			'name' => 'mimetype',
			'type' => 'varchar',
			),
		'status' => array (
			'name' => 'status',
			'type' => 'varchar',
			),
		'folder_id' => array (
			'name' => 'folder_id',
			'type' => 'varchar',
			),
		'parent_type' => array (
			'name' => 'parent_type',
			'type' => 'varchar',
			),
		'parent_name' => array (
			'name' => 'parent_name',
			'type' => 'varchar',
			),
		'parent_id' => array (
			'name' => 'parent_id',
			'type' => 'varchar',
			),
		'parent_link' => array (
			'name' => 'parent_link',
			'type' => 'varchar',
			),
		'cat_name' => array (
			'name' => 'cat_name',
			'type' => 'varchar',
			),
		'cat_description' => array (
			'name' => 'cat_description',
			'type' => 'varchar',
			),
		'icon_path' => array (
			'name' => 'icon_path',
			'type' => 'varchar',
			),
		'score' => array (
			'name' => 'score',
			'type' => 'varchar',
			),
		),
);
$dictionary['FolderItem'] = array(
	'fields' => array (
		'id' => array (
				'name' => 'id',
				'type' => 'varchar',
				),
		'name' => array (
			'name' => 'name',
			'type' => 'varchar',
			),
		'description' => array (
			'name' => 'description',
			'type' => 'varchar',
			),
		'creator' => array (
			'name' => 'creator',
			'type' => 'varchar',
			),
		),
);
$dictionary['ZuckerDocumentTransaction'] = array(
	'fields' => array (
		'version' => array (
				'name' => 'version',
				'type' => 'varchar',
				),
		'user' => array (
			'name' => 'user',
			'type' => 'varchar',
			),
		'datetime' => array (
			'name' => 'datetime',
			'type' => 'varchar',
			),
		'comment' => array (
			'name' => 'comment',
			'type' => 'varchar',
			),
		'type' => array (
			'name' => 'type',
			'type' => 'varchar',
			),
		),
);

?>
