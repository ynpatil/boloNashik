<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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
//om
require_once('include/ytree/Node.php');

//function returns an array of objects of Node type.
function get_node_data($params,$get_array=false) {
    $ret=array();
    $click_level=$params['TREE']['depth'];
    $subcat_id=$params['NODES'][$click_level]['id'];
//	$GLOBALS['log']->debug("Params ".implode(":",$params['NODES']));
//    $level=$params['NODES'][$click_level]['level'];
	$GLOBALS['log']->debug("LEVEL ".$click_level);

    if($click_level != "1"){

	    $cat_id=$params['NODES'][$click_level-1]['id'];
    	$href=true;
	    if (isset($params['TREE']['caller']) and $params['TREE']['caller']=='Brands' ) {
    	    $href=false;
    	}
	//	$nodes=get_product_hier($cat_id,$subcat_id,$href);
		$href_string = "javascript:select_node('prod_hier')";
		$nodes=get_category_nodes($href_string,$subcat_id,$click_level);
		$nodes = $nodes[0]->nodes;

		$GLOBALS['log']->debug("Count :".count($nodes));
		foreach ($nodes as $node) {
			$ret['nodes'][]=$node->get_definition();
		}
		$json = new JSON(JSON_LOOSE_TYPE);
		$str=$json->encode($ret);
    }
	return $str;
}

function get_category_nodes($href_string,$cat_id=NULL,$level=NULL){
    $nodes=array();
    global $mod_strings;
    global $app_list_strings;
	$request =  'http://10.100.109.253/crm/AdCategorySearch';
	$postargs = 'source=crm_master_AdCategory';
	if(isset($cat_id))
	$postargs .= "&prodh=".$cat_id;

	if(isset($level))
	$postargs .= "&level=".$level;

	// Get the curl session object
	$session = curl_init($request);

	// Set the POST options.
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// Do the POST and then close the session
	$response = curl_exec($session);
	curl_close($session);
	/*
	$json = new JSON(JSON_LOOSE_TYPE);
	$data = $json->decode($response);

	$level = "";
	*/
//	$GLOBALS['log']->debug("Data :".$response);
	$response = trim($response);
	if(empty($response))return nodes;

//	$data = simplexml_load_string($response);
	$dom = new DOMDocument('1.0', 'UTF-8');
	if ($dom->loadXML($response) === false) {
	   die('Parsing failed');
	}

	$root_node = $dom->getElementsByTagName("prod_hier");
	$attributes = array();
	$level = "";

//	$dynamic_load = isset($cat_id)?false:true;//for initial level 1 not need to load as data already loaded
	$dynamic_load = true;

	foreach($root_node as $root){

		$level_nodes = $root->getElementsByTagName("level");

		foreach($level_nodes as $level_node){
//			echo "Level ".$level_node->nodeName;

			unset($attributes);
			foreach($level_node->attributes as $attr)
			$attributes[$attr->name] = $attr->value;
			$level = $attributes['step'];
//			$GLOBALS['log']->debug("Level :".$level);
			$parent_nodes = $level_node->getElementsByTagName("P");

			foreach($parent_nodes as $parent_node){
//				echo "Parent ".$v->nodeName;
				unset($attributes);
				foreach($parent_node->attributes as $attr)
				$attributes[$attr->name] = $attr->value;

				$cat_node = new Node($attributes["code"], $attributes["desc"]);
			    $cat_node->set_property("href", $href_string);
	    		$cat_node->set_property("level", $level);
			    $cat_node->expanded = false;
			    $cat_node->dynamic_load = $dynamic_load;
//				$GLOBALS['log']->debug("Parent node :".$attributes['code']);

			    $child_nodes = $parent_node->getElementsByTagName("C");

			    foreach($child_nodes as $child_node){
					unset($attributes);
					foreach($child_node->attributes as $attr)
					$attributes[$attr->name] = $attr->value;

//					$GLOBALS['log']->debug("Child node :".$attributes['code']);
					$child_node = new Node($attributes["code"], $attributes["desc"]);
				    $child_node->set_property("href", $href_string);
		    		$child_node->set_property("level", $level);
				    $child_node->expanded = false;
				    $child_node->dynamic_load = $dynamic_load;
				    $cat_node->add_node($child_node);
			    }
			    if (!empty($cat_node)) $nodes[]=$cat_node;
			}
		}
	}

    return $nodes;
}

function get_product_hier($cat_id,$level,$href=true) {
	$GLOBALS['log']->debug("In get_product_hier :".$cat_id." Level ".$level);
    $nodes=array();
    $href_string = "javascript:select_node('doctree')";
/*
     $query="select * from documents where deleted=0";
    if ($cat_id != 'null') {
        $query.=" and category_id='$cat_id'";
    } else {
        $query.=" and category_id is null";
    }

    if ($subcat_id != 'null') {
        $query.=" and subcategory_id='$subcat_id'";
    } else {
        $query.=" and subcategory_id is null";
    }
    $result=$GLOBALS['db']->query($query);
    $current_cat_id=null;
    while (($row=$GLOBALS['db']->fetchByAssoc($result))!= null) {
*/
        $node = new Node($cat_id, "OM Some name ".$cat_id);
        if ($href) {
            $node->set_property("href", $href_string);
        }
        $node->expanded = false;
        $node->dynamic_load = true;

        $nodes[]=$node;
//    }
    return $nodes;
}
?>
