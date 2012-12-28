{*

/**
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

// $Id: date.tpl,v 1.4 2006/08/23 00:09:59 awu Exp $

*}


{include file="modules/DynamicFields/templates/Fields/Forms/coreTop.tpl"}
<tr><td nowrap="nowrap">{$MOD.COLUMN_TITLE_DEFAULT_VALUE}:</td><td>
{html_options name='default_value' output=$default_values values=$default_values selected=$default_value}
</td>
    	</tr>
<tr><td nowrap="nowrap">{$MOD.COLUMN_TITLE_MASS_UPDATE}:</td><td><input type="checkbox" name="mass_update" value="1" {if !empty($cf->mass_update)}checked{/if}/></td></tr>

{include file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}
