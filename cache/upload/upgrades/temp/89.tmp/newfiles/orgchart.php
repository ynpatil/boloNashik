<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
/*****************************************************************************
 * The contents of this file are subject to the RECIPROCAL PUBLIC LICENSE
 * Version 1.1 ("License"); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://opensource.org/licenses/rpl.php. Software distributed under the
 * License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND,
 * either express or implied.
 *
 * You may:
 * a) Use and distribute this code exactly as you received without payment or
 *    a royalty or other fee.
 * b) Create extensions for this code, provided that you make the extensions
 *    publicly available and document your modifications clearly.
 * c) Charge for a fee for warranty or support or for accepting liability
 *    obligations for your customers.
 *
 * You may NOT:
 * a) Charge for the use of the original code or extensions, including in
 *    electronic distribution models, such as ASP (Application Service
 *    Provider).
 * b) Charge for the original source code or your extensions other than a
 *    nominal fee to cover distribution costs where such distribution
 *    involves PHYSICAL media.
 * c) Modify or delete any pre-existing copyright notices, change notices,
 *    or License text in the Licensed Software
 * d) Assert any patent claims against the Licensor or Contributors, or
 *    which would in any way restrict the ability of any third party to use the
 *    Licensed Software.
 *
 * You must:
 * a) Document any modifications you make to this code including the nature of
 *    the change, the authors of the change, and the date of the change.
 * b) Make the source code for any extensions you deploy available via an
 *    Electronic Distribution Mechanism such as FTP or HTTP download.
 * c) Notify the licensor of the availability of source code to your extensions
 *    and include instructions on how to acquire the source code and updates.
 * d) Grant Licensor a world-wide, non-exclusive, royalty-free license to use,
 *    reproduce, perform, modify, sublicense, and distribute your extensions.
 *
 * The Original Code is: AnySoft Informatica
 *                       Marcelo Leite (aka Mr. Milk)
 *                       2005-10-01 mrmilk@anysoft.com.br
 *
 * TeamsOS Updated code: CRMUpgrades.com
 *                       Kenneth Brill
 *                       2007-01-06 ken.brill@gmail.com
 *
 * The Initial Developer of the Original Code is AnySoft Informatica Ltda.
 * Portions created by AnySoft are Copyright (C) 2005 AnySoft Informatica Ltda
 * All Rights Reserved.
 ********************************************************************************/

	$GLOBALS['sugarEntry'] = true;
	require_once('modules/Users/User.php');
	require_once('include/database/PearDatabase.php');
    require_once('include/database/DBManager.php');
	require_once('include/utils.php');
	require_once('log4php/LoggerManager.php');
    require_once('include/entryPoint.php');
    require_once('modules/TeamsOS/TeamOS.php');
	$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM');

	$colors = array(
		 'first_entity'   => "#ffe9e9",
		 'second_entity'  => "#eeeeee",
		 'third_entity'   => '#ffffe1',
		 'current_record' => '#cccccc',
		 'not_authorized' => '#ff8383',
		 );
#	$mods = array(
#		'A' => 'Accounts',
#		'C' => 'Contacts',
#		'U' => 'Users',
#		'E' => 'Employees',
#	);

	$security = false;
	if(isset($_REQUEST['pid']) && isset($_REQUEST['eid']))
		save_record($_REQUEST['pid'], $_REQUEST['eid']);

	init_session();

	if(!empty($_REQUEST['record']))
	{
		if($security && file_exists('modules/TeamSecurity/TeamSecurity.php'))
			init_security();

		if(isset($_REQUEST['module']))
			build_chart($_REQUEST['record'], $_REQUEST['module']);
		else
			die("No module specified");
	}

