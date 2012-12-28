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
require_once('data/SugarBean.php');

class EmailMan extends SugarBean{
	var $id;
	var $deleted;
	var $date_created;
	var $date_modified;
	var $module;
	var $module_id;
	var $marketing_id;
	var $campaign_id;
	var $user_id;
	var $list_id;
	var $invalid_email;
	var $from_name;
	var $from_email;
	var $in_queue;
	var $in_queue_date;
	var $template_id;
	var $send_date_time;
	var $table_name = "emailman";
	var $object_name = "EmailMan";
	var $module_dir = "EmailMan";
	var $send_attempts;
	var $related_id;
	var $related_type;
	var $test=false;
	var $notes_array = array();
	
	function toString(){
		return "EmailMan:\nid = $this->id ,user_id= $this->user_id module = $this->module , related_id = $this->related_id , related_type = $this->related_type ,list_id = $this->list_id, send_date_time= $this->send_date_time\n";
	}

    // This is used to retrieve related fields from form posts.
	var $additional_column_fields = array();

	function EmailMan() {
		parent::SugarBean();



		
	}

	var $new_schema = true;
	function create_list_query($order_by, $where, $show_deleted = 0){

		if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
		{
			
		$query = "SELECT $this->table_name.* ,
					campaigns.name as campaign_name, 
					email_marketing.name as message_name,
					(CASE related_type 
						WHEN 'Contacts' THEN CONCAT(CONCAT(contacts.first_name, '&nbsp;' ), contacts.last_name) 
						WHEN 'Leads' THEN CONCAT(CONCAT(leads.first_name, '&nbsp;' ), leads.last_name) 
						WHEN 'Users' THEN CONCAT(CONCAT(users.first_name, ' ' ), users.last_name) 
						WHEN 'Prospects' THEN CONCAT(CONCAT(prospects.first_name, '&nbsp;' ), prospects.last_name)
					END) recipient_name,";
		}
	    if($this->db->dbType == 'mssql')
		{
				$query = "SELECT $this->table_name.* ,
					campaigns.name as campaign_name, 
					email_marketing.name as message_name,
					(CASE related_type 
						WHEN 'Contacts' THEN contacts.first_name + '&nbsp;' + contacts.last_name 
						WHEN 'Leads' THEN  leads.first_name + '&nbsp;' + leads.last_name 
						WHEN 'Users' THEN  users.first_name + ' ' + users.last_name 
						WHEN 'Prospects' THEN prospects.first_name + '&nbsp;' + prospects.last_name
					END) recipient_name,";
		}

					$query .= " (CASE related_type 
						WHEN 'Contacts' THEN contacts.email1 
						WHEN 'Leads' THEN leads.email1 
						WHEN 'Users' THEN users.email1 
						WHEN 'Prospects' THEN prospects.email1
					END) recipient_email

					FROM $this->table_name
					LEFT JOIN users ON users.id = $this->table_name.related_id and $this->table_name.related_type ='Users'
					LEFT JOIN contacts ON contacts.id = $this->table_name.related_id and $this->table_name.related_type ='Contacts'
					LEFT JOIN leads ON leads.id = $this->table_name.related_id and $this->table_name.related_type ='Leads'
					LEFT JOIN prospects ON prospects.id = $this->table_name.related_id and $this->table_name.related_type ='Prospects'
					LEFT JOIN prospect_lists ON prospect_lists.id = $this->table_name.list_id
					LEFT JOIN campaigns ON campaigns.id = $this->table_name.campaign_id 
					LEFT JOIN email_marketing ON email_marketing.id = $this->table_name.marketing_id ";

		$where_auto = " $this->table_name.deleted=0";

