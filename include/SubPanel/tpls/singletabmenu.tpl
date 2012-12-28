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

// $Id: singletabmenu.tpl,v 1.8 2006/08/23 00:01:08 awu Exp $

*}


<script>
	SUGAR.subpanelUtils.currentSubpanelGroup = '{$startSubPanel}';
	
	SUGAR.subpanelUtils.subpanelMoreTab = '{$moreTab}';
	
	SUGAR.subpanelUtils.subpanelMaxSubtabs = '{$maxSubtabs}';
	
	SUGAR.subpanelUtils.subpanelHtml = new Array();
	
	SUGAR.subpanelUtils.loadedGroups = Array();
	SUGAR.subpanelUtils.loadedGroups.push('{$startSubPanel}');
	
	SUGAR.subpanelUtils.subpanelSubTabs = new Array();
	SUGAR.subpanelUtils.subpanelGroups = new Array();
{foreach from=$othertabs item=tab}
{assign var='notFirst' value='0'}
	SUGAR.subpanelUtils.subpanelGroups['{$tab.key}'] = [{foreach from=$tab.tabs item=subtab}{if $notFirst != 0}, {else}{assign var='notFirst' value='1'}{/if}'{$subtab.key}'{/foreach}{foreach from=$otherMoreSubMenu[$tab.key].tabs item=subtab}, '{$subtab.key}'{/foreach}];
{/foreach}

{assign var='notFirst' value='0'}
	SUGAR.subpanelUtils.subpanelTitles = {ldelim}{foreach from=$othertabs.All.tabs item=subtab}{if $notFirst != 0}, {else}{assign var='notFirst' value='1'}{/if}'{$subtab.key}':'{$subtab.label}'{/foreach}{foreach from=$otherMoreSubMenu.All.tabs item=subtab}, '{$subtab.key}':'{$subtab.label}'{/foreach}{rdelim};

	SUGAR.subpanelUtils.tabCookieName = get_module_name() + '_sp_tab';
	
	SUGAR.subpanelUtils.showLinks = {$showLinks};
	
	SUGAR.subpanelUtils.requestUrl = 'index.php?to_pdf=1&module=MySettings&action=LoadTabSubpanels&loadModule={$smarty.request.module}&record={$smarty.request.record}&subpanels=';
</script>



{if !empty($sugartabs)}
<ul class="subpanelTablist" id="groupTabs">
{foreach from=$sugartabs item=tab}
	<li id="{$tab.label}_sp_tab">
		<a class="{$tab.type}" href="javascript:SUGAR.subpanelUtils.loadSubpanelGroup('{$tab.label}');">{$tab.label}</a>
	</li>
{/foreach}
{if !empty($moreMenu)}
	<li>
		<div class='moreHandle' id='MorePanelHandle' style='cursor: hand; cursor: pointer; display: inline; margin-left: 2px; margin-bottom: 2px;' align='absmiddle' onmouseover='SUGAR.subpanelUtils.menu.tbspButtonMouseOver(this.id,"","",0);'>
			<img src="include/images/blank.gif" alt="more" border="0" height="16" width="16" />
		</div>
	</li>
{/if}
</ul>

{* Table closed in SubPanelTiles.php, line 295 *}
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="subpanelTabForm" style="border-top: 0px none; margin-bottom: 4px;">
	<tr>
		<td>
{/if}

{if $showLinks == 'true'}
<table cellpadding="0" cellspacing="0" width='100%' style="margin-top:7px;">
	<tr height="20">
		<td class="subpanelSubTabBar" colspan="100" id="subpanelSubTabs">
			<table border="0" cellpadding="0" cellspacing="0" height="20" width="100%" class="subTabs">
				<tbody>
				<tr>
{foreach from=$subtabs item=tab}
{if !empty($notFirst) && ($notFirst != 0) && ($notFirst != 1)}
					<td width='1'> | </td>
{else}
{assign var='notFirst' value='2'}
{/if}
					<td nowrap="nowrap">
						<a href='#{$tab.key}' class='subTabLink'>{$tab.label}</a>
					</td>
{/foreach}
{if !empty($otherMoreSubMenu[$moreSubMenuName].tabs)}
					<td nowrap="nowrap"> | &nbsp;<span class="subTabMore" id="MoreSub{$moreSubMenuName}PanelHandle" style="margin-left:2px; cursor: pointer; cursor: hand;" align="absmiddle" onmouseover="SUGAR.subpanelUtils.menu.tbspButtonMouseOver(this.id,'','',0);">&gt;&gt;</span></td>
{/if}
					<td width='100%'>&nbsp;</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
{/if}

{if !empty($moreMenu)}
<div id="MorePanelMenu" class="menu">
{foreach from=$moreMenu item=tab}
	<a href="javascript:SUGAR.subpanelUtils.loadSubpanelGroupFromMore('{$tab.label}');" class="menuItem" id="{$tab.label}_sp_mm" parentid="MorePanelMenu" onmouseover="hiliteItem(this,'yes'); closeSubMenus(this);" onmouseout="unhiliteItem(this);">{$tab.label}</a>
{/foreach}
</div>
{/if}

{foreach from=$otherMoreSubMenu item=group}
{if !empty($group.tabs)}
<div id="MoreSub{$group.key}PanelMenu" class="menu">
{foreach from=$group.tabs item=subtab}
	<a href="#{$subtab.key}" class="menuItem" parentid="MoreSub{$group.key}PanelMenu" onmouseover="hiliteItem(this,'yes'); closeSubMenus(this);" onmouseout="unhiliteItem(this);">{$subtab.label}</a>
{/foreach}
</div>
{/if}
{/foreach}


