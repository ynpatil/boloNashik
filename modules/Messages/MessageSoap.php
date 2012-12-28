<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
require_once('include/upload_file.php');
require_once('modules/Messages/Message.php');
require_once('include/upload_file.php');

class MessageSoap{
var $upload_file;
	function MessageSoap(){
		$this->upload_file = new UploadFile('uploadfile');
	}

	function saveFile($message, $portal = false){
        global $sugar_config;

        $focus = new Message();

        if(!empty($message['id'])){
                $focus->retrieve($message['id']);
        }else{
                return '-1';
        }

        if(!empty($message['file'])){
                $decodedFile = base64_decode($message['file']);
                $this->upload_file->set_for_soap($message['filename'], $decodedFile);

                $ext_pos = strrpos($this->upload_file->stored_file_name, ".");
                $this->upload_file->file_ext = substr($this->upload_file->stored_file_name, $ext_pos + 1);
                if (in_array($this->upload_file->file_ext, $sugar_config['upload_badext'])) {
                        $this->upload_file->stored_file_name .= ".txt";
                        $this->upload_file->file_ext = "txt";
                }

               	$focus->save();
                $this->upload_file->final_move($focus->id);
        }else{
                return '-1';
        }
        return $return_id;
	}
}
?>
