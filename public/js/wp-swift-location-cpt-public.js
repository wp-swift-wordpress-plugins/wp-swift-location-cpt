//@start closure
(function() {
    var autocomplete;
    var directionsService;
    var directionsDisplay;
    var map;
    var markers = [];
    var infoWindow;
    var route = new Route();
    var dataMarkerNodes;
    var addressInput = document.getElementById('addressInput');
    var locationSelect = document.getElementById("locationSelect");
    var htmlResults = document.getElementById('html-results');
    var showDebug = true;
    var searchDestinationLat = {};

    function Route() {
        this.origin = {
            lat: 0,
            lng: 0,
        };   
        this.destination = {
            lat: 0,
            lng: 0,
        };    
    }
    Route.prototype.isSet = function() {
        return this.origin.lat + this.origin.lng + this.destination.lat + this.destination.lng; 
    };

    // Expose to global
    window.initializeMapServices = function initializeMapServices() {
        // initMap();  
        // initAutocomplete();   
    };

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    window.geolocate = function() {
        console.log('Bias the autocomplete object to the users geographical location');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    };

    var initMap = function () {
        debug('initMap');
        if (typeof google !== "undefined" && document.getElementById('map') !== null) {
            var mapCenter = {
                lat: 52.2604705,
                lng: -7.1054075
            };
            dataMarkerNodes = JSON.parse(document.getElementById('map').getAttribute('data-markers'));
            directionsService = new google.maps.DirectionsService;
            directionsDisplay = new google.maps.DirectionsRenderer;
            map = new google.maps.Map(document.getElementById('map'), {
                center: mapCenter,
                zoom: 14,
                mapTypeId: 'roadmap',
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                }
            }); 
            
            directionsDisplay.setMap(map); 
            directionsDisplay.setPanel(document.getElementById('map-directions'));
            infoWindow = new google.maps.InfoWindow();

            if (dataMarkerNodes.length >0) {
                regionalLocations(dataMarkerNodes);
            }
        }      
    };

    var initAutocomplete = function () {
        if ( addressInput !== null ) {
            // Create the autocomplete object, restricting the search to geographica location types.
            autocomplete = new google.maps.places.Autocomplete(
                (addressInput), {
                    types: ['geocode']
                }
            );
            // Do stuff when the user selects an address from the dropdown
            autocomplete.addListener( 'place_changed', fillInAddress );
        }
    };

    // var placeChanged = function() {
    //     fillInAddress();   
    // }

    var fillInAddress = function () {
        debug("fillInAddress");
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        // get origin lat and lng
        route.origin.lat = place.geometry.location.lat();
        route.origin.lng = place.geometry.location.lng();   

        if (route.destination.lat && route.destination.lng) {
            // Destination cords are also set so show the route        
            calculateAndDisplayRoute(directionsService, directionsDisplay, route);
        }
        else {
            // Do an Ajax call to get the nearest location to the user selected place
            submitAjaxForm();
        }
    };

    var debug = function (key=false, value=false) {
        if (showDebug) {
            if (key && value) {
                console.log(key, value);
            }
            else if(key) {
                console.log(key);
            }
        }
    };

    var showRouteLinkClicked = function (el) {
        route.destination.lat = el.getAttribute('data-lat');
        route.destination.lng = el.getAttribute('data-lng');       
        fillInAddress();
        return false;    
    };

    var calculateAndDisplayRoute = function (directionsService, directionsDisplay, route) {
        debug("calculateAndDisplayRoute");

        var url = 'https://www.google.com/maps/dir/';
        url += '?api=1'; 
        url += '&origin='+route.origin.lat+','+route.origin.lng;
        url += '&destination='+route.destination.lat+','+route.destination.lng; 

        var link = document.createElement("a");
        link.href = url;
        link.target = "_blank";
        link.className = "button primary large";
        var node = document.createTextNode("Open in Maps");


        link.appendChild(node);

        var element = document.getElementById("map-directions-link");
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        };

        document.getElementById("map-directions-container").style.display = 'block';
        element.appendChild(link);    

        directionsService.route({
            origin: route.origin,
            destination: route.destination,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
                clearLocations();
                document.getElementById("map-directions").innerHTML = '';
                directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    };

    var regionalLocations = function (markerNodes) {
        var singleZoomLevel = 17;
        if (markerNodes !== null) {

            clearLocations();
            clearLocationSelect();
            var bounds = new google.maps.LatLngBounds();

            for (var i = 0; i < markerNodes.length; i++) {
                var id = markerNodes[i].id;
                var name = markerNodes[i].post_title;
                var address = markerNodes[i].location;
                if(typeof markerNodes[i].distance === 'number') {
                    var distance = parseFloat(markerNodes[i].distance);
                }
                else {
                    var distance = markerNodes[i].distance;
                }        
                var lat = parseFloat(markerNodes[i].lat);
                var lng = parseFloat(markerNodes[i].lng);
                var latlng = {
                    lat: lat,
                    lng: lng
                };
                createOption(name, distance, i);
                createMarker(latlng, name, address, distance);
                bounds.extend(latlng);
            }
            if (markerNodes.length > 0) {
                map.fitBounds(bounds);

                if (markerNodes.length === 1) {
                    var listener = google.maps.event.addListener(map, "idle", function() { 
                      if (map.getZoom() > singleZoomLevel) map.setZoom(singleZoomLevel); 
                      google.maps.event.removeListener(listener); 
                    });
                    searchDestinationLat.value = markerNodes[0].lat; 
                    searchDestinationLng.value = markerNodes[0].lng;
                    locationSelect.selected = 2;
                    document.getElementById("location-select-option-0").selected = true; 
                    // google.maps.event.trigger(markers[0], 'click');                     
                }

                locationSelect.style.visibility = "visible";
                locationSelect.onchange = function() {
                    if (locationSelect.options[locationSelect.selectedIndex].value !== "none") {
                        var markerNum = parseInt(locationSelect.options[locationSelect.selectedIndex].value);
                        // console.log('markerNum', markerNum);
                        // console.log('markers', markers);
                        if (markers.length > 0) {
                            route.destination.lat = markers[markerNum].getPosition().lat();
                            route.destination.lng = markers[markerNum].getPosition().lng();   

                            // var lat =  markers[markerNum].getPosition().lat();
                            // var lng =  markers[markerNum].getPosition().lng();  
                            // var destination = {
                            //     lat: lat,
                            //     lng: lng
                            // };   
                            // searchDestinationLat.value = lat; 
                            // searchDestinationLng.value = lng;
                            // addressInput.disabled = false;
                            //todo
                            google.maps.event.trigger(markers[markerNum], 'click');                        
                        }
                        else {
                            console.log('Change Route Now');
                            window.initMap();
                            // var lat =  markers[markerNum].getPosition().lat();
                            // var lng =  markers[markerNum].getPosition().lng();  
                            // searchDestinationLat.value = lat; 
                            // searchDestinationLng.value = lng; 
                            route.destination.lat = markers[markerNum].getPosition().lat();
                            route.destination.lng = markers[markerNum].getPosition().lng();      
                            fillInAddress();
                                    // Clear and show the route           
                            // calculateAndDisplayRoute(directionsService, directionsDisplay, origin, destination);    
                            // window.initMap();
                            // google.maps.event.trigger(markers[markerNum], 'click');
                            document.getElementById("location-select-option-"+markerNum).selected = true;
                        }

                    }
                    else {
                        route.origin.lat = 0;
                        route.origin.lng = 0;   
                        route.destination.lat = 0;
                        route.destination.lng = 0;   
                        // searchOriginLat.value = '';
                        // searchOriginLng.value = '';  
                        // searchDestinationLat.value = ''; 
                        // searchDestinationLng.value = '';
                        addressInput.value = '';                  
                        // addressInput.disabled = true;
                        if (dataMarkerNodes.length > 0) {
                            // directionsDisplay.setMap(null);
                            // directionsDisplay.setDirections(null);
                            // directionsDisplay.setDirections(response);
                            // directionsDisplay = new google.maps.DirectionsRenderer;
                            window.initMap();
                            // regionalLocations(dataMarkerNodes);
                            document.getElementById("map-directions-container").style.display = 'none';
                        }
                    }
                }
            }

        }

    }

    var clearLocations = function () {
        // if (locationSelect !== null) {
            infoWindow.close();
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers.length = 0;
            // locationSelect.innerHTML = "";
            // var option = document.createElement("option");
            // option.value = "none";
            // option.innerHTML = "See all results:";
            // locationSelect.appendChild(option);
            // locationSelect.style.visibility = "hidden";        
        // }
    }
    var clearLocationSelect = function () {
        if (locationSelect !== null) {
            // infoWindow.close();
            // for (var i = 0; i < markers.length; i++) {
            //     markers[i].setMap(null);
            // }
            // markers.length = 0;
            locationSelect.innerHTML = "";
            var option = document.createElement("option");
            option.value = "none";
            option.innerHTML = "See all results:";
            locationSelect.appendChild(option);
            // locationSelect.style.visibility = "hidden";        
        }
    }

    var createMarker = function (latlng, name, address, distance) {
        var marker = newMarker(latlng, name, address, distance);
        markers.push(marker);
    }

    var createOption = function (name, distance, num) {
        if (locationSelect !== null) {
            var option = document.createElement("option");
            option.value = num;
            option.id = "location-select-option-"+num;
            option.innerHTML = name;
            locationSelect.appendChild(option);
        }
    }

    var newMarker = function (latlng, name, address, distance) {
        
        var html = "<b>" + name + "</b> <br/>" + address;
        if(typeof distance === 'number') {
            distance = Math.round(distance * 10) / 10;

            html += "<br/>(<i>" + distance + " KM</i>)";
        }
        var marker = new google.maps.Marker({
            map: map,
            position: latlng
        });
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
        return marker;
    }
    debug("ready");
    var submitAjaxForm = function () {
        debug("submitAjaxForm");
        var errorsInForm = 0;
        var errorsMsg = '';
        var place = autocomplete.getPlace();
        var cats = [];
        var radius = 500;//document.getElementById('radiusSelect').value;
        var address = addressInput.value;

        // $("input[name='cat[]']:checked").each(function() {
        //     cats.push( $(this).val() );
        // });

        if (address === '') {
            errorsInForm++;
            errorsMsg = "Please provide a location.";
        }

        // if (cats.length === 0) {
        //     errorsInForm++;
        //     errorsMsg += "\nPlease select at least one category.";    
        // }


        // if (lat === '' || lng === '') {
        //     errorsInForm++;
        //     errorsMsg += "\nUnable to determine longitude and latitude.";    
        // }



        if(typeof place !== "undefined") {
            debug("submitAjaxForm route", route);
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            // searchOriginLat.value = lat;
            // searchOriginLng.value = lng;



            // LocationSearchAjax is set on server using wp_localize_script
            if(errorsInForm === 0 && typeof LocationSearchAjax !== "undefined") {
                // Disable buttons and show loading gif
            // setActiveSearch();
                // Set post object LocationSearchAjax 
                LocationSearchAjax.action = "wp_swift_submit_location_search_form";
                LocationSearchAjax.lat = lat;
                LocationSearchAjax.lng = lng;
                LocationSearchAjax.radius = radius;
                LocationSearchAjax.address = address;
                LocationSearchAjax.cat = cats;
       


                $.post(LocationSearchAjax.ajaxurl, LocationSearchAjax, function(response) {
                    var serverResponse = JSON.parse(response);
                    htmlResults.innerHTML = serverResponse.html;
                    if(typeof serverResponse.destination !== "undefined") {
                        route.destination.lat = serverResponse.destination.lat;  
                        route.destination.lng = serverResponse.destination.lng;              
                        calculateAndDisplayRoute(directionsService, directionsDisplay, route);   
                    }
                }); 
            }
            else {
                alert(errorsMsg);
                $submitSearchForm.prop('disabled', false);
            }

        }
        else if(errorsInForm > 0) {
            alert(errorsMsg);
        }
    }
})();//@end closure