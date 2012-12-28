/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

       function MapTypeControl(opt_opts) {
                this.options = opt_opts || {};
            }

            MapTypeControl.prototype = new GControl();

            MapTypeControl.prototype.initialize = function(map) {
                var container = document.createElement("div");
                var me = this;
                var mapDiv = me.createButton_("Map");
                var satDiv = me.createButton_("Satellite");
                var hybDiv = me.createButton_("Hybrid");
                var phyDiv = me.createButton_("Terrain");

                me.assignButtonEvent_(mapDiv, map, G_NORMAL_MAP, [phyDiv, satDiv, hybDiv]);
                me.assignButtonEvent_(phyDiv, map, G_PHYSICAL_MAP, [mapDiv, satDiv, hybDiv]);
                me.assignButtonEvent_(satDiv, map, G_SATELLITE_MAP, [phyDiv, mapDiv, hybDiv]);
                me.assignButtonEvent_(hybDiv, map, G_HYBRID_MAP, [phyDiv, satDiv, mapDiv]);
                GEvent.addListener(map, "maptypechanged", function() {
                    if (map.getCurrentMapType() == G_NORMAL_MAP) {
                        GEvent.trigger(mapDiv, "click");
                    } else if (map.getCurrentMapType() == G_PHYSICAL_MAP) {
                        GEvent.trigger(phyDiv, "click");
                    } else if (map.getCurrentMapType() == G_SATELLITE_MAP) {
                        GEvent.trigger(satDiv, "click");
                    } else if (map.getCurrentMapType() == G_HYBRID_MAP) {
                        GEvent.trigger(hybDiv, "click");
                    }
                });

                container.appendChild(mapDiv);
                container.appendChild(satDiv);
                container.appendChild(hybDiv);
                container.appendChild(phyDiv);

                map.getContainer().appendChild(container);

                GEvent.trigger(map, "maptypechanged");
                return container;
            }

            MapTypeControl.prototype.createButton_ = function(text) {
                var buttonDiv = document.createElement("div");
                this.setButtonStyle_(buttonDiv);
                buttonDiv.style.cssFloat = "left";
                buttonDiv.style.styleFloat = "left";
                var textDiv = document.createElement("div");
                textDiv.appendChild(document.createTextNode(text));
                textDiv.style.width = "6em";
                buttonDiv.appendChild(textDiv);
                return buttonDiv;
            }

            MapTypeControl.prototype.assignButtonEvent_ = function(div, map, mapType, otherDivs) {
                var me = this;

                GEvent.addDomListener(div, "click", function() {
                    for (var i = 0; i < otherDivs.length; i++) {
                        me.toggleButton_(otherDivs[i].firstChild, false);
                    }
                    me.toggleButton_(div.firstChild, true);
                    map.setMapType(mapType);
                });
            }

            MapTypeControl.prototype.toggleButton_ = function(div, boolCheck) {
                div.style.fontWeight = boolCheck ? "bold" : "";
                div.style.border = "1px solid white";
                var shadows = boolCheck ? ["Top", "Left"] : ["Bottom", "Right"];
                for (var j = 0; j < shadows.length; j++) {
                    div.style["border" + shadows[j]] = "1px solid #b0b0b0";
                }
            }

            MapTypeControl.prototype.getDefaultPosition = function() {
                return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 7));
            }

            MapTypeControl.prototype.setButtonStyle_ = function(button) {
                button.style.color = "#000000";
                button.style.backgroundColor = "white";
                button.style.font = "small Arial";
                button.style.border = "1px solid black";
                button.style.padding = "0px";
                button.style.margin= "0px";
                button.style.textAlign = "center";
                button.style.fontSize = "12px";
                button.style.cursor = "pointer";
            }



         //<![CDATA[
            // Latitude and Longitude math routines are from: http://www.fcc.gov/mb/audio/bickel/DDDMMSS-decimal.html

            var map = null;
            var geocoder = null;
            var latsgn = 1;
            var lgsgn = 1;
            var zm = 0;
            var marker = null;
            var posset = 0;

            function xz() {
                if (GBrowserIsCompatible()) {
                    map = new GMap2(document.getElementById("map"));
                    map.setCenter(new GLatLng(20.0, -10.0), 2);
                    map.setMapType(G_NORMAL_MAP);
                    map.addControl(new GLargeMapControl());
                    map.addControl(new MapTypeControl());
                    map.addControl(new GScaleControl());
                    map.enableScrollWheelZoom();
                    map.disableDoubleClickZoom();
                    geocoder = new GClientGeocoder();

                    marker = new GMarker(new GLatLng(20.0, -10.0), {draggable: true});
                    map.addOverlay(marker);

                    GEvent.addListener(map, 'click', function(overlay,point)
                    {
                        if (overlay)
                        {
                        }
                        else
                        {
                            posset = 1;

                            fc( point) ;
                            //marker.setPoint(point);
                            if (zm == 0)
                            {map.setCenter(point,7); zm = 1;}
                            else
                            {map.setCenter(point);}
                            computepos(point);
                        }
                    });

                    GEvent.addListener(map, 'singlerightclick', function(point,src,overlay)
                    {
                        if (overlay)
                        {
                            if (overlay != marker)
                            {
                                map.removeOverlay(overlay)
                                document.getElementById("latbox").value='';
                                document.getElementById("latboxm").value='';
                                document.getElementById("latboxmd").value='';
                                document.getElementById("latboxms").value='';
                                document.getElementById("lonbox").value='';
                                document.getElementById("lonboxm").value='';
                                document.getElementById("lonboxmd").value='';
                                document.getElementById("lonboxms").value='';
                            }
                        }
                        else
                        {}
                    });

                    GEvent.addListener(marker, "dragend", function() {
                        var point = marker.getLatLng();
                        posset = 1;

                        if (zm == 0)
                        {map.setCenter(point,7); zm = 1;}
                        else
                        {map.setCenter(point);}
                        computepos(point);
                    });


                    GEvent.addListener(marker, "click", function() {
                        var point = marker.getLatLng();
                        marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
                        computepos (point);
                    });

                }}

            function computepos (point)
            {
                var latA = Math.abs(Math.round(value=point.y * 1000000.));
                var lonA = Math.abs(Math.round(value=point.x * 1000000.));

                if(value=point.y < 0)
                {
                    var ls = '-' + Math.floor((latA / 1000000));
                }
                else
                {
                    var ls = Math.floor((latA / 1000000));
                }

                var lm = Math.floor(((latA/1000000) - Math.floor(latA/1000000)) * 60);
                var ld = ( Math.floor(((((latA/1000000) - Math.floor(latA/1000000)) * 60) - Math.floor(((latA/1000000) - Math.floor(latA/1000000)) * 60)) * 100000) *60/100000 );

                if(value=point.x < 0)
                {
                    var lgs = '-' + Math.floor((lonA / 1000000));
                }
                else
                {
                    var lgs = Math.floor((lonA / 1000000));
                }

                var lgm = Math.floor(((lonA/1000000) - Math.floor(lonA/1000000)) * 60);
                var lgd = ( Math.floor(((((lonA/1000000) - Math.floor(lonA/1000000)) * 60) - Math.floor(((lonA/1000000) - Math.floor(lonA/1000000)) * 60)) * 100000) *60/100000 );

                
                document.getElementById("latitude").value=point.y;
                document.getElementById("longitude").value=point.x;

                /*document.getElementById("latboxm").value=ls;
                document.getElementById("latboxmd").value=lm;
                document.getElementById("latboxms").value=ld;

                document.getElementById("latbox").value=point.y;
                document.getElementById("lonbox").value=point.x;
                
                document.getElementById("lonboxm").value=lgs;
                document.getElementById("lonboxmd").value=lgm;
                document.getElementById("lonboxms").value=lgd;
                alert("Latitude :"+point.y+"\n Longitude :"+point.x);*/
            }

            function showAddress(address) {
                if (geocoder) {
                    geocoder.getLatLng(
                    address,
                    function(point) {
                        if (!point) {
                            alert(address + " not found");
                        } else {

                            posset = 1;

                            map.setMapType(G_HYBRID_MAP);
                            map.setCenter(point,16);
                            zm = 1;
                            marker.setPoint(point);
                            GEvent.trigger(marker, "click");
                        }
                    }
                );
                }
            }

            function showLatLong(latitude, longitude) {
                
                if (isNaN(latitude)) {alert(' Latitude must be a number. e.g. Use +/- instead of N/S'); return false;}
                if (isNaN(longitude)) {alert(' Longitude must be a number.  e.g. Use +/- instead of E/W'); return false;}

                latitude1 = Math.abs( Math.round(latitude * 1000000.));
                if(latitude1 > (90 * 1000000)) { alert(' Latitude must be between -90 to 90. ');  document.getElementById("latbox1").value=''; return;}
                longitude1 = Math.abs( Math.round(longitude * 1000000.));
                if(longitude1 > (180 * 1000000)) { alert(' Longitude must be between -180 to 180. ');  document.getElementById("lonbox1").value='';  return;}

                var point = new GLatLng(latitude,longitude);

                posset = 1;

                if (zm == 0)
                {
                    map.setMapType(G_NORMAL_MAP);
                    map.setCenter(point,16);
                    zm = 1;
                }
                else
                {
                    map.setCenter(point);
                }

                var html = "";
                html += html + "Latitude, Longitude<br>" + point.toUrlValue(6);

                var baseIcon = new GIcon();
                baseIcon.iconSize=new GSize(32,32);
                baseIcon.shadowSize=new GSize(56,32);
                baseIcon.iconAnchor=new GPoint(16,32);
                baseIcon.infoWindowAnchor=new GPoint(16,0);
                var thisicon = new GIcon(baseIcon, "http://itouchmap.com/i/blue-dot.png", null, "http://itouchmap.com/i/msmarker.shadow.png");

                var marker = new GMarker(point,thisicon);
                GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
                map.addOverlay(marker);

                GEvent.trigger(marker, "click");
            }

            function showLatLong1(latitude, latitudem,latitudes, longitude,  longitudem,  longitudes) {
                alert('Pankaj');
                if (isNaN(latitude)) {alert(' Latitude must be a number. e.g. Use +/- instead of N/S'); return false;}
                if (isNaN(latitudem)) {alert(' Latitude must be a number. e.g. Use +/- instead of N/S'); return false;}
                if (isNaN(latitudes)) {alert(' Latitude must be a number. e.g. Use +/- instead of N/S'); return false;}
                if (isNaN(longitude)) {alert(' Longitude must be a number.  e.g. Use +/- instead of E/W'); return false;}
                if (isNaN(longitudem)) {alert(' Longitude must be a number.  e.g. Use +/- instead of E/W'); return false;}
                if (isNaN(longitudes)) {alert(' Longitude must be a number.  e.g. Use +/- instead of E/W'); return false;}

                if(latitude < 0)  { latsgn = -1; }
                alat = Math.abs( Math.round(latitude * 1000000.));
                if(alat > (90 * 1000000)) { alert(' Degrees Latitude must be between -90 to 90. ');  document.getElementById("latbox1m").value=''; document.getElementById("latbox1md").value=''; document.getElementById("latbox1ms").value=''; return; }
                latitudem = Math.abs(Math.round(latitudem * 1000000.)/1000000);  //integer
                absmlat = Math.abs(Math.round(latitudem * 1000000.));  //integer
                if(absmlat >= (60 * 1000000)) {  alert(' Minutes Latitude must be between 0 to 59. ');  document.getElementById("latbox1md").value=''; document.getElementById("latbox1ms").value=''; return;}
                latitudes = Math.abs(Math.round(latitudes * 1000000.)/1000000);
                absslat = Math.abs(Math.round(latitudes * 1000000.));
                if(absslat > (59.99999999 * 1000000)) {  alert(' Seconds Latitude must be between 0 and 59.99. '); document.getElementById("latbox1ms").value=''; return; }

                if(longitude < 0)  { lgsgn = -1; }
                alon = Math.abs( Math.round(longitude * 1000000.));
                if(alon > (180 * 1000000)) {  alert(' Degrees Longitude must be between -180 to 180. '); document.getElementById("lonbox1m").value=''; document.getElementById("lonbox1md").value=''; document.getElementById("lonbox1ms").value=''; return;}
                longitudem = Math.abs(Math.round(longitudem * 1000000.)/1000000);
                absmlon = Math.abs(Math.round(longitudem * 1000000));
                if(absmlon >= (60 * 1000000))   {  alert(' Minutes Longitude must be between 0 to 59. '); document.getElementById("lonbox1md").value=''; document.getElementById("lonbox1ms").value='';   return;}
                longitudes = Math.abs(Math.round(longitudes * 1000000.)/1000000);
                absslon = Math.abs(Math.round(longitudes * 1000000.));
                if(absslon > (59.99999999 * 1000000)) {  alert(' Seconds Longitude must be between 0 and 59.99. '); document.getElementById("lonbox1ms").value=''; return;}

                latitude = Math.round(alat + (absmlat/60.) + (absslat/3600.) ) * latsgn/1000000;
                longitude = Math.round(alon + (absmlon/60) + (absslon/3600) ) * lgsgn/1000000;

                var point = new GLatLng(latitude,longitude);
                posset = 1;

                if (zm == 0)
                {
                    map.setMapType(G_HYBRID_MAP);
                    map.setCenter(point,16);
                    zm = 1;
                }
                else
                {
                    map.setCenter(point);
                }
                var html = "";
                html += html + "Latitude, Longitude<br>" + point.toUrlValue(6);

                var baseIcon = new GIcon();
                baseIcon.iconSize=new GSize(32,32);
                baseIcon.shadowSize=new GSize(56,32);
                baseIcon.iconAnchor=new GPoint(16,32);
                baseIcon.infoWindowAnchor=new GPoint(16,0);
                var thisicon = new GIcon(baseIcon, "http://itouchmap.com/i/blue-dot.png", null, "http://itouchmap.com/i/msmarker.shadow.png");

                var marker = new GMarker(point,thisicon);
                GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
                map.addOverlay(marker);

                GEvent.trigger(marker, "click");
            }

            function streetview()
            {
                if (posset == 0)
                {
                    alert("Position Not Set.  Please click on the spot on the map to set the street view point.");
                    return;
                }

                var point = map.getCenter();
                var t1 = String(point);
                t1 = t1.replace(/[() ]+/g,"");
                var str = "http://www.streetviews.co?e=" + t1;
                var popup = window.open(str, "streetview");
                popup.focus();
            }

            function googleearth()
            {
                if (posset == 0)
                {
                    alert("Position Not Set.  Please click on the spot on the map to set the Google Earth map point.");
                    return;
                }
                var point = map.getCenter();
                var gearth_str = "http://gmap3d.com?r=3dmap&mt=Latitude-Longitude Point&ml=" + point.y+ "&mg=" + point.x;
                var popup = window.open(gearth_str, "googleearth");
                popup.focus();
            }

            function pictures()
            {
                if (posset == 0)
                {
                    alert("Position Not Set.  Please click on the spot on the map to set the photograph point.");
                    return;
                }
                var point = map.getCenter();
                var pictures_str = "http://ipicture.mobi?r=pictures&mt=Latitude-Longitude Point&ml=" + point.y+ "&mg=" + point.x;
                var popup = window.open(pictures_str, "pictures");
                popup.focus();
            }

            function lotsize()
            {
                if (posset == 0)
                {
                    alert("Position Not Set.  Please click on the spot on the map to set the lot size map point.");
                    return;
                }
                var point = map.getCenter();
                var t1 = String(point);
                t1 = t1.replace(/[() ]+/g,"");
                var vpike_str = "http://viewofhouse.com?e=" + t1 + "::findlotsize:";
                var popup = window.open(vpike_str, "lotsize");
                popup.focus();
            }

            function getaddress()
            {
                if (posset == 0)
                {
                    alert("Position Not Set.  Please click on the spot on the map to set the get address map point.");
                    return;
                }
                var point = map.getCenter();
                var t1 = String(point);
                t1 = t1.replace(/[() ]+/g,"");
                var getaddr_str = "http://www.getaddress.net?latlng=" + t1;
                var popup = window.open(getaddr_str, "getaddress");
                popup.focus();
            }

            function fc( point )
            {
                var html = "";
                html += html + "Latitude, Longitude<br>" + point.toUrlValue(6);

                var baseIcon = new GIcon();
                baseIcon.iconSize=new GSize(32,32);
                baseIcon.shadowSize=new GSize(56,32);
                baseIcon.iconAnchor=new GPoint(16,32);
                baseIcon.infoWindowAnchor=new GPoint(16,0);
                var thisicon = new GIcon(baseIcon, "google/blue-dot.png", null, "google/msmarker.shadow.png");

                var marker = new GMarker(point,thisicon);
                GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
                map.addOverlay(marker);
            }


            function createMarker(point, html)
            {
                var marker = new GMarker(point);
                GEvent.addListener(marker, "click", function()
                {
                    marker.openInfoWindowHtml(html);
                });
                return marker;
            }

            function reset() {
                map.clearOverlays();
                document.getElementById("latbox").value='';
                document.getElementById("latboxm").value='';
                document.getElementById("latboxmd").value='';
                document.getElementById("latboxms").value='';
                document.getElementById("lonbox").value='';
                document.getElementById("lonboxm").value='';
                document.getElementById("lonboxmd").value='';
                document.getElementById("lonboxms").value='';
                marker = new GMarker(new GLatLng(20.0, -10.0), {draggable: true});
                map.addOverlay(marker);
                marker.setPoint(map.getCenter());

                GEvent.addListener(marker, "dragend", function() {
                    var point = marker.getLatLng();
                    posset = 0;

                    if (zm == 0)
                    {map.setCenter(point,7); zm = 1;}
                    else
                    {map.setCenter(point);}
                    computepos(point);
                });

                GEvent.addListener(marker, "click", function() {
                    var point = marker.getLatLng();
                    marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
                    computepos (point);
                });
            }

            function reset1() {
                marker.setPoint(map.getCenter());
            }

            //]]>
