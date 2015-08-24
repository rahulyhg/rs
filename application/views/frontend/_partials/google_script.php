<script>
 
    var roadShare = roadShare || {};
        roadShare.origin = {};
        roadShare.destination = {};
        roadShare.waypoints = [];
        roadShare.route_details = [];
        roadShare.totalDist = 0;//km
        roadShare.totalTime = 0;//hr:mins

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
                location.name = place.name;
                location.LatLngString = lat+'|'+lang;



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

            if( !$.isEmptyObject(roadShare.origin) && !$.isEmptyObject(roadShare.destination))
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
              
              /*var route = response.routes[0];
              var summaryPanel = document.getElementById("directions_panel");
              summaryPanel.innerHTML = "";
              // For each route, display summary information.
              for (var i = 0; i < route.legs.length; i++) {
                var routeSegment = i + 1;
                summaryPanel.innerHTML += "<b>Route Segment: " + routeSegment + "</b><br />";
                summaryPanel.innerHTML += route.legs[i].start_address + " to ";
                summaryPanel.innerHTML += route.legs[i].end_address + "<br />";
                summaryPanel.innerHTML += route.legs[i].distance.text + "<br /><br />";
                console.log(route.legs[i]);
              }*/
              
              computeTotalDistance(response);
            } 
            else 
            {
              window.alert('Directions request failed due to ' + status);
            }
          });
      };

      
      function computeTotalDistance(result) {
        var totalDist = 0;
        var totalTime = 0;
        var myroute = result.routes[0];
        
        console.log(result);    
        console.log(myroute.waypoint_order);
        roadShare.route_details = [];
        for (i = 0; i < myroute.legs.length; i++) 
        {
          

          var tmp = {};
          if( i == 0 && myroute.legs.length == 1 )
          {
              tmp.from = roadShare.origin.name;
              tmp.to   = roadShare.destination.name;
          }
          else if( i == 0)
          {
              tmp.from = roadShare.origin.name;
              tmp.to   = roadShare.waypoints[myroute.waypoint_order[i]].name;
          }
          else if(i>0 && i<myroute.legs.length-1)
          {
              tmp.from = roadShare.waypoints[myroute.waypoint_order[i-1]].name;
              tmp.to   = roadShare.waypoints[myroute.waypoint_order[i]].name;
          }
          else
          {
              tmp.from = roadShare.waypoints[myroute.waypoint_order[i-1]].name;
              tmp.to   = roadShare.destination.name;
          }

          tmp.totalDist = myroute.legs[i].distance.value;
          tmp.totalTime = myroute.legs[i].duration.value;

          roadShare.route_details.push(tmp);

          totalDist += myroute.legs[i].distance.value;
          totalTime += myroute.legs[i].duration.value;
        }
        totalDist = totalDist / 1000;

        console.log(roadShare.route_details);

        roadShare.totalDist = totalDist;
        roadShare.totalTime = totalTime;
        //document.getElementById("total_distance").innerHTML = "total distance is: " + totalDist + " km<br>total time is: " + (totalTime / 60).toFixed(2) + " minutes";
      }

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


      
    })(roadShare || {}, window.jQuery || window.$);


    window.onload = function(){
      $('.autocomplete').googleAutocomplete();

      $('#add_stopovers').on('click', function(){
         var elm = $('<input  data-type="waypoint" class="autocomplete" placeholder="Enter your address" type="text"></input>');

         elm.googleAutocomplete();
         
         $('#stopovers').append(elm);

      })
      
      new google.maps.Map(document.getElementById('map'), {
        center: {lat: 13.7563, lng: 100.5018},
        zoom: 13
      });

      /*var defaults = {
            origin:new google.maps.LatLng(13.08, 80.27),
            destination: new google.maps.LatLng(13.08, 80.27),
            travelMode: google.maps.TravelMode.DRIVING,
            //waypoints:[{location:'Natham, Tamil Nadu, IN', stopover:true}],
        };

      roadShare.origin.name = 'Chennai';
      roadShare.origin.LatLng = new google.maps.LatLng(13.08, 80.27);

      roadShare.destination.name = 'Madurai';
      roadShare.destination.LatLng = new google.maps.LatLng(13.08, 80.27);

      //roadShare.waypoints.push({name:'Natham', location:'Natham, Tamil Nadu, IN', stopover:true});
      roadShare.showDirection(document.getElementById('map'), defaults);
      */
      
    };

    
    </script>