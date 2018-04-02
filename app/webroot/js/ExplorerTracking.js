function saveSubjectLogin() {

    if (!navigator.geolocation) {
        $.alert('Your browser does not support location finding.');
    }
    else {
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;
				console.log(lat, lng);
                //codeLatLng(lat, long, saveLogin);
            },
            function () {
                $.alert('Your location could not be found.');
            }
        );
    }
}

function saveLogin(loc) {

    var timezone = jstz.determine().name();
    $.dialog('New Check-in Venue:',
        function (result, args, userResp) {

            var url = APPROOT + 'logins/add?' +
                'longitude=' + loc.long +
                '&latitude=' + loc.lat +
                '&city=' + loc.city +
                '&region=' + loc.region +
                '&country=' + loc.country +
                '&timezone=' + timezone;

            if (result) {
                userResp = $.htmlEncode(userResp);
                url += ('&venue=' + userResp);
            }
            window.location = url;
        }
    );
}

/*
 * Determines city, region, country for lat and lng and passes all fields to a callback
 */
function codeLatLng(lat, lng, callBack) {

    var geocoder = new google.maps.Geocoder();

    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {

            if (results[1]) {
                for (var j = 0; j < results.length; j++) {	//use record with type of locality.
                    if (results[j].types[0] == 'locality' || results[j].types[0] == 'postal_code') {
                        break;
                    }
                }
                if (j < results.length) {
                    for (var i = 0; i < results[j].address_components.length; i++) {
                        if (results[j].address_components[i].types[0] == "locality") {
                            city = results[j].address_components[i];
                        }
                        if (results[j].address_components[i].types[0] == "administrative_area_level_1") {

                            region = results[j].address_components[i];
                        }
                        if (results[j].address_components[i].types[0] == "country") {
                            country = results[j].address_components[i];
                        }
                    }

                    locCity = typeof city != 'undefined' ? city.long_name : '';
                    locRegion = typeof region != 'undefined' ? region.long_name : '';
                    if (typeof country != 'undefined') {
                        if (country.long_name) {
                            locCountry = country.long_name;
                        } else if (country.short_name) {
                            locCountry = country.short_name;
                        }
                    }

                    loc = {'lat': lat, 'long': lng, 'city': locCity, 'region': locRegion, 'country': locCountry};

                    callBack(loc);
                    return;
                }
            }
            $.alert("City and Country could not be found for coordinates " + lat + ', ' + lng);
        } else {
            $.alert("Geocoder failed due to: " + status);
        }
    });
}


//get temperature script.
function setLocalTemp(explorerLoc) {
    curLoc = 'Boston, MA';  //default location
    if (city = explorerLoc.city) {
        curLoc = city;
        if (country = explorerLoc.country) {
            curLoc = city + ', ' + country;
        }
    }
    curLoc = curLoc.replace(/^\s\s*/, '').replace(/\s\s*$/, '');	//trim
	console.log(curLoc);
    $.simpleWeather({
        location: 'Stoughton, Wisconsin',
        unit: 'f',
		error: function(error) { console.log(error); },
        success: function (weather) {          
            var tempF = weather.temp;
            var tempC = Math.round((5.0 / 9.0) * (tempF - 32));
            $('#local-temp').html(tempF + '&deg;F / ' + tempC + '&deg;C');
        },
        error: function (error) {
            $("#weather").html('<p>' + error + '</p>');
        }
    });
}

