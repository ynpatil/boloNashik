<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

require_once('modules/Users/User.php');
$current_module_strings = return_module_language($current_language, 'Users');


$query = "SELECT id,name FROM suboffice_mast WHERE name!='' AND deleted=0 ORDER BY name";
$result = $GLOBALS['db']->query($query, false, "Error retrieving user ID: ");
while($row = $GLOBALS['db']->fetchByAssoc($result)) {
    $SubOfficeArray[$row['id']]=$row['name'];
}

?>
<body>
    <form name="EditView" method="post" action="index.php">
        <input type="hidden" name="module" value="Users">
        <input type="hidden" name="action">
        <input type="hidden" name="return_module" value="Users">
        <input type="hidden" name="return_action" value="Location">
        <input type="hidden" id="latitude" name="latitude" value="">
        <input type="hidden" id="longitude" name="longitude" value="">
        <table border="0" align="center" width="25%">
            <tr>
                <td colspan="2" align="center" class="required"><?php echo $_SESSION['sub_office massages']; ?> </td>
            </tr>
            <tr>
                <td><?php echo $current_module_strings['LBL_LIST_LATITUDE'] ?> </td>
                <td><span id="lat"></span></td>
            </tr>                                                   
            <tr>
                <td><?php echo $current_module_strings['LBL_LIST_LONGITUDE'] ?> </td>
                <td><span id="lon"></span></td>
            </tr>
            <tr>
                <td><?php echo $current_module_strings['LBL_LIST_FORM_TITLE'] ?> </td>
                <td><span > <select name="sub_office_id" id="sub_office_id"><option value="">--None--</option><?php echo get_select_options_with_id($SubOfficeArray, $SubOfficeName); ?></select></span></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input onclick="this.form.action.value='SaveLocation';"  tabindex='1'  title="<?php echo $current_module_strings['LBL_SAVE_BUTTON_TITLE'] ?>" accessKey="<?php echo $current_module_strings['LBL_SAVE_BUTTON_TITLE'] ?>" class="button" onclick="this.form.action.value='SaveNewUser';" type="submit" id="submit_button" name="Submit" value="<?php echo $current_module_strings['LBL_SAVE_BUTTON_TITLE']?>"></td>
            </tr>
        </table>
    </form>
</body>
</html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <!-- jQuery Min -->
    <script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>

    <!-- Google Maps -->
    <script type="text/javascript" charset="utf-8" src="http://maps.google.com/maps/api/js?sensor=true"></script>

    <script type="text/javascript">

        var geo = navigator.geolocation;
        if( geo ){
            geo.getCurrentPosition( showLocation, mapError, { timeout: 5000, enableHighAccuracy: true } );
        }


        function showLocation( position ){

            //  alert("lat"+ position.coords.latitude);
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            document.getElementById('lat').innerHTML=lat;
            document.getElementById('latitude').value=lat;


            document.getElementById('lon').innerHTML=lng;
            document.getElementById('longitude').value=lng;
            //return 'Latitude:'+lat+", Longitude:"+lng;
            //getLocation(lat,lng);

        }

        function mapError( e ){
            var error;
            switch( e.code ){
                case 1:
                    error = "Permission Denied.\n\n Please turn on Geo Location by going to Settings > Location Services > Safari";
                    break;
                case 2:
                    error = "Network or Satellites Down";
                    break;
                case 3:
                    error = "GeoLocation timed out";
                    break;
                case 0:
                    error = "Other Error";
                    break;
            }
            $("#map").html( error );
        }
        showLocation();
    </script>