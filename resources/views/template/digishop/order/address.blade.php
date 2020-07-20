@extends(includeTemplate('master'))
@section('title', 'سبدخرید | آدرس')
@section('content')

    <div class="container-fluid px-6 py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ url('cart') }}">سبد خرید</a></li>
                <li class="breadcrumb-item active" aria-current="page">آدرس تحویل سفارش</li>
            </ol>
        </nav>
        <div class="row cart bg-white border rounded">
            <script src="{{ asset('assets/admin/default/store.js') }}"></script>
            <form callback="cartAddressCallback" id="cart-address-form" action="{{ route('order.address') }}" method="post" class="carts p-3">
                <?php
                $address = $cart['address'];
                $cityId = $townId = $regionId = null;
                $cities = $towns = $regions = [];
                if ($address != null) {
                    $cityId = $address->city_id;
                    $townId = $address->town_id;
                    $regionId = $address->region_id;
                    if ($cityId != null) {
                        $cities = \LaraBase\World\models\City::where(['province_id' => $address->province_id, 'active_postage' => '1'])->get();
                    }
                    if ($townId != null) {
                        $towns = \LaraBase\World\models\Town::where(['city_id' => $cityId, 'active_postage' => '1'])->get();
                    }
                    if ($regionId != null) {
                        $regions = \LaraBase\World\models\Region::where(['town_id' => $townId, 'active_postage' => '1'])->get();
                    }
                }
                ?>
                @csrf
                <div class="shipping mb-2 row">
                    <div class="col-12">
                        <h5 class="d-inline-block">آدرس تحویل سفارش</h5>
                    </div>
{{--                    <div class="col-3 text-left">--}}
{{--                        <small class="font-weight-normal d-inline-block btn-sm btn-danger pointer">انتخاب آدرس</small>--}}
{{--                    </div>--}}
                </div>
                <div class="row">
                    <div class="provinceParent col-md-3">
                        <div class="input-group">
                            <label>استان</label>
                            <select id="provinceSelect" name="provinceId" style="width: 100%" class="cartSelect2 form-control rounded w-100">
                                <option value="">انتخاب کنید</option>
                                @foreach(getProvinces(['country_id' => 244, 'active_postage' => 1]) as $province)
                                    <option {{ selected($province->id, $address->province_id ?? '') }} value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="cityParent col-md-3 mt-3 mt-md-0 {{ $cityId != null ? '' : 'd-none' }}">
                        <div class="input-group">
                            <label>شهرستان</label>
                            <select id="citySelect" name="cityId" class="form-control rounded w-100">
                                <option value="">انتخاب کنید</option>
                                @foreach($cities as $city)
                                    <option {{ selected($city->id, $cityId ?? '') }} latitude="{{ $city->latitude }}" longitude="{{ $city->longitude }}" value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="townParent col-md-3 mt-3 mt-md-0 {{ $townId != null ? '' : 'd-none' }}">
                        <div class="input-group">
                            <label>شهر</label>
                            <select id="townSelect" name="townId" class="form-control rounded w-100">
                                <option value="">انتخاب کنید</option>
                                @foreach($towns as $town)
                                    <option {{ selected($town->id, $townId ?? '') }} latitude="{{ $town->latitude }}" longitude="{{ $town->longitude }}" value="{{ $town->id }}">{{ $town->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="regionParent col-md-3 mt-3 mt-md-0 {{ $regionId != null ? '' : 'd-none' }} mt-3">
                        <div class="input-group">
                            <label>محله</label>
                            <select id="regionSelect" name="regionId" class="form-control rounded w-100">
                                <option value="">انتخاب کنید</option>
                                @foreach($regions as $region)
                                    <option {{ selected($region->id, $regionId ?? '') }} latitude="{{ $region->latitude }}" longitude="{{ $region->longitude }}" value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 my-3">
                        <div class="input-group">
                            <label>نشانی پستی</label>
                            <input type="text" name="address" value="{{ $address->address ?? '' }}" class="form-control rounded w-100">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <label>کدپستی</label>
                            <input type="text" name="postalCode" value="{{ $address->postal_code ?? '' }}" class="form-control ltr text-left rounded w-100">
                        </div>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="input-group">
                            <label>نام و نام خانوادگی تحویل گیرنده</label>
                            <input type="text" name="nameFamily" value="{{ $address->name_family ?? '' }}" class="form-control rounded w-100">
                        </div>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="input-group">
                            <label>شماره موبایل</label>
                            <input type="text" name="mobile" value="{{ $address->mobile ?? '' }}" class="form-control rounded ltr text-left w-100">
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="input-group">
                            <label>موقعیت روی نقشه</label>
                        </div>
                        @php
                            $world = [
                                'divClass' => 'col-md-4',
                                'map'   => true
                            ];
                            $mapZoom = 5;
                            $latitude  = $address->latitude ?? 32.27406254744859;
                            $longitude = $address->longitude ?? 53.75866242865163;
                        @endphp
                        <div id="map" style="width: 100%; height:400px; z-index: 0; overflow: hidden;"></div>
                        <script>

                            var map = L.map('map', {
                                center: [{{ $latitude }}, {{ $longitude }}],
                                zoom: {{ $mapZoom }},
                                zoomControl: true,
                                scrollWheelZoom: false
                            });

                            var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(map);

                            var greenIcon = L.icon({
                                iconUrl: '{{ image('marker.png') }}',
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
                        <div class="row d-none">
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
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <a href="{{ url('cart') }}" class="btn btn-outline-warning px-4 py-2">بازگشت</a>
                    </div>
                    <div class="col-6 text-left">
                        <button class="btn btn-success px-4 py-2 btn-address">ثبت آدرس و پرداخت</button>
                    </div>
                </div>
            </form>
            <script>

                $('.cartSelect2').select2();

                worldAjax = false;

                $(document).on('change', '#provinceSelect', function () {

                    $('.cityParent, .townParent, .regionParent').addClass('d-none');

                    if ($(this).val() != '') {
                        $.ajax({
                            url: '{{ url('api/world/city') }}/' + $(this).val() + '?active_postage=1',
                            type: 'GET',
                            success: function (response, status) {

                                if (response.status === 'success') {
                                    var options = "<option value=''>انتخاب کنید</option>";
                                    $.each( response.result, function( i, item ) {
                                        options += "<option latitude='"+item.latitude+"' longitude='"+item.longitude+"' value='"+item.id+"'>"+item.name+"</option>";
                                    });
                                    worldAjax = false;
                                    $('#citySelect').html('').append(options).trigger('change');
                                    $('.cityParent').removeClass('d-none');
                                    worldAjax = true;
                                } else {
                                    var callback = $(this).attr('callback');
                                    if (callback == '') {
                                        alert('دوباره سعی کنید!');
                                    } else {
                                        eval(callback)(response);
                                    }
                                }

                            },
                            error: function (xhr, status, error) {
                                alert('دوباره سعی کنید');
                            }
                        });
                    }

                });

                $(document).on('change', '#citySelect', function () {

                    $('.townParent, .regionParent').addClass('d-none');

                    if ($(this).val() != '') {

                        setMapLocation($(this).find('option:selected').attr('latitude'), $(this).find('option:selected').attr('longitude'), 12);

                        if (worldAjax) {
                            $.ajax({
                                url: '{{ url('api/world/town') }}/' + $(this).val() + '?active_postage=1',
                                type: 'GET',
                                success: function (response, status) {

                                    if (response.status === 'success') {
                                        var options = "<option value=''>انتخاب کنید</option>";
                                        $.each(response.result, function (i, item) {
                                            options += "<option latitude='" + item.latitude + "' longitude='" + item.longitude + "' value='" + item.id + "'>" + item.name + "</option>";
                                        });
                                        if (response.result.length > 0) {
                                            worldAjax = false;
                                            $('#townSelect').html('').append(options).trigger('change');
                                            $('.townParent').removeClass('d-none');
                                            worldAjax = true;
                                        }
                                    } else {
                                        var callback = $(this).attr('callback');
                                        alert('دوباره سعی کنید!');
                                    }

                                },
                                error: function (xhr, status, error) {
                                    alert('دوباره سعی کنید');
                                }
                            });
                        }
                    }

                });

                $(document).on('change', '#townSelect', function () {

                    $('.regionParent').addClass('d-none');

                    if ($(this).val() != '') {
                        if (worldAjax) {
                            $.ajax({
                                url: '{{ url('api/world/region') }}/' + $(this).val() + '?active_postage=1',
                                type: 'GET',
                                success: function (response, status) {

                                    if (response.status === 'success') {
                                        var options = "<option value=''>انتخاب کنید</option>";
                                        $.each(response.result, function (i, item) {
                                            options += "<option latitude='" + item.latitude + "' longitude='" + item.longitude + "' value='" + item.id + "'>" + item.name + "</option>";
                                        });
                                        if (response.result.length > 0) {
                                            worldAjax = false;
                                            $('#regionSelect').html('').append(options).trigger('change');
                                            $('.regionParent').removeClass('d-none');
                                            worldAjax = true;
                                        }
                                    } else {
                                        var callback = $(this).attr('callback');
                                        alert('دوباره سعی کنید!');
                                    }

                                },
                                error: function (xhr, status, error) {
                                    alert('دوباره سعی کنید');
                                }
                            });
                        }
                    }

                });

                function setMapLocation(latitude, longitude, zoom) {
                    if (latitude != undefined) {
                        var latlng = {
                            lat: parseFloat(latitude),
                            lng: parseFloat(longitude)
                        };
                        marker.setLatLng(latlng);
                        map.setView(latlng, zoom);
                        map.panTo(latlng);
                    }
                }

                $(document).on('click', '.btn-address', function () {
                    $(this).text('لطفا صبر کنید...');
                    $('#cart-address-form').submit();
                });

                function cartAddressCallback(status, response) {
                    $('.btn-address').text('ثبت آدرس و پرداخت');
                    if (status == 'success') {
                        window.location = '{{ route('cart.payment') }}';
                    }
                }

            </script>
            <style>
                .select2-container {
                    border-radius: 0.25rem !important;
                    display: block;
                    height: calc(1.5em + 0.75rem + 2px);
                    padding: .375rem .75rem;
                    font-size: 1rem;
                    font-weight: 400;
                    line-height: 1.5;
                    color: #495057;
                    background-color: #fff;
                    background-clip: padding-box;
                    border: 1px solid #ced4da;
                    border-radius: .25rem;
                    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                    transition: border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
                    padding: 0 !important;
                }
                .select2-selection {
                    height: 100% !important;
                    border: none !important;
                    background: transparent !important;
                }
                .select2-selection__rendered {
                    line-height: 38px !important;
                }
                b[role=presentation] {
                    top: 17px !important;
                }
            </style>
        </div>
    </div>
@endsection
