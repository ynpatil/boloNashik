<?php
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
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Menu.php,v 1.18 2005/02/15 00:18:31 majed Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
$module_menu[]=Array("index.php?module=UserTypeMaster&action=ListView&return_module=UserTypeMaster&return_action=DetailView", $app_strings['LNK_USERTYPE_MASTER'],"Masters");
//$module_menu[]=Array("index.php?module=DMSubCategory&action=ListView&return_module=DMSubCategory&return_action=DetailView", $app_strings['LNK_DMSUBCATEGORY_MASTER'],"DMSubCategory");
//$module_menu[]=Array("index.php?module=DMCategory&action=ListView&return_module=DMCategory&return_action=DetailView", $app_strings['LNK_DMCATEGORY_MASTER'],"DMCategory");
$module_menu[]=Array("index.php?module=BranchMaster&action=ListView&return_module=BranchMaster&return_action=DetailView", $app_strings['LNK_BRANCH_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=ExperienceMaster&action=ListView&return_module=ExperienceMaster&return_action=DetailView", $app_strings['LNK_EXPERIENCE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=CountryMaster&action=ListView&return_module=CountryMaster&return_action=DetailView", $app_strings['LNK_COUNTRY_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=RegionMaster&action=ListView&return_module=RegionMaster&return_action=DetailView", $app_strings['LNK_REGION_MASTER'],"Masters");//ynp
$module_menu[]=Array("index.php?module=StateMaster&action=ListView&return_module=StateMaster&return_action=DetailView", $app_strings['LNK_STATE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=CityMaster&action=ListView&return_module=CityMaster&return_action=DetailView", $app_strings['LNK_CITY_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=LevelMaster&action=ListView&return_module=LevelMaster&return_action=DetailView", $app_strings['LNK_LEVEL_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=LanguageMaster&action=ListView&return_module=LanguageMaster&return_action=DetailView", $app_strings['LNK_LANGUAGE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=SubofficeMaster&action=ListView&return_module=SubofficeMaster&return_action=DetailView", $app_strings['LNK_SUBOFFICE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=OfficetypeMaster&action=ListView&return_module=OfficeTypeMaster&return_action=DetailView", $app_strings['LNK_OFFICETYPE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=IndustryMaster&action=ListView&return_module=IndustryMaster&return_action=DetailView", $app_strings['LNK_INDUSTRY_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=DIOMaster&action=ListView&return_module=DIOMaster&return_action=DetailView", $app_strings['LNK_DIO_MASTER'],"Masters");

$module_menu[]=Array("index.php?module=RelationshipMaster&action=ListView&return_module=RelationshipMaster&return_action=DetailView", $app_strings['LNK_RELATIONSHIP_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=RelationshipTypeMaster&action=ListView&return_module=RelationshipTypeMaster&return_action=DetailView", $app_strings['LNK_RELATIONSHIPTYPE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=CompanyTypeMaster&action=ListView&return_module=CompanyTypeMaster&return_action=DetailView", $app_strings['LNK_COMPANYTYPE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=LinkageMaster&action=ListView&return_module=LinkageMaster&return_action=DetailView", $app_strings['LNK_LINKAGE_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=FunctionMaster&action=ListView&return_module=FunctionMaster&return_action=DetailView", $app_strings['LNK_FUNCTION_MASTER'],"Masters");

$module_menu[]=Array("index.php?module=VerticalsMaster&action=ListView&return_module=VerticalsMaster&return_action=DetailView", $app_strings['LNK_VERTICALS_MASTER'],"Masters");
$module_menu[]=Array("index.php?module=ComplianceMaster&action=ListView&return_module=ComplianceMaster&return_action=DetailView", $app_strings['LNK_COMPLIANCE_MASTER'],"Masters");

?>
