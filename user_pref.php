<?php
//SADEK'S USER PREFERENCES EDITING SCRIPT

@include_once('style.php');
require_once("config.php");
global $sugar_config;

$db_type = $sugar_config['dbconfig']['db_type'];

$link = connect_to_db($db_type);

echo "Please enter a user name in the system in order to pull up a list of preferences to edit.<BR> \n";
echo "<form method=\"post\" action=\"user_pref.php\">\n";
echo "<input type=\"text\" size=\"60\" name=\"user_prefs\" value=\"".(isset($_POST['user_prefs']) ? $_POST['user_prefs'] : "")."\"><BR>\n";
echo "<input type=\"submit\" name=\"submit\" value=\"Submit\">";
echo "</form>\n";

if(isset($_POST['user_prefs']))
{
  $user_prefs = "";
  $query = "select id from users where user_name='".$_POST['user_prefs']."'";
  $result = executeQuery($query, $db_type, $link);
  $row = null;
  getRow($result, $db_type, $row);

  $query = "select contents, id, category from user_preferences where assigned_user_id='".$row[0]."'";
  $result = executeQuery($query, $db_type, $link);
  $row = null;
  getRow($result, $db_type, $row);

  echo "<BR>\nIf you would like to change any of the values in the user preferences, please modify the values and click the generate button.<BR>\n";

  $counter = 1;
  while($row){
    echo "<form method=\"post\" action=\"user_pref.php\" name=\"generateuserprefs\">\n";
    echo "<center>";
    echo "<h3>Preference $counter - ".$row[2]."</h3>\n";
    echo "<input type=\"submit\" name=\"user_prefs_generate\" value=\"Generate\">";
    echo "<table class=std border=1>\n";
    $user_prefs_array = @decodeUserPrefs($row[0]);
    if(empty($user_prefs_array))
      echo "<BR><b>Could not decode the user preferences for ".$row[2].".</b><BR>";
    printFormFromArray($user_prefs_array, 0);
    echo "</table>";
    echo "<input type=\"hidden\" name=\"user_prefs_id\" value=\"".$row[1]."\">";
    echo "<input type=\"submit\" name=\"user_prefs_generate\" value=\"Generate\">";
    echo "</center>";
    echo "</form>";
    getRow($result, $db_type, $row);
    $counter++;
  }
}
else if(isset($_POST['user_prefs_generate']))
{
  unset($_POST['user_prefs_generate']);
  $user_prefs_id = $_POST['user_prefs_id'];
  unset($_POST['user_prefs_id']);
  $new_user_prefs = encodeUserPrefs($_POST);

  echo "<BR>\nTo apply the new user preferences:<BR>\n";
  echo "1) make sure the user who's preferences are being updated is logged out<BR>\n";
  echo "2) run the following mysql statement (making sure you change desired_user_name to the user name who this applies to):<BR>\n";
  echo "<textarea rows=\"10\" cols=\"75\" WRAP=HARD>update user_preferences set \ncontents=\"$new_user_prefs\"\nwhere id=\"$user_prefs_id\";</textarea>\n<BR>\n";
//  echo "<i>update users set user_preferences=\"$new_user_prefs\" where user_name=\"<u>desired_user_name</u>\";</i><BR>\n";
}
else
{
  die();
}

function printFormFromArray($array, $num_tabs, $array_name="")
{
  $spacing_str = "";
  for($i = 0; $i < $num_tabs; $i++)
    $spacing_str .= "    ";

  foreach($array as $key => $value)
  {
    if(is_array($value))
    {
      echo "<tr><td>".$spacing_str."Array ($key)</td><td> </td></tr>\n";
      echo "<tr><td>$spacing_str{</td><td> </td></tr>\n";
      printFormFromArray($value, $num_tabs + 1, $key);
      echo "<tr><td>".$spacing_str."}</td><td> </td></tr>\n";
    }
    else
    {
      echo "<tr><td>".$spacing_str."$key</td><td><input class=textinput name=\"".($array_name == "" ? "" : "_arraybegin_{$array_name}_arrayend_")."$key\" type=\"text\" size=\"40\" value=\"$value\"> </td></tr>\n";
    }
  }
}

function decodeUserPrefs($array)
{
  return unserialize(base64_decode($array));
}

function encodeUserPrefs($array)
{
  $user_prefs_array = convertPostToUserArray($array);
  return base64_encode(serialize($user_prefs_array));
}

function convertPostToUserArray($array)
{
  $return_array = array();

  foreach($array as $key => $value)
  {
    if(strcmp(substr($key, 0, 12), "_arraybegin_") == 0)
    {
      $arr = substr($key, 12, stripos($key, "_arrayend_") - 12);
      $index_name = substr($key, stripos($key, "_arrayend_") + 10, strlen($key) - 1);

      $return_array[$arr][$index_name] = $value;
    }
    else
    {
      $return_array[$key] = $value;
    }
  }

  return $return_array;
}

function connect_to_db($db_type)
{
  global $sugar_config;

  switch($db_type){
    case "mysql":
      $link =
      mysql_connect(  $sugar_config['dbconfig']['db_host_name'],
                      $sugar_config['dbconfig']['db_user_name'],
                      $sugar_config['dbconfig']['db_password'] );
      if(!$link){
        echo "Could not connect to database. Make sure the config.php has the correct values set."; die();
      }
      mysql_select_db($sugar_config['dbconfig']['db_name'], $link);
      return $link;
      break;

    case "oci8":
      $link = ora_plogon($sugar_config['dbconfig']['db_user_name'],
                        $sugar_config['dbconfig']['db_password']);
      if(!$link){
        echo "Could not connect to database. Make sure the config.php has the correct values set."; die();
      }
      return $link;
      break;
    default:
      echo "Unexpected database type";
      die();
      break;
  }

}

function executeQuery($query, $db_type, $link)
{

  switch($db_type){
    case "mysql":
      return mysql_query($query);
      break;
    case "oci8":
      $open = ora_open($link);
      $parse = ora_parse($open, $query) or die (ora_error());
      $exec = ora_exec($open) or die(ora_error());
      return $open;
      break;
    default:
      echo "Unexpected database type";
      die();
      break;
  }

}

function getRow($result, $db_type, &$row)
{

  switch($db_type){
    case "mysql":
      $row = mysql_fetch_row($result);
      break;
    case "oci8":
      ora_fetch_into($result, $row);
      break;
    default:
      echo "Unexpected database type";
      die();
      break;
  }

}

?>
