<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelIcon
 *
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
 */

// $Id: SugarWidgetSubPanelIcon.php,v 1.14 2006/08/29 20:53:08 awu Exp $

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelIcon.php');

class SugarWidgetSubPanelIcon2 extends SugarWidgetSubPanelIcon
{
	function displayList(&$layout_def)
	{
		global $image_path;

		$ret = parent::displayList($layout_def);

		$module = $layout_def['module'];
		$parenticon_img_html = get_image($image_path . 'group'.$module, 'border="0" alt="Group ' . $module . '"');
//		$GLOBALS['log']->debug("Parent Image path :".$parenticon_img_html);
		return $parenticon_img_html;//.$ret;
	}
}
?>
