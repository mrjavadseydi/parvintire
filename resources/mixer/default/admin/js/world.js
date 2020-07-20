$(document).ready(function () {

    if ($('.world-select2').length > 0) {
        $('.world-select2').select2({
            closeOnSelect: true,
            dir: "rtl",
            height: '300px'
        });
    }

    if ($('#world-province-select').length > 0) {

        $(document).on('change', '#world-province-select', function () {

            var id   = $(this).val();
            var _url = '/api/world/city/' + id;

            $('#world-city-select, #world-town-select').html('<option value="">انتخاب کنید</option>').trigger('change');

            if (id != '') {

                $.ajax({
                    url: _url,
                    type: 'GET',
                    success: function (response, status) {

                        if (response.status === 'success') {
                            var options = "<option value=''>انتخاب کنید</option>";
                            $.each( response.result, function( i, item ) {
                                options += "<option latitude='"+item.latitude+"' longitude='"+item.longitude+"' value='"+item.id+"'>"+item.name+"</option>";
                            });
                            $('#world-city-select').html('').append(options).trigger('change');
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
                        var callback = $(this).attr('callback');
                        if (callback == '') {
                            alert('دوباره سعی کنید!');
                        } else {
                            result = [];
                            result['status']  = 'error';
                            result['message'] = 'دوباره سعی کنید!';
                            eval(callback)(result);
                        }
                    }
                });

            }

            setMapLocation(
                $(this).find('option:selected').attr('latitude'),
                $(this).find('option:selected').attr('longitude'),
                8
            );

        });

    }

    if ($('#world-city-select').length > 0) {

        $(document).on('change', '#world-city-select', function () {

            var id = $(this).val();

            if (id != '') {

                var _url = '/api/world/region/' + id;

                $('select[find="town_id"]').html('<option value="">انتخاب کنید</option>').trigger('change');

                $.ajax({
                    url: _url,
                    type: 'GET',
                    success: function (response, status) {

                        if (response.status === 'success') {
                            var options = "<option value=''>انتخاب کنید</option>";
                            $.each( response.result, function( i, item ) {
                                options += "<option latitude='"+item.latitude+"' longitude='"+item.longitude+"' value='"+item.id+"'>"+item.name+"</option>";
                            });
                            $('#world-town-select').html('').append(options).trigger('change');
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
                        var callback = $(this).attr('callback');
                        if (callback == '') {
                            alert('دوباره سعی کنید!');
                        } else {
                            result = [];
                            result['status']  = 'error';
                            result['message'] = 'دوباره سعی کنید!';
                            eval(callback)(result);
                        }
                    }
                });

            }

            setMapLocation(
                $(this).find('option:selected').attr('latitude'),
                $(this).find('option:selected').attr('longitude'),
                12
            );

        });

    }

    if ($('#world-town-select').length > 0) {

        $(document).on('change', '#world-town-select', function () {
            setMapLocation(
                $(this).find('option:selected').attr('latitude'),
                $(this).find('option:selected').attr('longitude'),
                14
            );
        });

    }

});

function setMapLocation(latitude, longitude, zoom) {
    if ($('input[name="mapLocation"]').length > 0) {
        if (latitude != undefined) {
            var latlng = {
                lat: parseFloat(latitude),
                lng: parseFloat(longitude)
            };
            map.setView(latlng, zoom);
        }
    }
}
