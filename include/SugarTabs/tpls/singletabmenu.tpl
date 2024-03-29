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

// $Id: singletabmenu.tpl,v 1.5 2006/08/23 00:01:40 awu Exp $

*}


<script>
	SUGAR.themes.currentSubpanelGroupLayoutChanged = false;
	
	SUGAR.themes.currentSubpanelGroup = '{$startSubPanel}';
	
	SUGAR.themes.subpanelMoreTab = '{$moreTab}';
	
	SUGAR.themes.subpanelHtml = new Array();
	
	SUGAR.themes.subpanelSubTabs = new Array();
	SUGAR.themes.subpanelGroups = new Array();
{foreach from=$othertabs item=tab}
{assign var='notFirst' value='0'}
	SUGAR.themes.subpanelGroups['{$tab.key}'] = [{foreach from=$tab.tabs item=subtab}{if $notFirst != 0}, {else}{assign var='notFirst' value='1'}{/if}'{$subtab.key}'{/foreach}{foreach from=$otherMoreSubMenu[$tab.key].tabs item=subtab}, '{$subtab.key}'{/foreach}];
{assign var='notFirst' value='0'}
	SUGAR.themes.subpanelSubTabs['{$tab.key}'] = '<table border="0" cellpadding="0" cellspacing="0" height="20" width="100%" class="subTabs"><tr>{foreach from=$tab.tabs item=subtab}{if $notFirst != 0}<td width="1"> | </td>{else}{assign var='notFirst' value='1'}{/if}<td nowrap="nowrap"><a href="#{$subtab.key}" class="subTabLink">{$subtab.label}</a></td>{/foreach}{if !empty($otherMoreSubMenu[$tab.key].tabs) }<td nowrap="nowrap"> | &nbsp;<span class="subTabMore" id="MoreSub{$tab.key}PanelHandle" style="margin-left:2px; cursor: pointer; cursor: hand;" align="absmiddle" onmouseover="tbButtonMouseOver(this.id,\'\',\'\',0);">&gt;&gt;</span></td>{/if}<td width="100%">&nbsp;</td></tr></table>';
{/foreach}

	SUGAR.themes.loadSubpanelFromMore = function(subpanel){ldelim}
		//console.log('lsfm:'+subpanel);
		SUGAR.themes.updateSubpanelMoreTab(subpanel);
		SUGAR.themes.loadSubpanel(subpanel);
	{rdelim};
	
	SUGAR.themes.updateSubpanelMoreTab = function(subpanel){ldelim}
		//console.log('usmt:'+subpanel+' | '+SUGAR.themes.subpanelMoreTab);
		
		// Update Tab
		var moreTab = document.getElementById(SUGAR.themes.subpanelMoreTab + '_sp_tab');
		moreTab.id = subpanel + '_sp_tab';
		moreTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[0].getElementsByTagName('img')[0].alt = subpanel;
		moreTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[1].getElementsByTagName('a')[0].innerHTML = subpanel;
		moreTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[1].getElementsByTagName('a')[0].href = "javascript:SUGAR.themes.loadSubpanel('"+subpanel+"');";
		moreTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[2].getElementsByTagName('img')[0].alt = subpanel;
		
		// Update Menu
		var menuLink = document.getElementById(subpanel+'_sp_mm');
		menuLink.id = SUGAR.themes.subpanelMoreTab+'_sp_mm';
		menuLink.href = "javascript:SUGAR.themes.loadSubpanelFromMore('"+SUGAR.themes.subpanelMoreTab+"');";
		menuLink.innerHTML = SUGAR.themes.subpanelMoreTab;
		
		
		SUGAR.themes.subpanelMoreTab = subpanel;
	{rdelim};
	
	SUGAR.themes.loadSubpanel = function(subpanel){ldelim}
		//console.log('lsp:'+subpanel+' | '+SUGAR.themes.currentSubpanelGroup);
		if(SUGAR.themes.currentSubpanelGroupLayoutChanged && SUGAR.themes.subpanelHtml[SUGAR.themes.currentSubpanelGroup]){ldelim}
			SUGAR.themes.subpanelHtml[SUGAR.themes.currentSubpanelGroup] = document.getElementById('subpanel_list').innerHTML;
			SUGAR.themes.currentSubpanelGroupLayoutChanged = false;
		{rdelim}
		if(SUGAR.themes.subpanelHtml[subpanel]){ldelim}
			document.getElementById('subpanel_list').innerHTML = SUGAR.themes.subpanelHtml[subpanel];
			SUGAR.themes.updateSubpanelTabs(subpanel);
		{rdelim}else{ldelim}
			if(!SUGAR.themes.subpanelHtml[SUGAR.themes.currentSubpanelGroup]){ldelim}
				SUGAR.themes.subpanelHtml[SUGAR.themes.currentSubpanelGroup] = document.getElementById('subpanel_list').innerHTML;
			{rdelim}
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
			SUGAR.util.retrieveAndFill('index.php?to_pdf=1&module=MySettings&action=LoadTabSubpanels&loadModule={$smarty.request.module}&record={$smarty.request.record}&subpanel='+subpanel,'subpanel_list', null, SUGAR.themes.updateSubpanelTabs, subpanel);
		{rdelim}
		SUGAR.themes.setGroupCookie(subpanel);
	{rdelim};
	
	SUGAR.themes.updateSubpanelTabs = function(subpanel){ldelim}
		//console.log('ust:'+subpanel);
		
		if(!SUGAR.themes.subpanelHtml[subpanel]){ldelim}
				SUGAR.themes.subpanelHtml[subpanel] = document.getElementById('subpanel_list').innerHTML;
		{rdelim}
		
		document.getElementById('subpanelSubTabs').innerHTML = SUGAR.themes.subpanelSubTabs[subpanel];
		
		oldTab = document.getElementById(SUGAR.themes.currentSubpanelGroup+'_sp_tab');
		if(oldTab){ldelim}
			oldTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[0].getElementsByTagName('img')[0].src = "themes/Sugar/images/otherTab_left.gif";
			oldTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[1].className = "otherTab";
			oldTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[1].getElementsByTagName('a')[0].className = "otherTabLink";
			oldTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[2].className = "otherTabRight";
		{rdelim}
		
		mainTab = document.getElementById(subpanel+'_sp_tab');
		mainTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[0].getElementsByTagName('img')[0].src = "themes/Sugar/images/currentTab_left.gif";
		mainTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[1].className = "currentTab";
		mainTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[1].getElementsByTagName('a')[0].className = "currentTabLink";
		mainTab.getElementsByTagName('tr')[0].getElementsByTagName('td')[2].className = "currentTabRight";
		
		SUGAR.themes.updateSubpanelEventHandlers(subpanel);
		
		SUGAR.themes.currentSubpanelGroup = subpanel;
		ajaxStatus.hideStatus();
	{rdelim};
	
	SUGAR.themes.updateSubpanelEventHandlers = function(subpanel){ldelim}
		if(SubpanelInitTabNames){ldelim}
			SubpanelInitTabNames(SUGAR.themes.subpanelGroups[subpanel]);
		{rdelim}
	{rdelim};
	
	SUGAR.themes.tabCookieName = get_module_name() + '_sp_tab';
	SUGAR.themes.setGroupCookie = function(subpanel){ldelim}
		Set_Cookie(SUGAR.themes.tabCookieName, subpanel, 3000, false, false,false);
	{rdelim};
