<?php
/*********************************************************************************
 * The contents of this file are subject to the CareBrains Public License
 * Version 1.0 ('License'); You may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://www.carebrains.co.jp/CPL .
 * Software distributed under the License is distributed on an 'AS IS' basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is CareBrains Open Source.
 * The Initial Developer of the Original Code is CareBrains, Inc.
 * Portions created by CareBrains are Copyright (C) 2005-2006 CareBrains, Inc.
 * All Rights Reserved.
 *
 * The Original Code is: CareBrains Inc.
 * The Initial Developer of the Original Code is CareBrains Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Threads/Thread.php');
require_once('MyThreadsDashlet.data.php');

class MyThreadsDashlet extends DashletGeneric {
    function MyThreadsDashlet($id, $def = null) {
        global $current_user, $app_strings, $dashletData, $current_language;
        $this->loadLanguage('MyThreadsDashlet', 'modules/Threads/Dashlets/'); // load the language strings here

		$this->myItemsOnly = true;
        parent::DashletGeneric($id, $def);

        if(empty($def['title'])) $this->title = $this->dashletStrings['LBL_MY_THREADS_TITLE'];
        else $this->title = $def['title'];

        $this->searchFields = $dashletData['MyThreadsDashlet']['searchFields'];
        $this->columns = $dashletData['MyThreadsDashlet']['columns'];
        $this->seedBean = new Thread();
    }
}

?>
