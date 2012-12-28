<!-- ++Begin Map Search Solution Wizard Generated Code++ -->
  <!--
  // Created with a Google AJAX Search Wizard
  // http://code.google.com/apis/ajaxsearch/wizards.html
  -->

  <!--
  // The Following div element will end up holding the map search control.
  // You can place this anywhere on your page
  -->
  <div id="mapsearch">
    <span style="color:#676767;font-size:11px;margin:10px;padding:4px;">Loading...</span>
  </div>

  <!-- Maps Api, Ajax Search Api and Stylesheet
  // Note: If you are already using the Maps API then do not include it again
  //       If you are already using the AJAX Search API, then do not include it
  //       or its stylesheet again
  //
  // The Key Embedded in the following script tags is designed to work with
  // the following site:
  // http://localhost/sfa
  -->
  <script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAkug0nthHAqR_qYi5pwmw_BRXAJIoL5n65zLpVVhMWMvxEZWgRBSwLkssILD2QBcZtyHQuo3A2ru2rg"
    type="text/javascript"></script>
  <script src="http://www.google.com/uds/api?file=uds.js&v=1.0&source=uds-msw&key=ABQIAAAAkug0nthHAqR_qYi5pwmw_BRXAJIoL5n65zLpVVhMWMvxEZWgRBSwLkssILD2QBcZtyHQuo3A2ru2rg"
    type="text/javascript"></script>
  <link href="http://www.google.com/uds/css/gsearch.css" rel="stylesheet" type="text/css"/>

  <!-- Map Search Control and Stylesheet -->
  <script src="http://www.google.com/uds/solutions/mapsearch/gsmapsearch.js"
    type="text/javascript"></script>
  <link href="http://www.google.com/uds/solutions/mapsearch/gsmapsearch.css"
    rel="stylesheet" type="text/css"/>

  <style type="text/css">
    .gsmsc-mapDiv {
      height : 275px;
    }

    .gsmsc-idleMapDiv {
      height : 275px;
    }

    #mapsearch {
      width : 365px;
      margin: 10px;
      padding: 4px;
    }
  </style>
  <script type="text/javascript">
    function LoadMapSearchControl() {

      var options = {
            zoomControl : GSmapSearchControl.ZOOM_CONTROL_ENABLE_ALL,
            title : "Googleplex",
            url : "http://www.google.com/corporate/index.html",
            idleMapZoom : GSmapSearchControl.ACTIVE_MAP_ZOOM,
            activeMapZoom : GSmapSearchControl.ACTIVE_MAP_ZOOM
            }

      new GSmapSearchControl(
            document.getElementById("mapsearch"),
            "Mumbai",
            options
            );

    }
    // arrange for this function to be called during body.onload
    // event processing
    GSearch.setOnLoadCallback(LoadMapSearchControl);
  </script>
<!-- --End Map Search Solution Wizard Generated Code-- -->
