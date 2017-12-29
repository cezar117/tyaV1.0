<script src="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.js') }}" ></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/i18n/es.js') }}"></script>
<script>

function toggleDescripcion() {
    $(".readMinus").toggle();
    $(".readMore").toggle();
    if ($(".descripcionCard").hasClass("active")) {
        $(".descripcionCard").removeClass("active");
    } else {
        $(".descripcionCard").addClass("active");
    }


}

function initTwoInputs() {
    $('#two-inputs').dateRangePicker(
            {
                autoClose: true,
                startDate: new Date(),
                format: 'DD-MM-YYYY',
                minDays: 2,
                maxDays: 30,
                separator: ' al ',
                customTopBar: function(days, startTime, endTime)
                {
                    return days > 1 ? days - 1 + ' ' + ' noche(s)' : '';
                },
                hoveringTooltip: function(days, startTime, hoveringTime)
                {

                    if (days >= 2 && days < 3) {
                        $("#noches").html(days - 1 + ' ' + ' noche');
                        return days - 1 + ' ' + ' noche';

                    } else {
                        $("#noches").html(days - 1 + ' ' + ' noches');
                        return days - 1 + ' ' + ' noches';
                        ;
                    }

                },
                getValue: function()
                {
                    if ($('#checkIn').val() && $('#checkOut').val())
                        return $('#checkIn').val() + ' al ' + $('#checkOut').val();
                    else
                        return '';
                },
                setValue: function(s, s1, s2)
                {
                    $('#checkIn').val(s1);
                    $('#checkOut').val(s2);

                }
            });
}

function initFechaSalida() {
    $('.fechaSalida').dateRangePicker(
            {
                autoClose: true,
                singleDate: true,
                showShortcuts: true,
                singleMonth: true,
                format: 'DD-MM-YYYY',

                startDate: new Date()

            });
}

function initDestinos() {
    $(".selectDestino").select2({
        language: "es",
        placeholder: "Selecciona tu destino",

        ajax: {
            url: "{{ route('destinos')}}",
            method: 'POST',
            dataType: 'json',
            delay: 150,

            data: function(params) {
                return {
                    term: params.term,
                    _token: "{{ csrf_token() }}"
                };
            },
            processResults: function(data, params) {

                params.page = params.page || 1;

                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
}
</script>