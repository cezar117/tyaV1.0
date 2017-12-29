@push('stylesheets')
<!--<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />-->
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/s2-docs.css') }}" rel="stylesheet" type="text/css" />

@endpush
@push('scripts')
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.js') }}" ></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/i18n/es.js') }}"></script>


<script>
var edadesMenores;
var checkIn;
var checkOut;
$(document).ready(function() {

    $('#form').on('submit', function() {

        if ($('#adultos').val() == 0 || undefined || null) {
            alert('seleccione adultos');
            return false;
        }

    });




    edadesMenores = $('.collapsible').collapsible();

    $("#menores").on("change", function() {

        cMenores($(this).val());
    });


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
                    if (days > 1 && days < 2) {
                        $("#noches").html(days - 1 + ' ' + ' noche');
                        return days - 1 + ' ' + ' noche';

                    } else {
                        $("#noches").html(days - 1 + ' ' + ' noches')
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
                console.log(data);
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

});


$(".selectNormal").select2();
$(".selectMaterial").material_select();





function cMenores(menores) {

    var countSelects = $(".selectMenores").length;
    var html = '';

    if (menores > 0) {
        var rec = 0;
        if (countSelects > menores) {
            var x = parseInt(menores) + 1;
            for (x; x <= countSelects; x++) {
                $("#divM" + x).remove();
            }
        } else {
            if (countSelects === 0) {
                rec = menores;
            } else {
                rec = menores - countSelects;
            }
            for (var i = 1; i <= rec; i++) {
                countSelects++;
                html += '<div id="divM' + countSelects + '" class="col s4 m1"><div class="row" style="margin-bottom: 10px;"><span>Menor ' + countSelects + '</span></div><select name="edades[]" class="validate browser-default selectMenores" type="number" value="1" value="0" >';
                html += '<option value="0">< 1</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>';
                html += '<option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>';
                html += '</select></div>';
            }
            $(".edadesMenores").append(html);
        }

    } else {
        $(".edadesMenores").html("");
    }
}


</script>
@endpush

<div class="">

    <form action="{{ route('hoteles.busqueda') }}" method="POST" name="form" id="form">
        <div class="form-group form-float">
            <div class="form-line">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <div class="row">
                    <div class="input-field col s12">
                        <div class="row">
                            <label>Destino</label><br></div>
                        <div class="row">
                            <!--<input id="selectDestino" class="selectDestino" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="Selecciona tu destino" style="width: 814px;" type="search">-->
                            <select class="selectDestino browser-default" tabindex="-1" aria-hidden="true" style="width: 100%;"></select>
                            @isset($busqueda["destino"])
                            value="{{ $busqueda["destino"] }}"<option>
                                {{ $busqueda["destino"] }}</option>

                            @endisset
                            </select>
                        </div>
                    </div>
                </div>



                <div class="col s12 m4">
                    <div class="row">
                        <span>Habitaciones</span></div>
                    <div class="row">
                        <select class="browser-default" name="numHabitaciones" id="numHabitaciones" cl>
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="row"><span for="adultos">Adultos +18</span></div>
                    <div class="row">
                        <select id="adultos" class="browser-default" name="adultos" id="adultos">
                            <option value="0">0</option>
                            <option value="1" @isset($busqueda) @if($busqueda["adultos"] ==1) selected @endif @endisset>1</option>
                            <option value="2" selected @isset($busqueda) @if($busqueda["adultos"] ==2) selected @endif @endisset>2</option>
                            <option value="3" @isset($busqueda) @if($busqueda["adultos"] ==3) selected @endif @endisset>3</option>
                            <option value="4" @isset($busqueda) @if($busqueda["adultos"] ==4) selected @endif @endisset>4</option>
                            <option value="5" @isset($busqueda) @if($busqueda["adultos"] ==5) selected @endif @endisset>5</option>
                            <option value="6" @isset($busqueda) @if($busqueda["adultos"] ==6) selected @endif @endisset>6</option>
                            <option value="7"@isset($busqueda) @if($busqueda["adultos"] ==7) selected @endif @endisset>7</option>
                            <option value="8" @isset($busqueda) @if($busqueda["adultos"] ==8) selected @endif @endisset>8</option>
                        </select>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="row"><span>Niños(-18)</span></div>
                    <div class="row">
                        <select id="menores" class="browser-default" name="menores">
                            <option value="0" selected>0</option>
                            <!-- <option value="" disabled selected>0</option> -->
                            <option value="1" @isset($busqueda) @if($busqueda["menores"] ==1) selected @endif @endisset>1</option>
                            <option value="2" @isset($busqueda) @if($busqueda["menores"] ==2) selected @endif @endisset>2</option>
                            <option value="3" @isset($busqueda) @if($busqueda["menores"] ==3) selected @endif @endisset>3</option>
                            <option value="4" @isset($busqueda) @if($busqueda["menores"] ==4) selected @endif @endisset>4</option>
                            <option value="5" @isset($busqueda) @if($busqueda["menores"] ==5) selected @endif @endisset>5</option>
                            <option value="6" @isset($busqueda) @if($busqueda["menores"] ==6) selected @endif @endisset>6</option>
                        </select>

                    </div>
                </div>


                <div class="input-field col s12">
                    <div class="edadesMenores">

                    </div>


                </div>

                <div id="two-inputs">
                    <p id="noches"></p>
                    <div class="col s12 m6">
                        <div class="row"><span for="checkIn" class="active">CheckIn</span></div>
                        <input id="checkIn" type="text" class="inputDates" name="checkIn" autocomplete="off" @isset($busqueda) value="{{ $busqueda["checkIn"] }}" @endisset @empty($busqueda) value="" @endempty>

                    </div>
                    <div class="col s12 m6">
                        <div class="row"><span for="checkOut" class="active">CheckOut</span></div>
                        <input id="checkOut" type="text" class="inputDates" name="checkOut" autocomplete="off" @isset($busqueda) value="{{ $busqueda["checkOut"] }}" @endisset @empty($busqueda) value="" @endempty>

                    </div>
                </div>



                <div class="input-field col s12">
                    <input id="boton" class="btn right" value="Buscar" type="submit">
                </div>

            </div>

        </div>

    </form>
</div>
<div class="row"></div>