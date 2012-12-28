<?php
	require_once('include/ytree/Node.php');

	$request =  'http://10.100.109.253/crm/AdCategorySearch';
	$postargs = 'source=crm_master_AdCategory';

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

		echo $response;
?>