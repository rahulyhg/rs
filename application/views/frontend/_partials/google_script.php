<script>
 
    var roadShare = roadShare || {};
        roadShare.origin = {};
        roadShare.destination = {};
        roadShare.waypoints = [];

    (function(roadShare, $){ 

      var markerArray = [];

      roadShare.autoComplete = function( input ){
        var autocomplete = new google.maps.places.Autocomplete(input, {types: ['geocode']});
        autocomplete.addListener('place_changed', function(){
            var place = autocomplete.getPlace(),
                lat = place.geometry.location.G,
                lang = place.geometry.location.K;

            var location = {};
                location.LatLng = new google.maps.LatLng(lat, lang);
                location.formatted_address = place.formatted_address;




            switch($(input).attr('data-type'))
            {
              case 'origin':
                roadShare.origin = location;
                break;

              case 'destination':
                roadShare.destination = location;
                break;

              case 'waypoint':
                roadShare.waypoints.push(location);
            }
            
            console.log(place);
            console.log(place.geometry.location.toString());
            console.log(place.geometry.location.toUrlValue());
            $.fn.googleDirection();
        });
      };


      roadShare.showDirection = function(elm_map, settings){

        var markerArray = [],
            directionsService = new google.maps.DirectionsService,
            map = new google.maps.Map(elm_map, { zoom: 13,center: {lat: 40.771, lng: -73.974} }),
            directionsDisplay = new google.maps.DirectionsRenderer({map: map}),
            stepDisplay = new google.maps.InfoWindow;

        // First, remove any existing markers from the map.
        for (var i = 0; i < markerArray.length; i++) 
        {
          markerArray[i].setMap(null);
        }

        // Retrieve the start and end locations and create a DirectionsRequest using
        // WALKING directions.
        directionsService.route(settings, function(response, status) {
            // Route the directions and pass the response to a function to create
            // markers for each step.
            if (status === google.maps.DirectionsStatus.OK) 
            {
              document.getElementById('warnings_panel').innerHTML =
                  '<b>' + response.routes[0].warnings + '</b>';
              directionsDisplay.setDirections(response);
              //showSteps(response, markerArray, stepDisplay, map);
            } 
            else 
            {
              window.alert('Directions request failed due to ' + status);
            }
          });
      };

      
      $.fn.googleAutocomplete = function(options) {
        
        var defaults = {
            types: ["geocode"],
            preventSubmit: false
        };
        var settings = $.extend(defaults, options);

        return this.each(function() {
          var input = this;
          roadShare.autoComplete(this);
        });

      };


      $.fn.googleDirection = function(options) {
        //console.log('GGGGGGG');
        var wp = [];
        for(var i=0;i<roadShare.waypoints.length; i++)
        {
          var tmp = {};
              tmp.location = roadShare.waypoints[i].LatLng;
              tmp.stopover = true;
          wp.push(tmp);
        }

        var defaults = {
            origin:roadShare.origin.LatLng,
            destination: roadShare.destination.LatLng,
            travelMode: google.maps.TravelMode.DRIVING,
            waypoints:wp
        };
        var settings = $.extend(defaults, options);
        console.log(settings);

        var elm_map = document.getElementById('map');

          roadShare.showDirection(elm_map, settings);

      };


      /*function showSteps(directionResult, markerArray, stepDisplay, map) {
        // For each step, place a marker, and add the text to the marker's infowindow.
        // Also attach the marker to an array so we can keep track of it and remove it
        // when calculating new routes.
        var myRoute = directionResult.routes[0].legs[0];
        for (var i = 0; i < myRoute.steps.length; i++) {
          var marker = markerArray[i] = markerArray[i] || new google.maps.Marker;
          marker.setMap(map);
          marker.setPosition(myRoute.steps[i].start_location);
          attachInstructionText(stepDisplay, marker, myRoute.steps[i].instructions);
        }
      }

      function attachInstructionText(stepDisplay, marker, text, map) {
        google.maps.event.addListener(marker, 'click', function() {
          // Open an info window when the marker is clicked on, containing the text
          // of the step.
          stepDisplay.setContent(text);
          stepDisplay.open(map, marker);
        });
      }*/
    })(roadShare || {}, window.jQuery || window.$);


    window.onload = function(){
      $('.autocomplete').googleAutocomplete();

      $('#add_stopovers').on('click', function(){
         var elm = $('<input  data-type="waypoint" class="autocomplete" placeholder="Enter your address" type="text"></input>');

         elm.googleAutocomplete();
         
         $('#stopovers').append(elm);

      })
      
      
      var defaults = {
            origin:new google.maps.LatLng(13.08, 80.27),
            destination: new google.maps.LatLng(10.35, 77.95),
            travelMode: google.maps.TravelMode.DRIVING,
            waypoints:[{location:'Natham, Tamil Nadu, IN', stopover:true}],
        };
      roadShare.showDirection(document.getElementById('map'), defaults);
      
    };

    
    </script>