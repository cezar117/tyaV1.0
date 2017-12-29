<div class="">
    <form action="{{ route('tours.busqueda') }}" method="POST">
        <div class="form-group form-float">
            <div class="form-line">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <div class="row">
                    <div class="input-field col s12">
                        <div class="row"><span>Destino</span></div>
                        <div class="col s12 @if($from === 'resultados') m12 @else m8 @endif divDestinos">
                            <select id="selectDestino" name="destino" class="selectDestino" style="width: 100%">
                                @isset($busqueda["destino"])
                                <option value="{{ $busqueda["destino"][0]["id"] }}" selected>{{ $busqueda["destino"][0]["text"] }}<option>
                                    @endisset
                            </select>
                        </div>

                    </div>
                </div>
                <div class="input-field col s6 @if($from === 'resultados') m6 @else m4 @endif">
                    <div class="row"><span for="adultos">Adultos +18</span></div>
                    <div class="row">
                        <select id="adultos" class="browser-default form-control" name="adultos" id="adultos">
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
                <div class="input-field col s6 @if($from === 'resultados') m6 @else m4 @endif">
                    <div class="row"><span>Niños(-18)</span></div>
                    <div class="row">
                        <select id="menores" class="browser-default menores form-control" name="menores">
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
                    <div class="edadesMenores"></div>
                    <div class="row"></div>
                </div>
                <div class="col s12 @if($from === 'resultados') m12 @else m8 @endif">
                    <div class="row"><span for="checkIn" class="active">Fecha inicio</span></div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons prefix teal-text">today</i></div>
                            <input id="checkIn" type="text" placeholder="DD-MM-YYYY" class="browser-default form-control fechaSalida" name="checkIn" autocomplete="off" required @isset($busqueda) value="{{ $busqueda["checkIn"] }}" @endisset @empty($busqueda) value="" @endempty >
                        </div>
                    </div>
                </div>
                <div class="input-field col s12">
                    <input class="btn" value="Buscar" type="submit">
                </div>

            </div>

        </div>
    </form>
</div>