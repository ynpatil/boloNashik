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

require_once('document.inc');
require_once('db.inc');
require_once('fs.inc');
require_once('auth.inc');
require_once('folderprovider.inc');
// #$# MOD-MAY28
require_once('sugarprovider.inc');

class KT_DocumentProvider {

	function getDocumentDetails($username, $password, $doc) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("KT_DocumentProvider::getDocumentDetails(".$username.", xxx, ".$doc->id.")");

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);

		$sql = getDocumentDetailsSql($doc->id);
		$rs = dmsQuery($sql);
		if ($row = $dmsLink->fetchByAssoc($rs)) {
			if (KT_AuthProvider::__userHasFolderReadPermission($userId, $row["folder_id"])) {	
				$doc->name = $row["name"];

				$dmsLog->debug(" #$# To Display row[name]---> ".$row["name"]."");

				$doc->description = $row["description"];
				
				$doc->filename = $row["filename"];
				
				$dmsLog->debug(" #$# To Display row[filename]---> ".$row["filename"]."");

				$doc->created = $row["created"];
				$doc->modified = $row["modified"];
				$doc->author = $row["author"];
				$doc->author_userid = $row["creator_id"];
				$doc->is_checked_out = $row["is_checked_out"];
				$doc->checkedout_userid = $row["checked_out_user_id"];
				$doc->checkedout_username = $row["checkedout_username"];
				$doc->author = $row["author"];
				$doc->statusId = $row["status_id"];
				$doc->folder_id = $row["folder_id"];
				$doc->full_path = $row["full_path"];
				
				$dmsLog->debug(" #$# To Display row[full_path]---> ".$row["full_path"]."");

				$doc->major_version = $row["major_version"];
				$doc->minor_version = $row["minor_version"];
				$doc->version = $row["major_version"].".".$row["minor_version"];

				$doc->mimetype = $row["mimetype"];
				$doc->icon_path = $row["icon_path"];
				return $doc;
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
			}
		} else {
			return new KT_DocumentsError(DOCERROR_DOCNOTFOUND, $doc->id);		
		}
	}


	function getDocuments($username, $password, $folder, $statusId = DOCSTATUS_LIVE) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("KT_DocumentProvider::getDocuments(".$username.", xxx, ".$folder->id.", ".$statusId.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		if (KT_AuthProvider::__userHasFolderReadPermission($userId, $folder->id)) {
			
			$sql = getDocumentsInFolderSql($folder->id, $statusId, TRUE);
			$rs = dmsQuery($sql);
			return KT_DocumentProvider::__getDocumentDetailsList($username, $password, $rs);
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
		}
	}
	
	function getDocumentsByText($username, $password, $text, $statusId = DOCSTATUS_LIVE) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("KT_DocumentProvider::getDocumentsByText(".$username.", xxx, ".$text.", ".$statusId.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		
		$sql = getDocumentByTextSql($text, $statusId);
		$rs = dmsQuery($sql);
		
		$docList = array();
		while ($row = $dmsLink->fetchByAssoc($rs)) {
			$doc = new KT_Document($row["id"]);
			$doc->score = $row["score"];
			$docList[] = $doc;
		}
		$result = array();
		foreach ($docList as $doc) {
			$doc = KT_DocumentProvider::getDocumentDetails($username, $password, $doc);
			if (!isDocumentsError($doc)) {
				$result[] = $doc;
			}
		}
		return $result;
	}	
	
	function getDocumentByFilename($username, $password, $folder, $filename) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::getDocumentByFilename(".$username.", xxx, ".$folder.", ".$filename.")");

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		if (KT_AuthProvider::__userHasFolderReadPermission($userId, $folder->id)) {
			$sql = getDocumentInFolderSql($folder->id, $filename);
			$rs = dmsQuery($sql);
			$row = $dmsLink->fetchByAssoc($rs);
			if ($row) {
				return KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($row["id"]));
			} else {
				return new KT_DocumentsError(DOCERROR_DOCNOTFOUND, $filename);		
			}
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
		}
	}

	//XXX todo check access rights ...
	function getRecentlyChangedDocuments($username, $password, $start, $limit) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::getRecentlyChangedDocuments(".$username.", xxx, ".$start.", ".$limit.")");
		
		KT_AuthProvider::checkUser($username, $password);
		
		$userId = KT_AuthProvider::__getUserId($username);
		
		$sql = getRecentlyChangedSql($start, $limit);
		$rs = dmsQuery($sql);
		return KT_DocumentProvider::__getDocumentDetailsList($username, $password, $rs);
	}
	
	function getDocumentTransactions($username, $password, $document) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::getDocumentTransactions(".$username.", xxx, ".$document.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, $document);
		if (!isDocumentsError($document)) {
			$result = array();
			$sql = getDocumentTransactionsSql($document->id);
			$rs = dmsQuery($sql);
			while ($row = $dmsLink->fetchByAssoc($rs)) {
				$dt = new KT_DocumentTransaction($row["version"], $row["username"], $row["datetime"], $row["comment"], $row["type"]);
				$fullPath = KT_FileProvider::getDocumentFullPathForSimpleVersion($document, $row["version"]);
				$dt->file_exists = (file_exists($fullPath) && is_readable($fullPath));
				$result[] = $dt;
			}
			return $result;
		} else {
			return $document;		
		}
	}
	
	function getDocumentContents($username, $password, $document, $version = '') {
		global $dmsLink, $dmsLog;
//		$dmsLog->debug("#$# KT_DocumentProvider::getDocumentContents(".$username.", xxx, ".$document.", ".$version.")");
		$dmsLog->debug("#$# KT_DocumentProvider::getDocumentContents(".$username.", xxx, ".$version.")");

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, $document);
		if (!isDocumentsError($document)) {
			if (empty($version) || $version == $document->version) {
				$fullPath = KT_FileProvider::getDocumentFullPath($document);
				$dmsLog->debug("#$# Calling KT_FileProvider::getDocumentFullPath");
			} else {
			
				$fullPath = KT_FileProvider::getDocumentFullPathForSimpleVersion($document, $version);
				$dmsLog->debug("#$# Calling KT_FileProvider::getDocumentFullPathForSimpleVersion");
			}
			$dmsLog->debug("KT_DocumentProvider::getDocumentContents ".$document->name." reading from ".$fullPath);
			if (file_exists($fullPath) && is_readable($fullPath)) {
				return file_get_contents($fullPath);
			} else {
				return new KT_DocumentsError(DOCERROR_DOCNOTFOUND, $fullPath);
			}
		} else {
			return $document;		
		}
	}

	function checkOut($username, $password, $document, $comment) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::checkOut(".$username.", xxx, ".$document.", ".$comment.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, $document);
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $document->folder_id)) {
				if (!$document->is_checked_out) {
					$contents = KT_DocumentProvider::getDocumentContents($username, $password, $document);
					if (!isDocumentsError($contents)) {
						$sql = getUpdateDocumentCheckedOutStatusSql($document->id, 1, $userId);
						dmsQuery($sql);
	
						$transId = getNewId("document_transactions");
						$version = ($document->major_version).".".($document->minor_version);
						/**
						// #$# MOD-MAY24 Filename was not able to resolve properly. So it is modified by affixing full_path and ip_address
						$sql = getInsertDocumentTransactionSql(
							$transId, $document->id, $version, $userId, $document->filename, $comment, HIST_CHECKOUT);
						*/
						$newPath = $document->full_path."/".$document->filename;
						$newDate=date('Y-m-d H:i:s');
				
						// get the ip address of the machine
						$ipAddress = $_SERVER['REMOTE_ADDR'];
						/**						
						//Parameters checklist(id, document_id, version, user_id, datetime, ip, filename, comment) 				
						// #$# MOD-MAY24 Perviously only 7 parametrs were passed
						$sql = getInsertDocumentTransactionSql(
						$transId, $document->id, $version, $userId, $document->filename, $comment, HIST_CHECKOUT);
						*/
						
						/** MOD-MAY26 - Rupesh K - providing proper transaction_namespace
						$sql = getInsertDocumentTransactionSql(
							$transId, $document->id, $version, $userId, $newPath, $comment, $transId, HIST_CHECKOUT);
						*/
						
						// $transaction_namespace = getTransactionNamespace(HIST_CHECKOUT);

						$sql = getInsertDocumentTransactionSql(
							$transId, $document->id, $version, $userId, $newPath, $comment, HIST_CHECKOUT);

						dmsQuery($sql);
					}
					return $contents;
				} else {
					return new KT_DocumentsError(DOCERROR_DOCCHECKEDOUT, $document->checkedout_username);			
				}
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
			}
		} else {
			return new KT_DocumentsError(DOCERROR_DOCNOTFOUND, $document->id);			
		}
	}
	// #$# MOD-MAY27 New Function for Checkin.
	function checkIn($username, $password, $contents, $document, $comment, $checkinVersionType) {
		global $dmsLink, $dmsLog;
		
		$dmsLog->debug("#$# KT_DocumentProvider::checkIn(".$username.", xxx, ".$contents.", ".$document.", ".$comment.", 
".$checkinVersionType.")");

		KT_AuthProvider::checkUser($username, $password);
		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, $document);
				
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $document->folder_id)) {
				if ($document->is_checked_out && $document->checkedout_userid != $userId) {
					return new KT_DocumentsError(DOCERROR_DOCCHECKEDOUT, $document->checkedout_username);
				} else {
					$majorVersion = $document->major_version;
					$minorVersion = $document->minor_version;

					$currentFile = KT_FileProvider::getDocumentFullPath($document);
					/* MOD-MAY28 To take proper backup at the time of checkin
					$backupFile = KT_FileProvider::getDocumentFullPathForVersion($document, $majorVersion, $minorVersion);
					*/

					// #$# MOD-MAY28
					$newDCVID = getNewId("document_content_version");
					$newDMDVID = getNewId("document_metadata_version");
					$newDFLID = getNewId("document_fields_link");
					
					// #$# MOD-MAY28 To create backup file with version names also.
					$newFile = KT_FileProvider::getNewDocumentFullPathForVersion($document, $majorVersion, $minorVersion);
					$backupFile = KT_FileProvider::getDocumentFullPathForVersion($document, $newDCVID);
					$dir = KT_FileProvider::getDocumentPath($document);

					if (file_exists($currentFile) && is_writeable($dir)) {
						// #$# MOD-MAY30 Changed backupfile into newFile File
						copy($currentFile, $newFile);
						$handle = fopen($currentFile, "w");
						fwrite($handle, $contents);
						fclose($handle);
					} else {
						return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
					}

					// To Copy the $newFile with version Type also.
					if (file_exists($currentFile) && is_writeable($dir)) {
						// #$# MOD-MAY30 Changed newFile into backup File
						copy($currentFile, $backupFile);
						$handle = fopen($currentFile, "w");
						fwrite($handle, $contents);
						fclose($handle);
					} else {
						return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
					}
					
										
					if ($checkinVersionType == CHECKIN_MINOR) {
						$minorVersion = $minorVersion + 1;
					} else {
						$majorVersion = $majorVersion +1;
						$minorVersion = 0;
					}
					
					/* 
						#$# MOD-MAY27 To make the updates in the following tables as KT do these changes.
						document_content_version
						document_fields_link		
						document_metadata_version
						zseq_document_content_version - Done
						zseq_document_fields_link - Done	
						zseq_document_metadata_version - Done
					*/
									
					// To insert into document_content_version
					$userId = KT_AuthProvider::__getUserId($username);
					$newPath = $document->full_path."/".$newDCVID."-".$document->filename;
					$mimeTypeId = getMimeId($document->id);

					//To get FileSize
					$size = filesize($currentFile);


					// To get parentFolderRow
					$sql = getFolderDetailsSql($folder->id);
					$rs = dmsQuery($sql);
					$folderRow = $dmsLink->fetchByAssoc($rs);

					// #$# MOD-MAY30 Passing majorVersion, minorVersion instead of document->major_version,document->minor_version.
					$insertDCVSQL = getNewInsertDCV($newDCVID,$document->filename,$document->id,
					$size,$mimeTypeId,$majorVersion,$minorVersion,$newPath);
					dmsQuery($insertDCVSQL);
										
					// To insert into document_metadata_version
					$insertDMDVSQL = getNewInsertDMDVSQL($newDMDVID,$document->id,$document->name);
					dmsQuery($insertDMDVSQL);
	
					// To fill in metadata fields in document to get value field in DFL table.
					$document = KT_SugarProvider::__fillinMetaData($document);
					
					// To Update documents table.
					// #$# MOD-MAY28 To update document table with new metadata version id.
					// $sql = getUpdateStatementCheckedInStatusSql($document->id, $majorVersion, $minorVersion);
					$sql = getUpdateStatementCheckedInStatusSql($document->id, $majorVersion, $minorVersion, $newDMDVID);
					dmsQuery($sql);
	
					// To insert into document_fields_link call KT_SugarProvider::__save metadata.
					KT_SugarProvider::__saveMetaData($document);
					
					$transId = getNewId("document_transactions");
					$version = $majorVersion.".".$minorVersion;
					
					/** MOD-MAY26 - Rupesh K - Providing proper transaction_namespace
					$sql = getInsertDocumentTransactionSql(
						$transId, $document->id, $version, $userId, $document->filename, $comment, HIST_CHECKIN);
					*/

					// $transaction_namespace = getTransactionNamespace(HIST_CHECKIN);
					
					$sql = getInsertDocumentTransactionSql(
						$transId, $document->id, $version, $userId, $newPath, $comment, HIST_CHECKIN);
					dmsQuery($sql);
			}
		} else {
			return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
		}
	} else {
		return new KT_DocumentsError(DOCERROR_DOCNOTFOUND, $document->id);			
	}
}

	function updateDocument($username, $password, $newDocument) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::updateDocument(".$username.", xxx, ".$newDocument.")");

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($newDocument->id));
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $document->folder_id)) {
				$sql = getUpdateDocumentDataSql($document->id, $newDocument->name, $newDocument->description);
				dmsQuery($sql);
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
			}
		} else {
			return $document;		
		}
	}
	
	function addDocument($username, $password, $contents, $folder, $documentTypeName, $filename, $genericMetaData, $typeSpecificMetaData, $description = '') {
		
		global $dmsLog, $dmsLink, $dmsResolveFilenameConflicts;

		$dmsLog->debug("#$# KT_DocumentProvider::addDocument(".$username.", xxx, ".$contents.", ".$folder.", xxx, ".$documentTypeName.", ".$filename.", ".$genericMetaData.", ".$typeSpecificMetaData.", ".$description.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$folder = KT_FolderProvider::getFolderDetails($username, $password, $folder);
		if (!isDocumentsError($folder)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $folder->id)) {

				$docId = getNewId("documents");
			
				$sql = getDocumentInFolderSql($folder->id, $filename);
				$rs = dmsQuery($sql);
				if ($row = $dmsLink->fetchByAssoc($rs)) {
					if ($dmsResolveFilenameConflicts) {
						$filename = $docId."_".$filename;
					} else {
						return new KT_DocumentsError(DOCERROR_FILEEXISTS, $filename); 
					}
				}
	
				$i = strrpos($filename, '.');
				$ext = substr($filename, $i + 1);
				$sql = getMimeTypeSql($ext);
				$rs = dmsQuery($sql);			
				if ($row = $dmsLink->fetchByAssoc($rs)) {
					$mimeTypeId = $row["id"];
				} else {
					$sql = getDefaultMimeTypeSql();
					$rs = dmsQuery($sql);		
					$row = $dmsLink->fetchByAssoc($rs);
					$mimeTypeId = $row["id"];
				}
				// #$# added by surya for new document content vrsion id (15-5-2006)
				$docContentVersionId = getNewId("document_content_version");
				
				$sql = getFolderDetailsSql($folder->id);
				$rs = dmsQuery($sql);
				$folderRow = $dmsLink->fetchByAssoc($rs);
	
				$dir = KT_FileProvider::getFullPath($folder);
				$dmsLog->debug("KT_DocumentProvider::addDocument folder = ".$folder->full_path);
				$onlyFileName = removeExtensionFromFilename($filename);
				$newPathValue = $folder->full_path."/".$onlyFileName;
				$dmsLog->debug("KT_DocumentProvider::addDocument newPathValue = ".$newPathValue);
				$dmsLog->debug("KT_DocumentProvider::addDocument dir/filename = ".$dir."/".$onlyFileName);
				
				// commented by surya (15-5-2006) and added content version id before the filename
				// $file = $dir."/".$filename;
				$file = $dir."/".$docContentVersionId."-".$filename;
				if (is_writable($dir)) {
					$handle = fopen($file, "w");
					fwrite($handle, $contents);
					fclose($handle);
				} else {
					return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
				}
	
				$documentType = KT_FolderProvider::__getDocumentType($documentTypeName);

				// #$# added by surya to insert the record in document_metadata_version table
				$docMetadataVersionId = getNewId("document_metadata_version");
				$sql = getInsertDocumentSql($docId, $filename, $filename, $documentType->id, filesize($file), $userId, $description, $mimeTypeId, $folderRow, 0, 1,$docMetadataVersionId,1,3,1);
				dmsQuery($sql);
						
				// #$# added by surya to insert the record in document_content_version table
				$sql = getInsertDocumentContentVersionSql($docContentVersionId, $filename, $filename, $docId, filesize($file), $userId, $description, $mimeTypeId, $folderRow, 0, 1);
				dmsQuery($sql);						

				$sql = getInsertDocumentMetadataVersionSql($docMetadataVersionId, $filename, $filename, $docId, filesize($file), $userId, $description, $mimeTypeId, $folderRow, 0, 1,$docContentVersionId,$documentType->id);
				dmsQuery($sql);
	
				foreach (array_keys($genericMetaData) as $key) {
					$value = $genericMetaData[$key];	
					// $#$ commented by surya to pass the id for metadata_version table instead of document table (15-5-2006)
					// KT_DocumentProvider::__setMetaDataField($docId, $key, $value);
					KT_DocumentProvider::__setMetaDataField($docMetadataVersionId, $key, $value);
				}
				foreach (array_keys($typeSpecificMetaData) as $key) {
					$value = $typeSpecificMetaData[$key];	
					KT_DocumentProvider::__setMetaDataField($docMetadataVersionId, $key, $value);
				}
				$transId = getNewId("document_transactions");
				
				/** MOD-MAY26 - Rupesh K - Providing proper transaction_namespace
				$sql = getInsertDocumentTransactionSql($transId, $docId, '0.1', $userId, $filename, '', HIST_CREATE,$folderRow);
				*/

				// $transaction_namespace = getTransactionNamespace(HIST_CREATE);
				/** MOD-MAY26 - Rupesh K - for full path of file
				$sql = getInsertDocumentTransactionSql($transId, $docId, '0.1', $userId, $filename, '', HIST_CREATE, $folderRow);
				*/ 
				// MOD-MAY30 - Sriram.V document object was having null previously.
				//$newPath = $document->full_path."/".$document->filename;
				//$dmsLog->debug("KT_DocumentProvider::addDocument newPath = ".$newPath);
				$sql = getInsertDocumentTransactionSql($transId, $docId, '0.1', $userId, $newPathValue, '', HIST_CREATE, $folderRow);					
				
				dmsQuery($sql);
				
				// $#$ write function to insert the record in document_text table by Surya on 17-5-2006
			/*	$contents = KT_SugarProvider::getDocumentContents($docId);
				$sql = getInsertDocumentTextSql($docId, $contents);
				dmsQuery($sql); */
				
				// $#$ write function to insert/modify the record in document transaction text table by Surya on 17-5-2006
				$sql = updateTransactionText($docId, "insert");
				dmsQuery($sql);
				
				// $#$ write function to insert/modify the record in document searchable text table by Surya on 17-5-2006
				$sql = updateSearchableText($docId, "insert", $filename, $description);
				dmsQuery($sql);
				

			//	$wsId = getNewId("web_documents");
			//	$sql = getInsertWebsiteSql($wsId, $docId, $folder->unit_id);
			//	dmsQuery($sql);
				
				$dmsLog->debug("KT_DocumentProvider::addDocument docId = ".$docId);
				return new KT_Document($docId);		
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $folder->id);	
			}
		} else {
			return $folder;
		}		
	}
	
	function deleteDocument($username, $password, $document) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::deleteDocument(".$username.", xxx, ".$document.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($document->id));
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $document->folder_id)) {
				
				$sql = getDocumentTransactionsSql($document->id);
				$rs = dmsQuery($sql);
				while ($row = $dmsLink->fetchByAssoc($rs)) {
					if ($row["transaction_id"] == HIST_CHECKIN || $row["transaction_id"] == HIST_CREATE) {
						if (!KT_FileProvider::moveToDeleted($document, $row["version"])) {
							$dmsLog->warn(($document->filename).", version ".($row["version"]).", was not deleted.");
						}
					}
				}
				if (!KT_FileProvider::moveToDeleted($document)) {
					$dmsLog->warn(($document->filename)." was not deleted.");
				}

				$sql = getUpdateDocumentStatusSql($document->id, DOCSTATUS_DELETED);
				dmsQuery($sql);
				/** #$# MOD-MAY26
				 ToDo: Need to insert into document_transactions, zseq_document_transactions and document_metadata_version needs to be updated.
				*/
				// call to db.inc::getInsertDocumentTransactionSql($id, $docId, $version, $userId, $filename, $comment, $transactionId,$parentFolderRow)
				//#$# MOD-MAY26 Added to insert into document_transactions table.
				
				//#$# Added on May-26 To get user_id
				// #$# Start
				global $current_user;
				$userName = $current_user->user_name;
				$userId = KT_AuthProvider::__getUserId($userName);
				// #$$ End

				// call to db.inc::getNewId(document_transactions);  				
				$transId = getNewId("document_transactions");
				$version = getVersion($docId);
				$newVersion = $version + 0.1 ;
				
				$sql = getFolderDetailsSql($folder->id);
				$rs = dmsQuery($sql);
				$folderRow = $dmsLink->fetchByAssoc($rs);
				//echo "folderprovider.inc :: folderRow".$folderRow."<BR>";
								
				$name = $document->full_path."/".$document->filename;
				//echo "folderprovider.inc :: name".$name."<BR>";
				
				$insertTransation = getInsertDocumentTransactionSql($transId, $document->id, $newVersion, $userId, $name, 'delete document', HIST_DELETE, $folderRow);
				dmsQuery($insertTransation);
				//$insertTransation = getInsertDocumentTransactionSql($newId, $document->id, $version, $userId, $filename, $comment, $transactionId,$parentFolderRow);
				/* MOD-MAY26 Instead of inserting into document_metadata_version we need to Update status.
				// call to db.inc::getInsertDMDVSQL(document->id,$name);
				$insertMDV = getInsertDMDVSQL($document->id,$name); 
				dmsQuery($insertMDV);
				*/
				//ToDo: Update document_metadata_version.
				upDateDMDV($document->id);

			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
			}
		} else {
			return $document;		
		}
	}
	
	function getLinkedDocuments($username, $password, $document) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::getLinkedDocuments(".$username.", xxx, ".$document.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($document->id));
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderReadPermission($userId, $document->folder_id)) {
				$sql = getLinkedDocumentsSql($document->id, TRUE);
				$rs = dmsQuery($sql);
				return KT_DocumentProvider::__getDocumentDetailsList($username, $password, $rs);
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
			}
		} else {
			return $document;		
		}
	}
	
	function getLinkingDocuments($username, $password, $document) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::getLinkingDocuments(".$username.", xxx, ".$document.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($document->id));
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderReadPermission($userId, $document->folder_id)) {
				$sql = getLinkingDocumentsSql($document->id, TRUE);
				$rs = dmsQuery($sql);
				return KT_DocumentProvider::__getDocumentDetailsList($username, $password, $rs);
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);
			}
		} else {
			return $document;		
		}
	}
	
	function addDocumentLink($username, $password, $document, $linkedDocument) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::addDocumentLink(".$username.", xxx, ".$document.", ".$linkedDocument.")");

		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($document->id));
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $document->folder_id)) {
				
				$sql = getDocumentLinkSql($document->id, $linkedDocument->id);
				$rs = dmsQuery($sql);		
				if ($row = $dmsLink->fetchByAssoc($rs)) {
				} else {
					$linkId = getNewId("document_link");
					$sql = getInsertDocumentLinkSql($linkId, $document->id, $linkedDocument->id, DOCLINK_DEFAULT);
					dmsQuery($sql);
				}
			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
			}
		} else {
			return $document;		
		}
	}
	
	function deleteDocumentLink($username, $password, $document, $linkedDocument) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::deleteDocumentLink(".$username.", xxx, ".$document.", ".$linkedDocument.")");
		
		KT_AuthProvider::checkUser($username, $password);

		$userId = KT_AuthProvider::__getUserId($username);
		$document = KT_DocumentProvider::getDocumentDetails($username, $password, new KT_Document($document->id));
		if (!isDocumentsError($document)) {
			if (KT_AuthProvider::__userHasFolderWritePermission($userId, $document->folder_id)) {
				
				$sql = getDeleteDocumentLinkSql($document->id, $linkedDocument->id);
				dmsQuery($sql);

			} else {
				return new KT_DocumentsError(DOCERROR_DMSAUTH, $username);	
			}
		} else {
			return $document;		
		}
	}
	
	
	function __setMetaDataField($docMetadataVersionId, $fieldName, $value) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::__setMetaDataField(".$docMetadataVersionId.", ".$fieldName.", ".$value.")");

		$sql = getDocumentFieldLinkSql($docId, $fieldName);
		$rs = dmsQuery($sql);
		if ($row = $dmsLink->fetchByAssoc($rs)) {
			$sql = getUpdateDocumentFieldValueSql($row["id"], $value);
			dmsQuery($sql);	
		} else {
			$sql = getDocumentFieldByNameSql($fieldName);
			$rs = dmsQuery($sql);
			if ($row = $dmsLink->fetchByAssoc($rs)) {
				$id = getNewId("document_fields_link");
				$sql = getInsertDocumentFieldLinkSql($id, $docMetadataVersionId, $row["id"], $value);
				dmsQuery($sql);
			}
		}
	}
	function __getMetaDataField($docId, $fieldName) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::__getMetaDataField(".$docId.", ".$fieldName.")");

		$sql = getDocumentFieldLinkSql($docId, $fieldName);
		$rs = dmsQuery($sql);
		$row = $dmsLink->fetchByAssoc($rs);
		if ($row) {
			return $row["value"];
		}
	}
	
	function __getDocumentDetailsList($username, $password, $rs) {
		global $dmsLink, $dmsLog;

		$dmsLog->debug("#$# KT_DocumentProvider::__getDocumentDetailsList(".$username.", xxx,...)");

		$docList = array();
		while ($row = $dmsLink->fetchByAssoc($rs)) {
			$docList[] = new KT_Document($row["id"]);
		}
		$result = array();
		foreach ($docList as $doc) {
			$doc = KT_DocumentProvider::getDocumentDetails($username, $password, $doc);
			if (!isDocumentsError($doc)) {
				$result[] = $doc;
			}
		}
		
		// #$# 
		$doc = KT_SugarProvider::__fillinMetaData($doc);
		return $result;
	}
}
?>