        if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != ""){
			$query .= " ORDER BY $order_by";
		}else
			$query .= " ORDER BY $this->table_name.send_date_time";

		return $query;
	}

	function set_as_sent($email_address,$success=true, $delete= true,$email_id=null, $email_type=null,$activity_type=null,$no_log=false){
					
		//no_log is true when this process encounters an emailaddress from the suppression list.
		if ($no_log) {
			$this->table_name = 'emailman';
			$query = 'DELETE FROM '. $this->table_name . " WHERE id = $this->id";
			$this->db->query($query);
		} else {
		
			global $timedate;
			
			$this->send_attempts++;
			if($delete || $this->send_attempts > 5){
	
				//create new campaign log record.
				require_once('modules/CampaignLog/CampaignLog.php');
				$campaign_log = new CampaignLog();
				$campaign_log->campaign_id=$this->campaign_id;
				$campaign_log->target_tracker_key=$this->target_tracker_key;
				$campaign_log->target_id= $this->related_id;
				$campaign_log->target_type=$this->related_type;
				if (!$this->test) {
					$campaign_log->more_information=$email_address;
				}
				if (!empty($activity_type)) {
					$campaign_log->activity_type=$activity_type;
				} else {
					$campaign_log->activity_type='send error';
				}
				$campaign_log->activity_date=$timedate->to_display_date_time(gmdate("Y-m-d H:i:s"));
				$campaign_log->list_id=$this->list_id;
				if ($success) {
					$campaign_log->related_id= $email_id;
					$campaign_log->related_type=$email_type;
				}
				$campaign_log->save();
						
				if(!$success){
					$campaign_log = new CampaignLog();
					$campaign_log->campaign_id=$this->campaign_id;
					$campaign_log->target_tracker_key=$this->target_tracker_key;
					$campaign_log->target_id= $this->related_id;
					$campaign_log->target_type=$this->related_type;
					if (!$this->test) {
						$campaign_log->more_information=$email_address;
					}
					if (!empty($activity_type)) {
						$campaign_log->activity_type=$activity_type;
					} else {
						$campaign_log->activity_type='send error';
					}
					$campaign_log->activity_date=$timedate->to_display_date_time(gmdate("Y-m-d H:i:s"));
					$campaign_log->list_id=$this->list_id;
					$campaign_log->save();
				}
				$this->table_name = 'emailman';
				$query = 'DELETE FROM '. $this->table_name . " WHERE id = $this->id";
				$this->db->query($query);
			}else{
				//try to send the email again at a later date. currently this timeperiod is set to one day.
				$query = 'UPDATE ' . $this->table_name . " SET in_queue='1', send_attempts='$this->send_attempts', in_queue_date='". gmdate('Y-m-d H:i:s') ."' WHERE id = '$this->id'";
				$this->db->query($query);
			}
		}
	}

	function sendEmail($mail,$testmode=false){
	    $this->test=$testmode;
	    	
		global $beanList, $beanFiles, $sugar_config;
		global $mod_strings;
		$mod_strings = return_module_language( $sugar_config['default_language'], 'EmailMan');

		//get tracking entities locations.
		if (!isset($this->tracking_url)) {
			if (!class_exists('Administration')) {
				require_once('modules/Administration/Administration.php');
			}
			$admin=new Administration();
			$admin->retrieveSettings('massemailer'); //retrieve all admin settings.
		    if (isset($admin->settings['massemailer_tracking_entities_location_type']) and $admin->settings['massemailer_tracking_entities_location_type']=='2'  and isset($admin->settings['massemailer_tracking_entities_location']) ) {
				$this->tracking_url=$admin->settings['massemailer_tracking_entities_location'];
		    } else {
				$this->tracking_url=$sugar_config['site_url'];
		    }
		} 
		if(!isset($beanList[$this->related_type])){
			return false;
		}
		$class = $beanList[$this->related_type];
		if (!class_exists($class)) {
			require_once($beanFiles[$class]);
		}

		if (!class_exists('Email')) {
			require_once('modules/Emails/Email.php');
		}

		$module = new $class();
		$module->retrieve($this->related_id);
		if ((!isset($module->email_opt_out) || $module->email_opt_out != 'on') && (!isset($module->invalid_email) || $module->invalid_email != 1)){
			$lower_email_address=strtolower($module->email1);
			//test against indivdual address.
			if (isset($this->restricted_addresses) and isset($this->restricted_addresses[$lower_email_address])) {
				$this->set_as_sent($lower_email_address,true, true,null,null,null,true);
				return true;	
			} 
			//test against restricted domains
			$at_pos=strrpos($lower_email_address,'@');
			if ($at_pos !== false) {
				foreach ($this->restricted_domains as $domain=>$value) {
					$pos=strrpos($lower_email_address,$domain);
					if ($pos !== false && $pos > $at_pos) {
						//found						
						$this->set_as_sent($lower_email_address,true, true,null,null,null,true);
						return true;	
					}
				}
			}
			//test for duplicate email address.
			if (!empty($module->email1) and !empty($this->campaign_id)) {
				$dup_query="select id from campaign_log where more_information='".$module->email1."' and campaign_id='".$this->campaign_id."'";
				$dup=$this->db->query($dup_query);
				$dup_row=$this->db->fetchByAssoc($dup);
				if (!empty($dup_row)) {
					//email address was processed //silent delete this entry.
					$this->set_as_sent($module->email1,true, true,null,null,null,true);
					return true;	
				}
			}

			$start = microtime();
			$this->target_tracker_key=create_guid();

			//fetch email marketing.			
			if (empty($this->current_emailmarketing) or !isset($this->current_emailmarketing)) {
				if (!class_exists('EmailMarketing')) {
					require_once('modules/EmailMarketing/EmailMarketing.php');
				}
				
				$this->current_emailmarketing=new EmailMarketing();
			} 
			if (empty($this->current_emailmarketing->id) or $this->current_emailmarketing->id !== $this->marketing_id) {
				$this->current_emailmarketing->retrieve($this->marketing_id);
			}
			//fetch email template associate with the marketing message.
			if (empty($this->current_emailtemplate) or $this->current_emailtemplate->id !== $this->current_emailmarketing->template_id) {
				if (!class_exists('EmailTemplate')) {
					require_once('modules/EmailTemplates/EmailTemplate.php');
				}
				$this->current_emailtemplate= new EmailTemplate();
				
				$this->current_emailtemplate->retrieve($this->current_emailmarketing->template_id);

				//escape email template contents.
				$this->current_emailtemplate->subject=from_html($this->current_emailtemplate->subject);
				$this->current_emailtemplate->body_html=from_html($this->current_emailtemplate->body_html);
				$this->current_emailtemplate->body=from_html($this->current_emailtemplate->body);
				

				$q = "SELECT * FROM notes WHERE parent_id = '".$this->current_emailtemplate->id."' AND deleted = 0";
				$r = $this->db->query($q);

				// cn: bug 4684 - initialize the notes array, else old data is still around for the next round
				$this->notes_array = array();

				while($a = $this->db->fetchByAssoc($r)) {
					$noteTemplate = new Note();
					$noteTemplate->retrieve($a['id']);
					$this->notes_array[] = $noteTemplate;
				}


			}
			//fetch mailbox details..
			if (empty($this->current_mailbox)) {
				if (!class_exists('InboundEmail')) {
					require_once('modules/InboundEmail/InboundEmail.php');
				}
				$this->current_mailbox= new InboundEmail();
			} 
			if (empty($this->current_mailbox->id) or $this->current_mailbox->id !== $this->current_emailmarketing->inbound_email_id) {
				$this->current_mailbox->retrieve($this->current_emailmarketing->inbound_email_id);
				//extract the email address.
				$this->mailbox_from_addr=$this->current_mailbox->get_stored_options('from_addr','nobody@example.com',null);
			}
			
			//fetch campaign  details..
			if (empty($this->current_campaign)) {
				if (!class_exists('Campaign')) {
					require_once('modules/Campaigns/Campaign.php');
				}
				$this->current_campaign= new Campaign();
			} 
			if (empty($this->current_campaign->id) or $this->current_campaign->id !== $this->current_emailmarketing->campaign_id) {
				$this->current_campaign->retrieve($this->current_emailmarketing->campaign_id);

				//load defined tracked_urls
				$this->current_campaign->load_relationship('tracked_urls'); 
				$query_array=$this->current_campaign->tracked_urls->getQuery(true);
				$query_array['select']="SELECT tracker_name, tracker_key, id, is_optout ";
				$result=$this->current_campaign->db->query(implode(' ',$query_array));

				$this->has_optout_links=false;
				while (($row=$this->current_campaign->db->fetchByAssoc($result)) != null) {
					$this->tracker_urls['{'.$row['tracker_name'].'}']=$row;				
					//has the user defined opt-out links for the campaign.
					if ($row['is_optout']==1) {
						$this->has_optout_links=true;
					}	
				}
			}

            //BEGIN:this code will trigger for only campaigns pending before upgrade to 4.2.0.
            //will be removed for the next release.
			$btracker=true;
			$tracker_url = $this->tracking_url . '/campaign_tracker.php?track=' . $this->current_campaign->tracker_key.'&identifier='.$this->target_tracker_key;
			$tracker_text = $this->current_campaign->tracker_text;
			if(empty($tracker_text)) {
				$btracker=false;
			}
			//END
			
			$mail->ClearAllRecipients();
			$mail->ClearReplyTos();
			$mail->Sender	= $this->mailbox_from_addr;
			$mail->From     = $this->mailbox_from_addr;
			$mail->FromName = $this->current_emailmarketing->from_name;
            $mail->AddReplyTo($this->mailbox_from_addr, $this->current_emailmarketing->from_name);
			//parse and replace bean variables.
			$template_data=  $this->current_emailtemplate->parse_email_template(array('subject'=>$this->current_emailtemplate->subject,
																					  'body_html'=>$this->current_emailtemplate->body_html,
																					  'body'=>$this->current_emailtemplate->body,
																					  )
																					  ,'Contacts', $module);
			//parse and replace urls.
			//this is new style of adding tracked urls to a campaign.
			$tracker_url_template= $this->tracking_url . '/campaign_trackerv2.php?track=%s'.'&identifier='.$this->target_tracker_key;
			$removeme_url_template=$this->tracking_url . '/removeme.php?identifier='.$this->target_tracker_key;
			$template_data=  $this->current_emailtemplate->parse_tracker_urls($template_data,$tracker_url_template,$this->tracker_urls,$removeme_url_template);
			$mail->AddAddress($module->email1, $module->first_name . ' ' . $module->last_name);
			$mail->Subject =  $template_data['subject'];
            
			$mail->Body = wordwrap($template_data['body_html'], 900);

            //BEGIN:this code will trigger for only campaigns pending before upgrade to 4.2.0.
            //will be removed for the next release.
            if ($btracker) {
				$mail->Body .= "<br><br><a href='". $tracker_url ."'>" . $tracker_text . "</a><br><br>";
            } else {
            	if (!empty($tracker_url)) {
            		$mail->Body = str_replace('TRACKER_URL_START', "<a href='" . $tracker_url ."'>", $mail->Body);
	            	$mail->Body = str_replace('TRACKER_URL_END', "</a>", $mail->Body);
            		$mail->AltBody = str_replace('TRACKER_URL_START', "<a href='" . $tracker_url ."'>", $mail->AltBody);
            		$mail->AltBody = str_replace('TRACKER_URL_END', "</a>", $mail->AltBody);
            	} 
            }
			//END
			
			//do not add the default remove me link if the campaign has a trackerurl of the opotout link
			if ($this->has_optout_links==false) {
				$mail->Body .= "<br><font size='2'>{$mod_strings['TXT_REMOVE_ME']}<a href='". $this->tracking_url . "/removeme.php?identifier={$this->target_tracker_key}'>{$mod_strings['TXT_REMOVE_ME_CLICK']}</a></font>";
			}
			//add image reference to track opening of html emails.
			$mail->Body .= "<br><IMG HEIGHT=1 WIDTH=1 src={$this->tracking_url}/image.php?identifier={$this->target_tracker_key}>";

			$mail->AltBody = $template_data['body'];
	    	if ($btracker) {
	    		$mail->AltBody .="\n". $tracker_url;
	    	}
			if ($this->has_optout_links==false) {
	    		$mail->AltBody .="\n\n\n{$mod_strings['TXT_REMOVE_ME_ALT']} ". $this->tracking_url . "/removeme.php?identifier=$this->target_tracker_key";
			}
			
			// cn: bug 4684, handle attachments in email templates.
			$mail->handleAttachments($this->notes_array);
			
	    	$success = $mail->send();
			if($success ){
				$email = new Email();



				$email->to_addrs= $module->first_name . ' ' . $module->last_name . '&lt;'.$module->email1.'&gt;';
				$email->to_addrs_ids = $module->id .';';
				$email->to_addrs_names = $module->first_name . ' ' . $module->last_name . ';';
				$email->to_addrs_emails = $module->email1 . ';';
				$email->type= 'archived';
				$email->deleted = '0';
				$email->name = $this->current_campaign->name.': '.$mail->Subject ;
				$email->description_html = $mail->AltBody;
				$email->description = $mail->AltBody;
				$email->from_addr = $mail->From;
				$email->assigned_user_id = $this->user_id;
				$email->parent_type = $this->related_type;
				$email->parent_id = $this->related_id ;

				$email->date_start =date('Y-m-d');
				$email->time_start =date('H:i:s');
				$email->status='sent';
				$retId = $email->save();
				
				foreach($this->notes_array as $note) {
					if(!class_exists('Note')) {
						require_once('modules/Notes/Note.php');
					}
					// create "audit" email without duping off the file to save on disk space
					$noteAudit = new Note();
					$noteAudit->parent_id = $retId;
					$noteAudit->parent_type = $email->module_dir;
					$noteAudit->description = "[".$note->filename."] ".$mod_strings['LBL_ATTACHMENT_AUDIT'];
					$noteAudit->save();
				}
				
				
				if (!empty($this->related_id ) && !empty($this->related_type)) {
					
					//save relationships.
					switch ($this->related_type)  {
						case 'Users':
							$rel_name="users";
							break;
							
						case 'Prospects':
							$rel_name="prospects";
							break;

						case 'Contacts':
							$rel_name="contacts";
							break;

						case 'Leads':	
							$rel_name="leads";
							break;
					}
					
					if (!empty($rel_name)) {
						$email->load_relationship($rel_name);
						$email->$rel_name->add($this->related_id);
					}	
				}
			}
			if ($success) {
				$this->set_as_sent($module->email1,$success, $success,$email->id,'Emails','targeted');
			} else {
				//log send error.
				$this->set_as_sent($module->email1,$success, $success);			
			}
		}else{
			$mail->ErrorInfo .= "\nRecipient Email Opt Out";
			$success = false;
			if (isset($module->email_opt_out) && $module->email_opt_out == 'on') {
				$this->set_as_sent($module->email1,$success, true,null,null,'removed');				
			} else {
				if (isset($module->invalid_email) && $module->invalid_email == 1) {
					$this->set_as_sent($module->email1,$success, true,null,null,'invalid email');				
				} else {
					$this->set_as_sent($module->email1,$success, true);									
				}			
			}
		}
		return $success;
	}
}
?>
