@extends('admin/app_admin')
@section('content')

<link href="/css/admin/jobs.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show Job
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body" style="overflow: hidden;">
    <p><b>IP: </b><span>{{$ip}}</span></p>
        <div class="portlet box red col-md-6">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Location data from GeoIp </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <ul>
                    <li> <b>Latitude: </b><span>{{$latitude}}</span> </li>
                    <li> <b>Longitude: </b><span>{{$longitude}}</span></li>
                    <li> <b>City: </b><span>{{$cityName}}</span> </li>
                    <li> <b>Country: </b><span>{{$countryName}}</span> </li>
                    <li> <b>Country Code: </b><span>{{$countryCode}}</span> </li>
                </ul>
            </div>
        </div>
        <!-- <div class="col-md-6" style="border: 1px solid #666; ">
            <p><b>IP: </b><span>{{$ip}}</span></p>
            <p>Location data from GeoIp</p>
            <p><b>City: </b><span>{{$cityName}}</span></p>
            <p><b>Country: </b><span>{{$countryName}}</span></p>
            <p><b>Country Code: </b><span>{{$countryCode}}</span></p>
            <p><b>Latitude: </b><span>{{$latitude}}</span></p>
            <p><b>Longitude: </b><span>{{$longitude}}</span></p>
        </div> -->
        <div class="portlet box blue col-md-6">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Location data from HTML geolocation</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <ol>
                    <li> <b>Latitude: </b><span id="html-latitude"></span> </li>
                    <li> <b>Longitude: </b><span id="html-longitude"></span> </li>
                    <li> <b>City (from Google Maps Geocoder):</b><span id="html-city"></span> </li>
                    <li> <b>Country (from Google Maps Geocoder):</b><span id="html-country"></span> </li>
                    <li> <b>Country Code (from Google Maps Geocoder):</b><span id="html-country-code"></span> </li>
                </ol>
            </div>
        </div>

        <div class="portlet box yellow col-md-6">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Location data from Accept-Language Header </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <ul class="list-unstyled">
                    <li> <b>Language: </b><span>{{$headerLang}}</span> </li>
                    <li> <b>Country COde: </b><span>{{$headerCountryCode}}</span> </li>
                </ul>
            </div>
        </div>

        
        <!-- <div class="col-md-6" style="border: 1px solid #666; ">
            <p>Latitude and Longitude from HTML geolocation</p>
            <p><b>Latitude: </b><span id="html-latitude"></span></p>
            <p><b>Longitude: </b><span id="html-longitude"></span></p>
            <p>Latitude and Longitude are sent to Google Maps Geocoder</p>
            <p>City, Country and Country Code from Google Maps Geocoder</p>
            <p><b>City: </b><span id="html-city"></span></p>
            <p><b>Country: </b><span id="html-country"></span></p>
            <p><b>Country Code: </b><span id="html-country-code"></span></p>
            
        </div> -->
    </div>
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){



    navigator.geolocation.getCurrentPosition(showPosition);

    
    function showPosition(position) {
        // x.innerHTML = "Latitude: " + position.coords.latitude + 
        // "<br>Longitude: " + position.coords.longitude; 
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var loc = getCountry(results);
                }
            }
        });

        var latP = document.getElementById("html-latitude");
        latP.innerHTML = latitude;
        var lonP = document.getElementById("html-longitude");
        lonP.innerHTML = longitude;
    }

   

    function getCountry(results)
    {
        for (var i = 0; i < results[0].address_components.length; i++)
        {
            for (var j=0; j < results[0].address_components[i].types.length; j++) {
                if (results[0].address_components[i].types[j] == "country") {
                    var shortname = results[0].address_components[i].short_name;
                    var longname = results[0].address_components[i].long_name;
                }

                if(results[0].address_components[i].types[j] == "locality") {
                    var cityName =  results[0].address_components[i].long_name;
                }
            }
            
            //var type = results[0].address_components[i].types;
            
        }

        var city = document.getElementById("html-city");
        city.innerHTML = cityName;
        var country = document.getElementById("html-country");
        country.innerHTML = longname;
        var countryCode = document.getElementById("html-country-code");
        countryCode.innerHTML = shortname;
    }

});

</script>

@endsection