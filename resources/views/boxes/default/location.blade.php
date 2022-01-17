<div class="box box-purple">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

                @php
                    $location = $post->location();

                    $world = [
                        'divClass' => 'col-md-4',
                        'map'   => true
                    ];

                    $mapZoom = 5;
                    $latitude  = 32.27406254744859;
                    $longitude = 53.75866242865163;
                    if (!empty($location)) {
                        $world['provinceId'] = $location->province_id;
                        $world['cityId']     = $location->city_id;
                        $world['townId']     = $location->town_id;
                        $latitude            = $location->latitude;
                        $longitude           = $location->longitude;
                        $mapZoom             = 14;
                    }
                @endphp

                <div class="row">
                    @include('boxes.default.world', $world)
                </div>


                <div class="col-12 mt10">
                    <div class="input-group">
                        <label>آدرس</label>
                        <textarea style="height: 42px;" class="w100" name="address">{{ (!empty($location) ? $location->address : '') }}</textarea>
                    </div>
                </div>

            </div>

            <div class="col-md-12 map">

                <div id="map" style="width: 100%; height:400px; z-index: 0;"></div>

                <script>

                    var map = L.map('map', {
                        center: [{{ $latitude }}, {{ $longitude }}],
                        zoom: {{ $mapZoom }},
                        zoomControl: true,
                        scrollWheelZoom: false
                    });

                    var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(map);

                    var greenIcon = L.icon({
                        iconUrl: '{{ asset('default/images/marker.png') }}',
                        shadowUrl: '',
                        iconSize:     [32, 50], // size of the icon
                        shadowSize:   [32, 50], // size of the shadow
                        iconAnchor:   [19, 54], // point of the icon which will correspond to marker's location
                        shadowAnchor: [4, 62],  // the same for the shadow
                        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                    });

                    marker = L.marker([{{ $latitude }}, {{ $longitude }}], {icon: greenIcon, draggable: true}).addTo( map )
                    .on('mouseover',function(e) {
                        e.target.openPopup();
                    }).on('mouseout',function(e) {
                        e.target.closePopup();
                    }).on('drag',function(e) {
                        var lat = e.latlng.lat;
                        var lng = e.latlng.lng;
                        $('input[name="latitude"]').val(lat);
                        $('input[name="longitude"]').val(lng);
                        cacheDistance();
                    });

                    function onMapClick(e) {
                        var lat = e.latlng.lat;
                        var lng = e.latlng.lng;
                        var newLatLng = new L.LatLng(lat, lng);
                        marker.setLatLng(newLatLng);
                        $('input[name="latitude"]').val(lat);
                        $('input[name="longitude"]').val(lng);
                        cacheDistance();
                    }

                    map.on('click', onMapClick);

                    $(document).ready(function () {

                        $('input[name="latitude"], input[name="longitude"]').keyup(function () {
                            var latlng = {
                                lat: parseFloat($('input[name="latitude"]').val()),
                                lng: parseFloat($('input[name="longitude"]').val())
                            };
                            marker.setLatLng(latlng);
                            map.panTo(latlng);
                            cacheDistance();
                        });

                    });

                    function cacheDistance() {
                        if ($('input[name=cacheDistance]').length == 0) {
                            $('.map').append('<input type="hidden" name="cacheDistance" value="true">');
                        }
                    }

                </script>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="">طول جغرافیایی (longitude)</label>
                            <input class="ltr" type="text" name="longitude" value="{{ $longitude }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="">عرض جغرافیایی (latitude)</label>
                            <input class="ltr" type="text" name="latitude" value="{{ $latitude }}">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="row">

                    <div class="col-12 mt15">
                        <p>لطفا در نقشه نقاط را طوری انتخاب کنید که روی مسیر باشند. درصورت ثبت نشدن مسافت ها نقطه انتخابی را اصلاح و مجددا پست را در وضعیت (انتشار) ذخیره کنید.</p>
                    </div>

                    @foreach($post->worldDistances($location) as $distance)
                        <div class="col-md-4 tac">
                            <div class="alert alert-purple">
                                <span>{{ $distance }}</span>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>

</div>