//------------------------------------------------------------------

	function save_record($id1, $id2)
	{
		$pid = substr($_REQUEST['pid'], 2);
		$eid = substr($_REQUEST['eid'], 2);
		$mod = substr($_REQUEST['eid'], 0, 1);
		if(substr($_REQUEST['pid'], 0, 1) != $mod) $pid = '';

		$db = PearDatabase::getInstance();
		switch ($mod) {
			case 'C':
				$_REQUEST['module'] = 'Contacts';
				$sql = "UPDATE contacts SET reports_to_id = '$pid' WHERE id = '$eid'";
				break;
			case 'A':
				$_REQUEST['module'] = 'Accounts';
				$sql = "UPDATE accounts SET parent_id = '$pid' WHERE id = '$eid'";
				break;
			case 'E':
				$_REQUEST['module'] = 'Employees';
				$sql = "UPDATE users SET reports_to_id = '$pid' WHERE id = '$eid'";
				break;
			case 'U':
				$_REQUEST['module'] = 'Users';
				$sql = "UPDATE users SET reports_to_id = '$pid' WHERE id = '$eid'";
				break;
		}
		$result = $db->query($sql);

		$_REQUEST['record'] = $eid;
		unset($_REQUEST['eid']);
		unset($_REQUEST['pid']);
	}

	function init_session()
	{
		require_once('config.php');
		global $sugar_config;
		global $current_user;
		global $mod_strings;
		global $app_strings;

		$current_user = new User();
		if (!empty($sugar_config['session_dir'])) {
			session_save_path($sugar_config['session_dir']);
		}
		session_start();

		if(isset($_SESSION['authenticated_user_id']))
		{
			$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
			if($result == null) {
				session_destroy();
				header("Location: index.php?action=Login&module=Users");
			}
		}
		else
		{
			session_destroy();
			header("Location: index.php?action=Login&module=Users");
		}

		if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
			$current_language = $_SESSION['authenticated_user_language'];
		else
			$current_language = $sugar_config['default_language'];

		$mod_strings = return_module_language($current_language, $_REQUEST['module']);
		$app_strings = return_application_language($current_language);

	}

	function init_security()
	{
		require_once('modules/TeamSecurity/TeamSecurity.php');
		global $current_team;
		if(!isset($current_team)) {
		$current_team = new Team();
		}
	}

	function build_chart($parent_id, $type)
	{
		add_header();

#		echo "type=$type parent_id=$parent_id\n";
		if($type=="Users" || $type=="Employees")
			get_children('', true, $parent_id, $type);
		else {
			$curr_id = get_top_company($parent_id, $type);
			if(!empty($curr_id)) {
				get_children($curr_id, true, $parent_id, 'Accounts');
			}
		}
		add_footer();
	}

	function get_top_company($parent_id, $type='Accounts')
	{
		$companies = array();
		$users = array();
		$curr_id = $parent_id;
		$db =& PearDatabase::getInstance();

		if($type == 'Contacts')
		{
			$sql = "SELECT account_id as parent_id FROM accounts_contacts WHERE deleted=0 AND contact_id = '$curr_id'";
			$result = $db->query($sql);
			if($db->getRowCount($result) > 0)
			{
				$row = $db->fetchByAssoc($result);
				$curr_id = $row['parent_id'];
			}
		}

		$sql = "SELECT parent_id, name FROM accounts WHERE deleted=0 AND id = '$curr_id'";
		$result = $db->query($sql);
		while($row = $db->fetchByAssoc($result))
		{
			if(!empty($row['parent_id']) && !in_array($row['parent_id'], $companies))
			{
				$curr_id = $row['parent_id'];
				$companies[] = $row['parent_id'];
				$sql = "SELECT parent_id, name FROM accounts WHERE deleted=0 AND id = '$curr_id'";
				$result = $db->query($sql);
			}
		}
		return $curr_id;
	}

	function get_children($id, $first=false, $focus_id='', $type='Accounts')
	{
		static $indent;
		static $done_already = array();
		static $current_acc;
		global $current_team;
		global $security;
		global $mod_strings;
		global $colors;

		$focus_teams = new TeamOS();


#debug		echo "type=$type id=$id  \n";
		$restricted_color = $colors['not_authorized'];
		if($type=='Employees')
			$status_fld = 'employee_status';
		else
			$status_fld = "ifnull(status, 'Employee')";

		if(isset($mod_strings['LBL_ORGCHART_COMPANY']))
			$company = $mod_strings['LBL_ORGCHART_COMPANY'];

		$db =& PearDatabase::getInstance();
		if($first)
		{
			if($type == 'Accounts')
			{
				$sql  = "SELECT ";
				$sql .= "  '' as reports_to_id, id, name, industry as tooltip, 'Accounts' as type, assigned_user_id, id as acc_id ";
				$sql .= "FROM ";
				$sql .= "  accounts ";
				$sql .= "WHERE ";
				$sql .= "  deleted=0 AND id = '$id'";
			}
			elseif($type == 'Contacts')
			{
				$sql  = "SELECT ";
				$sql .= "  reports_to_id, id, concat(ifnull(first_name, ''), ' ', ifnull(last_name, '')) as name, title as tooltip, 'Contacts' as type, assigned_user_id, '' as acc_id ";
				$sql .= "FROM ";
				$sql .= "  contacts ";
				$sql .= "WHERE ";
				$sql .= "  deleted=0 AND id = '$id'";
			}
			else
			{
				$sql  = "SELECT ";
				$sql .= "  '' as reports_to_id, '' as id, '$company' as name, '' as tooltip, '$type' as type, '1' as assigned_user_id, '' as acc_id, $status_fld as status FROM users LIMIT 1";
			}
		}
		else
		{
			if($type == 'Accounts')
			{
				$sql = "(SELECT ";
				$sql .= "  contacts.reports_to_id as reports_to_id, contacts.id as id, concat(ifnull(first_name, ''), ' ', ifnull(last_name, '')) as name, title as tooltip, 'Contacts' as type, assigned_user_id, account_id as acc_id ";
				$sql .= "FROM ";
				$sql .= "  accounts_contacts ";
				$sql .= "INNER JOIN ";
				$sql .= "  contacts ";
				$sql .= "ON ";
				$sql .= "  accounts_contacts.contact_id=contacts.id ";
				$sql .= "WHERE ";
				$sql .= "  accounts_contacts.deleted=0 AND contacts.deleted=0 AND accounts_contacts.account_id = '$id') ";
				$sql .= "UNION ALL ";
				$sql .= "(SELECT ";
				$sql .= "  '', id, name, industry, 'Accounts', assigned_user_id, id ";
				$sql .= "FROM ";
				$sql .= "  accounts ";
				$sql .= "WHERE ";
				$sql .= "  deleted=0 AND parent_id = '$id') ";
				$sql .= "ORDER BY ";
				$sql .= "  type DESC, reports_to_id";
			}
			elseif($type == 'Contacts')
			{
				$sql  = "SELECT ";
				$sql .= "  reports_to_id, contacts.id as id, concat(ifnull(first_name, ''), ' ', ifnull(last_name, '')) as name, title as tooltip, 'Contacts' as type, assigned_user_id, account_id as acc_id ";
				$sql .= "FROM ";
				$sql .= "  contacts ";
				$sql .= "LEFT JOIN ";
				$sql .= "  accounts_contacts ";
				$sql .= "ON ";
				$sql .= "  contacts.id=accounts_contacts.contact_id ";
				$sql .= "WHERE ";
				$sql .= "  contacts.deleted=0 AND reports_to_id = '$id'";
			}
			else
			{
				$sql  = "SELECT ";
				$sql .= "  '' as reports_to_id, id, concat(ifnull(first_name, ''), ' ', ifnull(last_name, '')) as name, department as tooltip, '$type' as type, created_by as assigned_user_id, id as acc_id, $status_fld as status ";
				$sql .= "FROM ";
				$sql .= "  users ";
				$sql .= "WHERE ";
				$sql .= "  deleted=0 AND ";
				if ($id == "")
					$sql .= "(reports_to_id = '$id' or reports_to_id IS NULL) ";
				else
					$sql .= "reports_to_id = '$id' ";
				$sql .= "ORDER BY ";
				$sql .= "  type DESC, reports_to_id";
//				echo "<pre>$sql</pre>";
			}
		}
		$result = $db->query($sql);
		$rows = $db->getRowCount($result);

		$i = 0;
		if($rows > 0)
		{
			$table_id = "";
			if(!$first)
				add_link($indent, true);

			$indent .= str_repeat(' ', 2);
			echo $indent."<table style='margin-left: auto; margin-right: auto' cellspacing=0 cellpadding=0>"."\n";
			echo $indent."  <tr>"."\n";

			while ($i < $rows)
			{
				$link = '';
				$circ_ref = '';
				$row = $db->fetchByAssoc($result);
				if(isset($row['status']))
					$status = $row['status'];
				else
					$status = '';

				if(!in_array($row['id'], $done_already))
				{
					if($type != 'Contacts')
						$current_acc = $row['acc_id'];

					$done_already[] .= $row['id'];
					$indent .= str_repeat(' ', 4);

					if(!empty($row['acc_id']) && ($row['acc_id'] != $current_acc))
						$link = "###";

				}
				elseif($row['type'] == $type)
					$circ_ref = $mod_strings['LBL_ORGCHART_CIRCULAR_REFERENCE'];

				else
					$circ_ref = 'Skip Done';

				if($circ_ref != 'Skip Done')
				{
					if(!empty($circ_ref))
						$circ_ref = '<b>'.$circ_ref.'</b>';

/* begin Lampada change */
					if($row['type']=="Contacts" || $row['type']=="Accounts") {
						$get_team_sql = "SELECT " . $row['type'] . "_cstm.assigned_team_id_c AS team
						                 FROM " . $row['type'] . ", " . $row['type'] . "_cstm
										 WHERE " . $row['type'] . ".id = " . $row['type'] . "_cstm.id_c
										        AND " . $row['type'] . ".id = '" . $row['id'] . "'";
						$team_result = $db->query($get_team_sql);
						$team_row = $db->fetchByAssoc($team_result);
						$row['description']=$row['tooltip'];
					} else {
						$get_team_sql = "SELECT users_cstm.default_team_id_c AS team,
						                        users.title AS title,
						                        users.department AS department
						                 FROM users, users_cstm
										 WHERE users.id = users_cstm.id_c
										        AND users.id = '" . $row['id'] . "'";
						$team_result = $db->query($get_team_sql);
						$team_row = $db->fetchByAssoc($team_result);
						if($db->getRowCount($team_result)<1) {
							$get_team_sql = "SELECT users.title AS title,
							                        users.department AS department
							                 FROM users
											 WHERE users.id = '" . $row['id'] . "'";
							$team_result = $db->query($get_team_sql);
							$team_row = $db->fetchByAssoc($team_result);
						}
						$row['description']=$team_row['title'];
						if($row['department']!='') {
							$row['description'] .= " / " . $row['department'];
						}

					}

					$row['tooltip']="";
					$team_membership_result = $focus_teams->retrieve_team_id($row[id]);
					foreach($team_membership_result AS $id=>$thename) {
						$row['tooltip'] .= $thename . ", ";
					}
					$row['tooltip']=substr($row['tooltip'],0,strlen($row['tooltip'])-2);

					if($team_row['team']!="") {
						$row['description'] .= "</a><BR><b><font color=blue>" . $focus_teams->get_team_name($team_row['team']) . "</font></b>";
					} else {
						$row['description'] .= "</a>";
					}
/* end Lampada change */

					if($row['type'] != 'Contacts' || !$security || $current_team->can_access_record($row['id'], 'detailview')=='')
						$link .= "<a status='' title='" . strip_tags($row['tooltip']) . "' style='text-decoration: none' href='' onClick=" . '"' . "JavaScript:window.opener.location='index.php?action=DetailView&module=" . $row['type'] . "&record=" . $row['id'] . "';window.close();return false;" . '"' . "'>$circ_ref " . $row['name'] . "<hr>" . $row['description'];
					else
						$link .= "<a href='' onclick='JavaScript:restricted();return false;' style='text-decoration: none; color: $restricted_color'>$circ_ref ".$mod_strings['LBL_ORGCHART_NOT_AUTHORIZED']."</a>";

					$focus = $row['id'] == $focus_id ? true : false;
					$tipo  = substr($link, 0, 3) == "###" ? "External" : $row['type'];
#debug					echo "\$row['id']=" . $row['id'] . " type=$type\n";
					if (($row['id'] == '') && (($type == 'Users') || ($type == 'Employees'))) {
						$id = 'O-'.$row['id'];
					} else {
						$id = substr($row['type'], 0, 1).'-'.$row['id'];
					}

					add_box($link, $indent, $first, $focus, get_color($tipo, $status), $id);
				}

				if(empty($circ_ref))
					get_children($row['id'], false, $focus_id, $row['type']);

				if($circ_ref != 'Skip Done')
				{
					echo $indent."</td>"."\n";
					$indent = substr($indent, 0, -4);
				}

				if($circ_ref == $mod_strings['LBL_ORGCHART_CIRCULAR_REFERENCE'])
				{
					echo $indent."  </tr>"."\n";
					echo $indent."</table>"."\n";
					$indent = substr($indent, 0, -2);
				}
				$i++;
			}

			echo $indent."  </tr>"."\n";
			echo $indent."</table>"."\n";
			$indent = substr($indent, 0, -2);
		}
		else
			add_link($indent, false);
	}

	function add_header()
	{
	global $security;
	global $current_team;

	global $mod_strings;
	$title = $mod_strings['LBL_ORGCHART_TITLE'];
	$first_entity = $mod_strings['LBL_ORGCHART_1ST_ENTITY'];
	$second_entity = $mod_strings['LBL_ORGCHART_2ND_ENTITY'];
	$third_entity = $mod_strings['LBL_ORGCHART_3RD_ENTITY'];
	$current_record = $mod_strings['LBL_ORGCHART_CURR_RECORD'];
	$not_authorized = $mod_strings['LBL_ORGCHART_NOT_AUTHORIZED'];
	$description = $mod_strings['LBL_ORGCHART_DESCRIPTION'];

	global $colors;
	$color_first    = $colors['first_entity'];
	$color_second   = $colors['second_entity'];
	$color_third    = $colors['third_entity'];
	$color_current  = $colors['current_record'];
	$color_restrict = $colors['not_authorized'];

	$tb = <<<EOQ
  <h3 style='font-size: 14px; font-variant: small-caps;'>$title
  <hr>
  <table cellSpacing=1 cellPadding=0 width="100%" style='border: 1px solid #dfdfdf;'>
	 <tr>
		<td>$description:&nbsp;&nbsp;</td>
		<td align=center width=120 style='color: gray; background-color: $color_first'>$first_entity</td>
		<td align=center width=120 style='color: gray; background-color: $color_second'>$second_entity</td>
		<td align=center width=120 style='color: gray; background-color: $color_third'>$third_entity</td>
		<td align=center width=120 style='color: gray; background-color: $color_current'>$current_record</td>

EOQ;
	if($security)
		if($current_team->is_admin != 'on')
			$tb .= "      <td align=center width=120 style='color: $color_restrict; background-color: $color_second'>$not_authorized</td>"."\n";
	$tb .= <<<EOQ
	 </tr>
  </table>
  <br><br>
  </head>
  <title>CallRooM</title>
  <body LANGUAGE=javascript style='margin: 10px 5px 5px 5px; background-color: #ffffff;'>
  <div id="drag_el" style="position:absolute; display:none">Drop on the parent</div>
EOQ;

	echo "<html>"."\n";
	echo "<head>"."\n";
    echo "<!-- Dependencies -->\n";
	echo "<script src=\"include/javascript/yui/YAHOO.js\"></script>\n";
	echo "<!-- Source file -->\n";
	echo "<script src=\"include/javascript/yui/dom.js\"></script>\n";
	echo "<script src=\"include/javascript/yui/event.js\"></script>\n";
	add_style();
	echo $tb . "\n";
	}

	function get_color($type, $status)
	{
		global $colors;
		switch ($type) {
			case "Accounts":
				$color = $colors['first_entity'];
				break;
			case "Contacts":
				$color = $colors['second_entity'];
				break;
			case "External":
				$color = $colors['third_entity'];
				break;
			default:
				switch ($status) {
					case 'Terminated':
						$color = $colors['first_entity'];
						break;
					case 'Inactive':
						$color = $colors['first_entity'];
						break;
					case 'Active':
						$color = $colors['second_entity'];
						break;
					case 'Leave of Absence':
						$color = $colors['third_entity'];
						break;
					case 'Employee':
						$color = $colors['third_entity'];
						break;
					default:
						$color = $colors['second_entity'];
						break;
				}
		}
		return $color;
	}

	function add_box($data, $indent, $first, $focused, $color='', $id)
	{
		global $colors;

		if(strstr($data, '###'))
			$data = str_replace('###', '', $data);

		if(!$first)
			echo $indent."<td align=center style='border-top: 1px solid silver;' valign=top>"."\n";
		else
			echo $indent."<td align=center style='border-top: 0px solid silver;' valign=top>"."\n";

		$box_type = "box_moveable";
		if(strpos($data, "restricted")===false)
			echo $indent."  <table align=center class='outline_box' width=90 cellspacing=0 cellpadding=0>"."\n";
		else {
			echo $indent."  <table align=center class='outline_box' width=90 cellspacing=0 cellpadding=0>"."\n";
			$id = 'na';
			$box_type = "box_restricted";
		}

		if(!$first)
		{
			echo $indent."    <tr>"."\n";
			echo $indent."      <td align=center>"."\n";
			echo $indent."        <img src='include/images/orgline.png' width=1 height=10>"."\n";
			echo $indent."      </td>"."\n";
			echo $indent."    </tr>"."\n";
		}
		echo $indent."    <tr>"."\n";

		if($focused)
			$color = $colors['current_record'];

		echo $indent."      <td id='$id' class='$box_type' align=center style='background-color: $color; padding-left: 5px; padding-right: 5px; margin-left: 10px; margin-right: 10px; border: 1px solid #dfdfdf;' height=45>"."\n";
		echo $indent."        <font color=#0094F2>".$data."</font>\n";
		echo $indent."      </td>"."\n";
		echo $indent."    </tr>"."\n";
	}

	function add_link($indent, $has_child)
	{
		echo $indent."    <tr>"."\n";
		echo $indent."      <td align=center>"."\n";

		if($has_child)
			echo $indent."        <img src='include/images/orgline.png' width=1 height=10>"."\n";

		echo $indent."      </td>"."\n";
		echo $indent."    </tr>"."\n";
		echo $indent."  </table>"."\n";

	}

	function add_footer()
	{
		echo "</body>"."\n";
		echo "</html>"."\n";
		add_script();
	}

	function add_script()
	{
	global $mod_strings;
	global $app_strings;
	$na = $mod_strings['LBL_ORGCHART_NOT_AUTHORIZED'];
	$msg = $mod_strings['LBL_ORGCHART_NOT_AUTORIZED_MSG'];
	$reports = $app_strings['LBL_ORGCHART_REPORTS'];
	$noone = $app_strings['LBL_ORGCHART_NOONE'];

	$script = <<<EOQ
	<script type="text/javascript">
		var zoomed = false;
		var msg = false;
		var dragging = false;
		var a, b, x, y;
		var w, r;
		var t = "<br>$reports<br>";
		var drag_el_xy;

		function applyEventListeners()
		{
			var boxElements = YAHOO.util.Dom.getElementsByClassName("box_moveable");
			YAHOO.util.Event.addListener(boxElements, 'mousedown', drag_start);
			YAHOO.util.Event.addListener(boxElements, 'click', collapse);
			var boxElements = YAHOO.util.Dom.getElementsByClassName("box_restricted");
			YAHOO.util.Event.addListener(boxElements, 'click', collapse);
			YAHOO.util.Event.addListener(document, 'dblclick', zoom);
		}

		function zoom()
		{
			if(msg) {
				msg = false;
				return;
			}

			var td_count = document.getElementsByTagName('td').length;
			for (var i=0; i<td_count; i++) {
				td = document.getElementsByTagName('td')[i];
				if(td.className == 'box_moveable') {
					var tr = td.parentNode;
					var table = tr.parentNode;
					table = table.parentNode;
					if(!zoomed) {
						anchor = td.getElementsByTagName('a')[0];
						anchor.style.display = 'none';
						td.title = anchor.innerHTML;
						td.height = 12;
						table.width = 24;
					} else {
						anchor = td.getElementsByTagName('a')[0];
						anchor.style.display = '';
						td.title = '';
						td.height = 45;
						table.width = 90;
					}
				}
			}
			zoomed = !zoomed;
		}

		function move(ev)
		{
			var el = YAHOO.util.Event.getTarget(ev, 1);

			if (dragging && (!zoomed) && (el != undefined) ) {
				var drag_el = YAHOO.util.Dom.get("drag_el");
				YAHOO.util.Dom.setXY('drag_el', [ev.clientX + 5, ev.clientY + 5]);

				go = false;
				var v = w + t;
				if (el.className == 'box_moveable' && el.id != "na" &&
				    el.id != i) {
					var m = el.id.substr(0, 1) == i.substr(0, 1) ? true : false;
					if (m) {
						v = v + el.innerHTML;
						if(w != el.innerHTML)
							go = true;
					} else {
						v = v + "$noone";
						go = true;
					}
				}
				drag_el.innerHTML = v;
				if (go) {
					r = el.id;
				} else {
					r = '';
				}
			}
		}


		function drag_start(ev)
		{
			if (!zoomed) {
				var drag_el = YAHOO.util.Dom.get('drag_el');
				YAHOO.util.Dom.setStyle('drag_el', 'display', 'block');
				YAHOO.util.Dom.setXY('drag_el', [ev.clientX + 5, ev.clientY + 5]);
				i = this.id;
				r = '';
				w = this.innerHTML;

				dragging = true;
				drag_el.innerHTML = this.innerHTML + t;
				YAHOO.util.Event.addListener(document, 'mousemove', move);
				YAHOO.util.Event.addListener(document, 'mouseup', drag_stop);
			}
		}

		function drag_stop()
		{
			var drag_el = YAHOO.util.Dom.get('drag_el');
			YAHOO.util.Dom.setStyle('drag_el', 'display', 'none');
			YAHOO.util.Event.removeListener(document, 'mousemove', move);
			YAHOO.util.Event.removeListener(document, 'mouseup', drag_stop);
			dragging = false;
			if(r != '' && i != '') {
				window.location = 'orgchart.php?pid='+r+'&eid='+i;
			}
			return false;
		}

		function collapse(ev)
		{
			var el = this;

			if ((el.className == 'box_moveable') || (el.className == 'box_restricted')) {
				el = el.parentNode.parentNode.parentNode;
			}
			el = el.nextSibling;

			while(el != undefined)
			{
				if (el.nodeType == 1) {
					if(el.style.display != 'none')
						el.style.display = 'none';
					else
						el.style.display = 'block';
				}

				el = el.nextSibling;
			}
			return false;
		}

		function restricted()
		{
			alert('$msg');
			msg = true;
		}

		applyEventListeners();
  </script>
EOQ;
	echo $script;
	}

	function add_style()
	{
		$style = <<<EOQ
  <style>
		body {
		margin: 10px 5px 5px 5px;
		background-color: #ffffff;
		font-family: Tahoma, Arial, Verdana, Helvetica, sans-serif;
		font-size: 11px;
		color: #003399;
		text-align: center;
		}
		table,td {
		color: #444444;
		font-size: 11px;
		}
		a:link, a:visited {
		color: #444444;
		font-size: 11px;
		text-decoration: underline;
		}
		a:hover {
		color: #0094F2;
		text-decoration: underline;
		}
		table.outline_box {
			margin-left: 5px;
			margin-right: 5px;
		}
		td.box_moveable {
			cursor: move;
		}
		td.box_restricted {
		}

  </style>
EOQ;
		echo $style."\n";
	}

?>
