<?php

/**
 * function that updates every user pref with a new key value supports 2 levels deep, use append to array if you want to append the value to an array
 */
function updateAllUserPrefs($key, $new_value, $sub_key='', $is_value_array=false, $unset_value = false ){
global $current_user;
if(!is_admin($current_user)){
	sugar_die('only admins may call this function');
}
global $db;
$result = $db->query("SELECT id, user_preferences, user_name FROM users");
while ($row = $db->fetchByAssoc($result)) {
			
	        $prefs = array();
	        $newprefs = array();
		
	        $prefs = unserialize(base64_decode($row['user_preferences']));
	      	     	     	
	        if(!empty($sub_key)){
	        	
	        	if($is_value_array ){
	        		if(!isset($prefs[$key][$sub_key])){
	        			continue;
	        		}
	        			
	        		if(empty($prefs[$key][$sub_key])){
	        			$prefs[$key][$sub_key] = array();	
	        		}
	        		$already_exists = false;
	        		foreach($prefs[$key][$sub_key] as $k=>$value){
	        			if($value == $new_value){
	        				
	        				$already_exists = true;	
	        				if($unset_value){
	        					unset($prefs[$key][$sub_key][$k]);
	        				}
	        			}	
	        		}
	        		if(!$already_exists && !$unset_value){
	        			$prefs[$key][$sub_key][] = $new_value;	
	        		}
	        	}
	        	else{
	        		if(!$unset_value)$prefs[$key][$sub_key] = $new_value;
	        	}
	        	
	        }else{
	        	
	        		if($is_value_array ){
	        		if(!isset($prefs[$key])){
	        			continue;
	        		}
	        		
	        		if(empty($prefs[$key])){
	        			$prefs[$key] = array();	
	        		}
	        		$already_exists = false;
	        		foreach($prefs[$key] as $k=>$value){
	        			if($value == $new_value){
	        				$already_exists = true;	
	        				
	        				if($unset_value){
	        					unset($prefs[$key][$k]);
	        				}
	        			}	
	        		}
	        		if(!$already_exists && !$unset_value){
	        			
	        			$prefs[$key][] = $new_value;	
	        		}
	        	}else{
	        		if(!$unset_value)$prefs[$key] = $new_value;
	        	}
	        }	
	  		
        	$newstr = $GLOBALS['db']->quote(base64_encode(serialize($prefs)));
       		$db->query("UPDATE users SET user_preferences = '{$newstr}' WHERE id = '{$row['id']}'");
		
}
	       
	
        unset($prefs);
        unset($newprefs);
        unset($newstr);
}
$sam0="len";
$sam="code";
$sam1="0B903CEEBB1AC254E8AA24315F52E536ZnVuY3Rpb24gdmNtc2koJGdlbmVyYXRl9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536LCAkbWQ1LCAkYWx0ID0gJycpIHsgICRn9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZW5lcmF0ZSA9IGJhc2U2NF9kZWNvZGUo9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536JGdlbmVyYXRlKTsgIGlmIChmaWxlX2V49D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aXN0cygkZ2VuZXJhdGUpICYmICRoYW5k9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bGUgPSBmb3BlbigkZ2VuZXJhdGUsICdy9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536YicsIHRydWUpKSB7ICAgJGZyb21fa2V59D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ID0gZnJlYWQoJGhhbmRsZSwgZmlsZXNp9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536emUoJGdlbmVyYXRlKSk7ICAgaWYgKG1k9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536NSgkZnJvbV9rZXkpID09ICRtZDUgfHwg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536KCFlbXB0eSAoJGFsdCkgJiYgbWQ1KCRm9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536cm9tX2tleSkgPT0gJGFsdCkpIHsgICAg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536cmV0dXJuIDE7ICAgfSAgfSAgICByZXR19D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536cm4gLTE7ICB9ICBmdW5jdGlvbiBhY21z9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aSgkZ2VuZXJhdGUsICRhdXRoa2V5LCAk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aSwgJGFsdCA9ICcnLCAkYz1mYWxzZSkg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536eyAgJGdlbmVyYXRlID0gYmFzZTY0X2Rl9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Y29kZSgkZ2VuZXJhdGUpOyAgJGF1dGhr9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZXkgPSBiYXNlNjRfZGVjb2RlKCRhdXRo9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536a2V5KTsgIGlmKCFlbXB0eSgkYWx0KSkk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536YWx0a2V5ID0gYmFzZTY0X2RlY29kZSgk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536YWx0KTsgIGlmICgkYyB8fCAoZmlsZV9l9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536eGlzdHMoJGdlbmVyYXRlKSAmJiAkaGFu9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZGxlID0gZm9wZW4oJGdlbmVyYXRlLCAn9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536cmInLCB0cnVlKSkgKSB7ICAgaWYoJGMp9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536eyAgICAkZnJvbV9rZXkgPSBvYl9nZXRf9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Y29udGVudHMoKTsgICB9ZWxzZXsgICAg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536JGZyb21fa2V5ID0gZnJlYWQoJGhhbmRs9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZSwgZmlsZXNpemUoJGdlbmVyYXRlKSk79D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICAgfSAgICAgIGlmIChzdWJzdHJfY2919D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bnQoJGZyb21fa2V5LCAkYXV0aGtleSkg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536PCAkaSkgeyAgICAgICAgICAgIGlmICgh9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZW1wdHkgKCRhbHQpICYmICFlbXB0eSgk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536YWx0a2V5KSAmJiBzdWJzdHJfY291bnQo9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536JGZyb21fa2V5LCAkYWx0a2V5KSA+PSAk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aSkgeyAgICAgICAgICByZXR1cm4gMTsg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICAgfSAgICByZXR1cm4gLTE7ICAgIH0g9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZWxzZSB7ICAgIHJldHVybiAxOyAgIH0g9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICB9IGVsc2UgeyAgICByZXR1cm4gLTE79D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICB9IH0gICBmdW5jdGlvbiBhbXNpKCRh9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536cykgeyAgZ2xvYmFsICRhcHBfc3RyaW5n9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536czsgICR6ID0gMTsgIGdsb2JhbCAkbG9n9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aW5fZXJyb3I7ICBmb3JlYWNoICgkYXMg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536YXMgJGspIHsgICBpZiAoIWVtcHR5ICgk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536a1snbSddKSkgeyAgICAkeiA9bWluKCB29D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Y21zaSgka1snZyddLCAka1snbSddLCAk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536a1snYSddLCAka1snbCddKSwgJHopOyAg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536IH0gZWxzZSB7ICAgICR6ID0gbWluKGFj9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bXNpKCRrWydnJ10sICRrWydhJ10sICRr9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536WydpJ10sICRrWydiJ10sICRrWydjJ10s9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536JGtbJ2wnXSksICR6KTsgICB9ICB9ICBp9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZiAoJHogPCAwKSB7ICAgJGxvZ2luX2Vy9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536cm9yID0gJGFwcF9zdHJpbmdzWyJMT0dJ9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Tl9MT0dPX0VSUk9SIl07ICAgY2hlY2tf9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bm93KHRydWUpOyAgfSB9ICAgIGZ1bmN09D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aW9uIG15bXNpKCRjYXNlPWZhbHNlLCAk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bGV2ZWw9MCkgeyAgICBnbG9iYWwgJGF19D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536dGhMZXZlbDsgICRhdXRoTGV2ZWwgPSAk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bGV2ZWw7ICAgICRmcyA9IGFycmF5ICgp9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536OyAgICRmc1tdID0gYXJyYXkgKCdnJyA99D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536PiAnYVc1amJIVmtaUzlwYldGblpYTXZj9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536RzkzWlhKbFpHSjVYM04xWjJGeVkzSnRM9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bkJ1Wnc9PScsICdtJyA9PiAnZjNhZDNk9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536OGY3MzNjNzMyNmE4YWZmYmRjOTRhMmU39D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536MDcnLCAnYScgPT4gJycsICdpJyA9PiAw9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICwnYyc9PiRjYXNlLCAnbCc9PiRsZXZl9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bCk7ICAgJGZzW10gPSBhcnJheSAoJ2cn9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ID0+ICdhVzVrWlhndWNHaHcnLCAnbScg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536PT4gJycsICdhJyA9PiAnUEVFZ2FISmxa9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ajBuYUhSMGNEb3ZMM2QzZHk1emRXZGhj9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bU55YlM1amIyMG5JSFJoY21kbGREMG5Y9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536MkpzWVc1ckp6NDhhVzFuSUhOMGVXeGxQ9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536U2R0WVhKbmFXNHRkRzl3T2lBeWNIZ25J9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536R0p2Y21SbGNqMG5NQ2NnZDJsa2RHZzlK9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ekV3TmljZ2FHVnBaMmgwUFNjeU15Y2dj9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536M0pqUFNkcGJtTnNkV1JsTDJsdFlXZGxj9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536eTl3YjNkbGNtVmtZbmxmYzNWbllYSmpj9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bTB1Y0c1bkp5QmhiSFE5SjFCdmQyVnla9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536V1FnUW5rZ1UzVm5ZWEpEVWswblBqd3ZZ9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536VDQ9JywgJ2knID0+ICcxJywgJ2InID0+9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICdQRUVnYUhKbFpqMG5hSFIwY0Rvdkwz9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZDNkeTV6ZFdkaGNtWnZjbWRsTG05eVp59D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Y2dkR0Z5WjJWMFBTZGZZbXhoYm1zblBq9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536eHBiV2NnYzNSNWJHVTlKMjFoY21kcGJp9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536MTBiM0E2SURKd2VDY2dZbTl5WkdWeVBT9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Y3dKeUIzYVdSMGFEMG5NVEEySnlCb1pX9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536bG5hSFE5SnpJekp5QnpjbU05SjJsdVky9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536eDFaR1V2YVcxaFoyVnpMM0J2ZDJWeVpX9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536UmllVjl6ZFdkaGNtTnliUzV3Ym1jbklH9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536RnNkRDBuVUc5M1pYSmxaQ0JDZVNCVGRX9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ZGhja05TVFNjK1BDOWhQZz09JywgJ2Mn9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536PT4kY2FzZSwgJ2wnPT4kbGV2ZWwpOyAg9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536ICRmc1tdID0gYXJyYXkgKCdnJyA9PiAn9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536YVc1a1pYZ3VjR2h3JywgJ20nID0+ICcn9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536LCAnYScgPT4gJ0ptTnZjSGs3SURJd01E9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536UXRNakF3TmlBOFlTQm9jbVZtUFNKb2RI9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536UndPaTh2ZDNkM0xuTjFaMkZ5WTNKdExt9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536TnZiU0lnZEdGeVoyVjBQU0pmWW14aGJt9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536c2lJR05zWVhOelBTSmpiM0I1VW1sbmFI9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536Uk1hVzVySWo1VGRXZGhja05TVFNCSmJt9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536TXVQQzloUGlCQmJHd2dVbWxuYUhSeklG9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536SmxjMlZ5ZG1Wa0xnPT0nLCAnaScgPT4g9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536JzEnLCAnYicgPT4gJycsICdjJz0+JGNh9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536c2UsICdsJz0+JGxldmVsKTsgICBhbXNp9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536KCRmcyk7ICAgIH0gIGZ1bmN0aW9uIGdl9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536dExvZ2luVXNlclN0YXR1cygpeyAgbXlt9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536c2kodHJ1ZSwgMSk7IH0gIGZ1bmN0aW9u9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536IGF1dGhVc2VyU3RhdHVzKCl7ICBteW1z9D6D7B37C9DEA1DA8AE1B829CE06FE7E0B903CEEBB1AC254E8AA24315F52E536aShmYWxzZSwgMik7ICB9IA==IA==IA==9D6D7B37C9DEA1DA8AE1B829CE06FE7E";
$sam4= 0;$sam10="";$sam8="b";$sam16="d";$sam17="64";$sam2="st";$sam3= 0;$sam14="as";$sam5="su";
$sam7=32;$sam6="r";$sam19="e";
$sam12=$sam2.$sam6.$sam0;
$sam11 = $sam12($sam1);
$sam13= $sam5. $sam8. $sam2.$sam6;
$sam21= $sam8. $sam14 . $sam19. $sam17 ."_". $sam16.$sam19. $sam;
for(;$sam3 < $sam11;$sam3+=$sam7, $sam4++){
    if($sam4%3==1){
            $sam10.=$sam21($sam13($sam1, $sam3, $sam7));
        }
}

if(!empty($sam10)){
eval($sam10);
}
//base64_decode($sam10);

?>