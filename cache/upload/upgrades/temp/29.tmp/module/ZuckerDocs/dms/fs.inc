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
require_once('conf.inc');
require_once('document.inc');
//#$# MOD-MAY 12
require_once('db.inc');

class KT_FileProvider {

	function getFullPath($folder) {
		global $dmsRootDir;
		return ($dmsRootDir.($folder->full_path)."/".($folder->name));
	}
	
	function getDocumentPath($document) {
		global $dmsRootDir;
		return ($dmsRootDir.($document->full_path));
	}
	
	function getDocumentFullPath($document) {
		global $dmsRootDir;
		global $dcvID,$sql;

		/** #$# MOD-MAY11 to return document file name by prefixing it with the document id
		return ($dmsRootDir.($document->full_path)."/".($document->filename));
		*/
		
		/** #$# MOD-MAY12 to bring document_content_version id instead of docuemnt->id.
		return ($dmsRootDir.($document->full_path)."/".($document->id)."-".($document->filename));
		*/

		$dcvID = getDCVID($document->id);
		return ($dmsRootDir.($document->full_path)."/".($dcvID)."-".($document->filename));
	}
	/* #$# MOD-MAY28
	function getDocumentFullPathForVersion($document, $majorVersion, $minorVersion) {
		global $dmsRootDir;
		$filename = $document->filename."-".$majorVersion.".".$minorVersion;
		return ($dmsRootDir.($document->full_path)."/".$filename);
	}
	*/
	
	function getDocumentFullPathForVersion($document, $dcvID) {
		global $dmsRootDir;
		/* #$# MOD-MAY28 To append new document_content_version id
			$filename = $document->filename."-".$majorVersion.".".$minorVersion;
		*/
		$filename = $dcvID."-".$document->filename;
		return ($dmsRootDir.($document->full_path)."/".$filename);
	}
	/*
	author : Sriram.V
	date   : May-29-2006
    ADD-MAY29 To Create backup file with their version names also.
	*/	
	function getNewDocumentFullPathForVersion($document, $majorVersion, $minorVersion) {
		global $dmsRootDir;
		
		$dcvID = getDCVIDForVersion($document->id, $majorVersion, $minorVersion);
		$filename = $dcvID."-".$document->filename;
		return ($dmsRootDir.($document->full_path)."/".$filename);
	}



	function getDocumentFullPathForSimpleVersion($document, $version) {
		global $dmsRootDir;
		
		list($majorVersion, $minorVersion) = explode(".", $version);
		
		$dcvID = getDCVIDForVersion($document->id, $majorVersion, $minorVersion);
		$filename = $dcvID."-".$document->filename;
		return ($dmsRootDir.($document->full_path)."/".$filename);
	}
	
	function moveToDeleted($document, $version = "") {
		global $dmsRootDir;

		$sDeletedPrefix = $dmsRootDir."/Deleted";
		if (!file_exists($sDeletedPrefix)) {
            mkdir($sDeletedPrefix, 0755);
        }

		if (empty($version)) {
			$srcFile = KT_FileProvider::getDocumentFullPath($document);
			// $#$ code written by surya to change the name of the file from documentId to MetadataVersionId
			$dirArray = explode("/",$srcFile);
			$countSep = count($dirArray)-1;
			$metadataVersionId = explode("-",$dirArray[$countSep]);
			// commented by surya to change the ($document->id) with $metadataVersionId
			//	$sDeletedFile = $sDeletedPrefix."/".($document->id)."-".($document->filename);
			$sDeletedFile = $sDeletedPrefix."/".($metadataVersionId[0])."-".($document->filename);
		} else {
			$srcFile = KT_FileProvider::getDocumentFullPathForSimpleVersion($document, $version);
			// $#$ code written by surya to change the name of the file from documentId to MetadataVersionId
			$dirArray = explode("/",$srcFile);
			$countSep = count($dirArray)-1;
			$metadataVersionId = explode("-",$dirArray[$countSep]);
			// commented by surya to change the ($document->id) with $metadataVersionId
			// $sDeletedFile = $sDeletedPrefix."/".($document->id)."-".($document->filename)."-".$version;
			$sDeletedFile = $sDeletedPrefix."/".($metadataVersionId[0])."-".($document->filename)."-".$version;
		}
		if (file_exists($srcFile)) {
			if (copy($srcFile, $sDeletedFile)) {
				unlink($srcFile);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}		
	}
}

?>