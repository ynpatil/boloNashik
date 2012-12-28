<?php

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/TeamsOS/TeamOS.php');
require_once('modules/TeamsOS/Forms.php');
require_once('include/JSON.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = new TeamOS();

if (!is_admin($current_user) && $_REQUEST['record'] != $current_user->id)
    sugar_die("Unauthorized access to administration.");

if (isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
    $focus->name = "";
}
global $theme;
$theme_path = 'themes/' . $theme . '/';
$image_path = $theme_path . 'images/';
include_once($theme_path . 'layout_utils.php');

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'] . ": " . $focus->name, true);
echo "\n</p>\n";

$GLOBALS['log']->info('Team edit view');
$xtpl = new XTemplate('modules/TeamsOS/EditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

////Popup for Level
$popup_request_data = array(
    'call_back_function' => 'set_return',
    'form_name' => 'EditView',
    'field_to_name_array' => array(
        'id' => 'level_id',
        'name' => 'level_name',
    ),
);


$json = getJSONobj();

$xtpl->assign('encoded_level_popup_request_data', $json->encode($popup_request_data));
////END


////Popup for Language
$popup_request_data = array(
    'call_back_function' => 'set_return',
    'form_name' => 'EditView',
    'field_to_name_array' => array(
        'id' => 'language_id',
        'name' => 'language_name',
    ),
);
$xtpl->assign('encoded_language_popup_request_data', $json->encode($popup_request_data));
///END


////Popup for Experience
$popup_request_data = array(
    'call_back_function' => 'set_return',
    'form_name' => 'EditView',
    'field_to_name_array' => array(
        'id' => 'experience_id',
        'name' => 'experience_name',
    ),
);
$xtpl->assign('encoded_experience_popup_request_data', $json->encode($popup_request_data));
///END

if (isset($_REQUEST['error_string']))
    $xtpl->assign('ERROR_STRING', '<span class="error">Error: ' . $_REQUEST['error_string'] . '</span>');
if (isset($_REQUEST['return_module']))
    $xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
if (isset($_REQUEST['return_action']))
    $xtpl->assign('RETURN_ACTION', $_REQUEST['return_action']);
if (isset($_REQUEST['return_id']))
    $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
else {
    $xtpl->assign('RETURN_ACTION', 'ListView');
}

$xtpl->assign('ID', $focus->id);

require_once($theme_path . 'config.php');

require_once('modules/DynamicFields/templates/Files/EditView.php');
if (is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {
    $record = '';
    if (!empty($_REQUEST['record'])) {
        $record = $_REQUEST['record'];
    }
    $xtpl->assign("ADMIN_EDIT", "<a href='index.php?action=index&module=DynamicLayout&from_action=" . $_REQUEST['action'] . "&from_module=" . $_REQUEST['module'] . "&record=" . $record . "'>" . get_image($image_path . "EditLayout", "border='0' alt='Edit Layout' align='bottom'") . "</a>");
}

$xtpl->assign("THEME", $theme);

require_once('include/QuickSearchDefaults.php');

$xtpl->assign('Teams', $focus->get_xtemplate_data());
$xtpl->assign("NAME", $focus->name);

/** uSE fOR vendor Only */
//$xtpl->assign("TYPE", get_select_options_with_id($app_list_strings['type_dom'], $focus->type));
////$region_arr=array('Delhi','Jaipur','Lucknow','Noida/ Greater Noida','Pune','Aurangabad','Muzaffarpur','Chandigarh','Bhopal','Gurgaon','Other City(s) in Punjab','Nagpur','Ahmedabad','Indore','Kurukshetra','Mumbai','Saharanpur','Rudrapur','Patna','Kolkata','Jammu','Allahabad','Ghaziabad','Other City(s) in Orissa','Varanasi / Banaras','Guwahati','Bengaluru/ Bangalore','Chennai','Hamirpur','Valsad -Vapi','Other City(s) in Chhattisgarh','Alwar','Kanpur','Other City(s) in Rajasthan','Mysoru/ Mysore','Other International Location(s)','Nasik','Jamshedpur','Hyderabad/ Secunderabad','Faridabad','Navi Mumbai','Ludhiana','Parwanoo','Kota','Bathinda','Bokaro','Jamnagar','Rohtak','Surat','Panipat','Thane','Chandrapur','Other City(s) in Bihar','Vadodara','Mohali','Japan','Udaipur','Silchar','Other City(s) in Tamil Nadu','Pondicherry/ Puducherry','Coimbatore','Trichy','Salem','UK','Dehradun','Other City(s) in Uttaranchal','Muzaffarnagar','Katni','Sirmaur','Meerut','Ratlam','Bikaner','Visakhapatnam','Midnapur','Vijayawada','Amravati','Ooty','Jeddah','Rajkot','Cuttack','Shillong','Buldhana','Bhubaneshwar','Other City(s) in Andhra Pradesh','Ambala','Vellore','Cuddalore','Yavatmal','Panjim/ Panaji','Other City(s) in Gujarat','Bareilly','Mangalore','Ahmednagar','Other City(s) in Maharashtra','Junagarh','Jalandhar','Guntur','Calicut/ Kozhikode','Singapore','Erode','Thiruvananthapuram','Other City(s) in Saudi Arabia','Haridwar','Netherlands','Gandhinagar','Amritsar','Gulbarga','Ujjain','Solan','Anywhere in India','Kapurthala','Aligarh','Baddi','Jabalpur','Bellary','Davanagere','Bhilai-Durg','Eastern Province','Bilaspur','Other India Location(s)','Dubai','Sonepat','Hissar','Sirsa','Other City(s) in Haryana','Karnal','Sangrur','Yamunanagar','Rewari','Pakistan','Gwalior','Jodhpur','Bharuch','Anand','Ankleshwar','Bhavnagar','GUJARAT','Ajmer','Other City(s) in Uttar Pradesh','Other City(s) in Madhya Pradesh','Bongaigaon','Belgaum','Other City(s) in West Bengal','Ferozepur','Other City(s) in Goa','Kolhapur','Panchkula','Gandhidham','Dadra & Nagar Haveli - Silvassa','Hoshiarpur','Moga','Dharamshala','Patiala','Rourkela','Ranchi','Other City(s) in Jammu & Kashmir','Satara','Una','Sibsagar','Jorhat','Other Gulf/ Middle East Location(s)','Kuwait City','Durgapur','Burdwan','Tuticorin','Tirunelveli','Thanjavur','Abu Dhabi','Madurai','Cochin/ Kochi/ Ernakulam','Kurnool','Gorakhpur','Agra','Muscat','Kannur','Kottayam','Other City(s) in Kerala','Sangli','Rajahmundry','Philippines','Mansa','Kaithal','Bhiwani','Solapur','Bidar','Agartala','Al Madina Al Munawarah','Aizawal','France','Doha','Warangal','Lakshdweep','Kollam','Anantapur','Nellore','Tirupati','Thailand','Other City(s) in Kuwait','Mehsana','Qatar','Bhuj','Raipur','Sagar','Mathura','Kangra','Guntakal','Malaysia','Other City(s) in Karnataka','Nanded','Jalgaon','Hubli','Kharagpur','Other City(s) in Sikkim','South Africa','Thrissur','Dibrugarh','Kakinada','Dharwad','Spain','Srinagar','Faizabad','Latur','Moradabad','Fatehabad','Bhagalpur','Daman & Diu','Pulwama','Other US Location(s)','Nagercoil','Vasco Da Gama','Sweden','Dimapur','Kenya','Others','Hosur','Manama','Sharjah','Chicago','Dhanbad','Sri Lanka','Asansol','Siliguri','Malda','Fatehgarh Sahib','Other City(s) in Bahrain','Italy','Roorkee','Other City(s) in Himachal Pradesh','Jhajjar','Other City(s) in Meghalaya','India','Haldwani','Morocco','Palakkad','Ras Al Khaimah','Zimbabwe','Mandi','Canada','Kullu','ASSAM','Baramulla','Tinsukia','Kanniyakumari','Australia','GOA','Shimla','Nainital','Nizamabad','Other City(s) in Assam','Kinnaur','Other City(s) in Qatar','Rajouri','Algeria','Other City(s) in Jharkhand','Haldia','Baltimore','Imphal','MADHYA PRADESH','Paradeep','Akola','Satna','Nepal','Other City(s) in Oman','Other City(s) in Texas','Al Ain','MAHARASHTRA','Other City(s) in Manipur','Gangtok','Maldives','Other City(s) in New York','Porbandar','Dhule','China','Gondia','Itanagar','Pathankot','Bangladesh','Other City(s) in UAE','Mahendergarh','Kolar','Mahabaleshwar');
//$xtpl->assign("REGION", get_select_options_with_id($region_arr, $focus->region));
//for($lev=1;$lev<=4;$lev++){
//    $lev_arr[$lev] = $lev;
//}
//$xtpl->assign("LEVEL", get_select_options_with_id($lev_arr, $focus->level));
//
$xtpl->assign("LEVEL_NAME", $focus->level_name); //ynp
$xtpl->assign("LEVEL_ID", $focus->level_id); 
$xtpl->assign("EXPERIENCE_NAME", $focus->experience_name);
$xtpl->assign("EXPERIENCE_ID", $focus->experience_id);
$xtpl->assign("LANGUAGE_NAME", $focus->language_name);
$xtpl->assign("LANGUAGE_ID", $focus->language_id);
$xtpl->assign("EMAIL", $focus->email);

if ($focus->private == 1) {
    $xtpl->assign('PRIVATE', 'CHECKED');
}
$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
?>