</script>


<table cellpadding="0" cellspacing="0" width='100%' style="margin-top:7px;">
	{if !empty($sugartabs)}
	<tr>
		{foreach from=$sugartabs item=tab}
		<td width='1%'>
		    <table border="0" cellpadding="0" cellspacing="0" width="1" id="{$tab.label}_sp_tab">
				<tbody>
				<tr height="25">
					<td width='1'><img src="themes/Sugar/images/{$tab.type}Tab_left.gif" alt="{$tab.label}" border="0" height="25" width="5"></td>
					<td width='1' class="{$tab.type}Tab" nowrap="nowrap"><a class="{$tab.type}TabLink" href="javascript:SUGAR.themes.loadSubpanel('{$tab.label}');">{$tab.label}</a></td>
					<td width='1' class="{$tab.type}TabRight"><img src="include/images/blank.gif" alt="{$tab.label}" border="0" height="1" width="2"></td>
					<td width='1' style="background-image: url(themes/Sugar/images/emptyTabSpace.gif);" valign="bottom"></td>
				</tr>
				</tbody>
			</table>
		</td>
		{/foreach}
		<td width='1%'>
		{if !empty($moreMenu)}
			<img src='themes/Sugar/images/more.gif' alt='' align='absmiddle' id='MorePanelHandle' style=' margin-left:2px; cursor: pointer; cursor: hand;' align='absmiddle' onmouseover='tbButtonMouseOver(this.id,"","",0);'>
		{/if}
		</td>
		<td width='100%'>&nbsp;</td>
	</tr>
	{/if}
	<tr height="20">
		<td class="subTabBar" colspan="100" id="subpanelSubTabs">
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
					<td nowrap="nowrap"> | &nbsp;<span class="subTabMore" id="MoreSub{$moreSubMenuName}PanelHandle" style="margin-left:2px; cursor: pointer; cursor: hand;" align="absmiddle" onmouseover="tbButtonMouseOver(this.id,'','',0);">&gt;&gt;</span></td>
				{/if}
					<td width='100%'>&nbsp;</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>

{if !empty($moreMenu)}
<div id="MorePanelMenu" class="menu">
{foreach from=$moreMenu item=tab}
	<a href="javascript:SUGAR.themes.loadSubpanelFromMore('{$tab.label}');" class="menuItem" id="{$tab.label}_sp_mm" parentid="MorePanelMenu" onmouseover="hiliteItem(this,'yes'); closeSubMenus(this);" onmouseout="unhiliteItem(this);">{$tab.label}</a>
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


