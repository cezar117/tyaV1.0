<div class="">
    <form action="{{ route('hoteles.busqueda') }}" method="POST" name="form" id="form">
        <div class="form-group form-float">
            <div class="form-line">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <div class="row">
                    <div class="input-field col s12">
                        <div class="row">
                            <span>Destino</span></div> 
                        <div class="row">
                            <div class="col s12 @if($from === 'resultados') m12 @else m9 @endif divDestinos">
                                <select id="destino" name="destino" class="selectDestino browser-default @if ($errors->has('destinos')) invalid @endif" tabindex="-1" aria-hidden="true" style="width: 100%;" required>
                                    @isset($busqueda["destino"])
                                    <option value="{{ $busqueda["destino"][0]["id"] }}" selected>{{ $busqueda["destino"][0]["text"] }}<option>
                                        @endisset
                                </select>
                                @if ($errors->has('destino'))
                                @foreach ($errors->get('destino') as $error)
                                <label id="destino-error" class="error" for="destino">{{ $error }}</label>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12 @if($from === 'resultados') m12 l4 @else m3 @endif">
                    <div class="row">
                        <span>Habitaciones</span></div>
                    <div class="row">
                        <select class="browser-default form-control @if ($errors->has('habitaciones')) invalid @endif" name="habitaciones" id="habitaciones" required >
                            <option value="1" @isset($busqueda) @if($busqueda["habitaciones"]==1) selected @endif @endisset >1</option>
                            <option value="2" @isset($busqueda) @if($busqueda["habitaciones"]==2) selected @endif @endisset >2</option>
                            <option value="3" @isset($busqueda) @if($busqueda["habitaciones"]==3) selected @endif @endisset >3</option>
                            <option value="4" @isset($busqueda) @if($busqueda["habitaciones"]==4) selected @endif @endisset >4</option>

                        </select>
                        @if ($errors->has('habitaciones'))
                        @foreach ($errors->get('habitaciones') as $error)
                        <label id="habitaciones-error" class="error" for="adultos">{{ $error }}</label>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="habitaciones">
                    @isset($busqueda)
                    @if($busqueda["habitaciones"] == 1)
                    <div class="col s12 @if($from === 'resultados') m12 l4 @else m3 @endif">
                        <div class="row"><span for="adultos">Adultos +18</span></div>
                        <div class="row">
                            <select id="adultos" class="browser-default form-control @if ($errors->has('adultos')) invalid @endif" name="adultos[]" required>
                                <!--<option value="0">0</option>-->
                                <option value="1" @isset($busqueda) @if($busqueda["adultos"] ==1) selected @endif @endisset>1</option>
                                <option value="2" selected @isset($busqueda) @if($busqueda["adultos"] ==2) selected @endif @endisset>2</option>
                                <option value="3" @isset($busqueda) @if($busqueda["adultos"] ==3) selected @endif @endisset>3</option>
                                <option value="4" @isset($busqueda) @if($busqueda["adultos"] ==4) selected @endif @endisset>4</option>
                                <option value="5" @isset($busqueda) @if($busqueda["adultos"] ==5) selected @endif @endisset>5</option>
                                <option value="6" @isset($busqueda) @if($busqueda["adultos"] ==6) selected @endif @endisset>6</option>
                                <option value="7"@isset($busqueda) @if($busqueda["adultos"] ==7) selected @endif @endisset>7</option>
                                <option value="8" @isset($busqueda) @if($busqueda["adultos"] ==8) selected @endif @endisset>8</option>
                            </select>
                            @if ($errors->has('adultos'))
                            @foreach ($errors->get('adultos') as $error)
                            <label id="adultos-error" class="error" for="adultos">{{ $error }}</label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col s12 @if($from === 'resultados') m12 l4 @else m3 @endif">
                        <div class="row"><span>Niños(-18)</span></div>
                        <div class="row">
                            <select id="menores" class="browser-default menores form-control @if ($errors->has('menores')) invalid @endif" name="menores[]" required>
                                <option value="0" selected>0</option>
                                <!-- <option value="" disabled selected>0</option> -->
                                <option value="1" @isset($busqueda) @if($busqueda["menores"] ==1) selected @endif @endisset>1</option>
                                <option value="2" @isset($busqueda) @if($busqueda["menores"] ==2) selected @endif @endisset>2</option>
                                <option value="3" @isset($busqueda) @if($busqueda["menores"] ==3) selected @endif @endisset>3</option>
                                <option value="4" @isset($busqueda) @if($busqueda["menores"] ==4) selected @endif @endisset>4</option>
                                <option value="5" @isset($busqueda) @if($busqueda["menores"] ==5) selected @endif @endisset>5</option>
                                <option value="6" @isset($busqueda) @if($busqueda["menores"] ==6) selected @endif @endisset>6</option>
                            </select>
                            @if ($errors->has('menores'))
                            @foreach ($errors->get('menores') as $error)
                            <label id="menores-error" class="error" for="menores">{{ $error }}</label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @else
                    @php $countSelects=1; @endphp
                    @foreach(objetoParseArray($busqueda["adultos"]) as $key=>$adm)
                    @php $countSelects=1; @endphp
                    <div class="divHabitaciones divHabitacion{{ $key+1 }} col s12 m12"><fieldset><legend>Habitacion {{ $key+1 }}</legend>
                            <div class="col s12 m6">
                                <div class="row">
                                    <span for="adultos">Adultos +18</span>
                                </div>
                                <div class="row"><select id="adultos[]" class="browser-default form-control" name="adultos[]">
                                        <option value="1" @if($adm==1) selected @endif>1</option>
                                        <option value="2" @if(!isset($busqueda)) selected="" @endif @if($adm==2) selected @endif>2</option>
                                        <option value="3" @if($adm==3) selected @endif>3</option>
                                        <option value="4" @if($adm==4) selected @endif>4</option>
                                        <option value="5" @if($adm==5) selected @endif>5</option>
                                        <option value="6" @if($adm==6) selected @endif>6</option>
                                        <option value="7" @if($adm==7) selected @endif>7</option>
                                        <option value="8" @if($adm==8) selected @endif>8</option>
                                    </select></div></div>
                            <div class="col s12 m6">
                                <div class="row">
                                    <span>Niños(-18)</span>
                                </div><div class="row">
                                    <select id="menores{{ $key+1 }}" class="browser-default form-control menores{{ $key+1 }}" name="menores[]">
                                        <option value="0" @if($busqueda["menores"][$key]==0) selected @endif selected="">0</option>
                                        <option value="1" @if($busqueda["menores"][$key]==1) selected @endif>1</option>
                                        <option value="2" @if($busqueda["menores"][$key]==2) selected @endif>2</option>
                                        <option value="3" @if($busqueda["menores"][$key]==3) selected @endif>3</option>
                                        <option value="4" @if($busqueda["menores"][$key]==4) selected @endif>4</option>
                                        <option value="5" @if($busqueda["menores"][$key]==5) selected @endif>5</option>
                                        <option value="6" @if($busqueda["menores"][$key]==6) selected @endif>6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col s12 m12 edadesMenoresH">

                                <div class="edadesMenores{{$key+1}}">
                                    @foreach($busqueda["edadMenores"][$key] as $key2=>$edad)
                                    <div id="divM{{ $key+($key2+1) }}" class="col s4 m3">
                                        <div class="row" style="margin-bottom: 10px;">
                                            <span>Menor {{ ($key2+1) }}</span>
                                        </div>
                                        <select name="edades{{ $key+1 }}[]" class="validate browser-default form-control selectMenores{{ $key+1 }}" type="number" value="1" value="0" >
                                            <option value="0" @if($edad == 0) selected @endif>< 1</option>
                                            <option value="1" @if($edad == 1) selected @endif>1</option>
                                            <option value="2" @if($edad == 2) selected @endif>2</option>
                                            <option value="3" @if($edad == 3) selected @endif>3</option>
                                            <option value="4" @if($edad == 4) selected @endif>4</option>
                                            <option value="5" @if($edad == 5) selected @endif>5</option>
                                            <option value="6" @if($edad == 6) selected @endif>6</option>
                                            <option value="7" @if($edad == 7) selected @endif>7</option>
                                            <option value="8" @if($edad == 8) selected @endif>8</option>
                                            <option value="9" @if($edad == 9) selected @endif>9</option>
                                            <option value="10" @if($edad == 10) selected @endif>10</option>
                                            <option value="11" @if($edad == 11) selected @endif>11</option>
                                            <option value="12" @if($edad == 12) selected @endif>12</option>
                                        </select>
                                    </div>
                                    @php $countSelects++; @endphp
                                    @endforeach
                                </div>


                            </div>
                        </fieldset></div>
                    <script>$("#menores{{ $key+1 }}").on("change", function() {cMenoresH($(this).val(), {{ $key + 1 }}); });</script>
                    @endforeach

                    @endif
                    @else

                    @endisset
                    @empty($busqueda)
                    <div class="col s12 @if($from === 'resultados') m4 @else m3 @endif">
                        <div class="row"><span for="adultos">Adultos +18</span></div>
                        <div class="row">
                            <select id="adultos" class="browser-default form-control @if ($errors->has('adultos')) invalid @endif" name="adultos[]" required>
                                <!--<option value="0">0</option>-->
                                <option value="1" @isset($busqueda) @if($busqueda["adultos"] ==1) selected @endif @endisset>1</option>
                                <option value="2" selected @isset($busqueda) @if($busqueda["adultos"] ==2) selected @endif @endisset>2</option>
                                <option value="3" @isset($busqueda) @if($busqueda["adultos"] ==3) selected @endif @endisset>3</option>
                                <option value="4" @isset($busqueda) @if($busqueda["adultos"] ==4) selected @endif @endisset>4</option>
                                <option value="5" @isset($busqueda) @if($busqueda["adultos"] ==5) selected @endif @endisset>5</option>
                                <option value="6" @isset($busqueda) @if($busqueda["adultos"] ==6) selected @endif @endisset>6</option>
                                <option value="7"@isset($busqueda) @if($busqueda["adultos"] ==7) selected @endif @endisset>7</option>
                                <option value="8" @isset($busqueda) @if($busqueda["adultos"] ==8) selected @endif @endisset>8</option>
                            </select>
                            @if ($errors->has('adultos'))
                            @foreach ($errors->get('adultos') as $error)
                            <label id="adultos-error" class="error" for="adultos">{{ $error }}</label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col s12 @if($from === 'resultados') m4 @else m3 @endif">
                        <div class="row"><span>Niños(-18)</span></div>
                        <div class="row">
                            <select id="menores" class="browser-default menores form-control @if ($errors->has('menores')) invalid @endif" name="menores[]" required>
                                <option value="0" selected>0</option>
                                <!-- <option value="" disabled selected>0</option> -->
                                <option value="1" @isset($busqueda) @if($busqueda["menores"] ==1) selected @endif @endisset>1</option>
                                <option value="2" @isset($busqueda) @if($busqueda["menores"] ==2) selected @endif @endisset>2</option>
                                <option value="3" @isset($busqueda) @if($busqueda["menores"] ==3) selected @endif @endisset>3</option>
                                <option value="4" @isset($busqueda) @if($busqueda["menores"] ==4) selected @endif @endisset>4</option>
                                <option value="5" @isset($busqueda) @if($busqueda["menores"] ==5) selected @endif @endisset>5</option>
                                <option value="6" @isset($busqueda) @if($busqueda["menores"] ==6) selected @endif @endisset>6</option>
                            </select>
                            @if ($errors->has('menores'))
                            @foreach ($errors->get('menores') as $error)
                            <label id="menores-error" class="error" for="menores">{{ $error }}</label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @endempty

                </div>

                <div class="input-field col s12">
                    <div class="edadesMenores"></div>
                    <div class="row"></div>
                </div>

                <div id="two-inputs">


                    <div class="col s12 @if($from === 'resultados') m6 @else m6 l3 @endif ">
                        <div class="row"><span for="checkIn" class="active">CheckIn</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="material-icons prefix teal-text">today</i></div>
                                <input id="checkIn" type="text" placeholder="DD-MM-YYYY" class="browser-default form-control @if ($errors->has('checkIn')) invalid @endif" name="checkIn" required autocomplete="off" @isset($busqueda) value="{{ $busqueda["checkIn"] }}" @endisset @empty($busqueda) value="" @endempty>
                                       @if ($errors->has('checkIn'))
                                       @foreach ($errors->get('checkIn') as $error)
                                       <label id="checkIn-error" class="error" for="checkIn">{{ $error }}</label>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col s12 @if($from === 'resultados') m6 @else m6 l3 @endif">
                        <div class="row"><span for="checkOut" class="active">CheckOut</span></div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="material-icons prefix teal-text">today</i></div>
                                <input id="checkOut" type="text" placeholder="DD-MM-YYYY" class="browser-default form-control @if ($errors->has('checkOut')) invalid @endif"  name="checkOut" required autocomplete="off" @isset($busqueda) value="{{ $busqueda["checkOut"] }}" @endisset @empty($busqueda) value="" @endempty>
                                   @if ($errors->has('checkOut'))
                                   @foreach ($errors->get('checkOut') as $error)
                                   <label id="checkOut-error" class="error" for="checkOut">{{ $error }}</label>
                            @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div id="two-inputs">


                                    <div class="col s12 @if($from === 'resultados') m6 @else m6 l3 @endif ">
                                        <div class="row"><span for="checkIn" class="active">CheckIn</span></div>
                                        <i class="material-icons prefix iconosDate teal-text">today</i>

                                        <input id="checkIn" type="text" placeholder="DD-MM-YYYY" class="inputDates @if ($errors->has('checkIn')) invalid @endif" name="checkIn" required autocomplete="off" @isset($busqueda) value="{{ $busqueda["checkIn"] }}" @endisset @empty($busqueda) value="" @endempty>
                                               @if ($errors->has('checkIn'))
                                               @foreach ($errors->get('checkIn') as $error)
                                               <label id="checkIn-error" class="error" for="checkIn">{{ $error }}</label>
                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="col s12 @if($from === 'resultados') m6 @else m6 l3 @endif">
                                        <div class="row"><span for="checkOut" class="active">CheckOut</span></div>
                                        <i class="material-icons prefix iconosDate teal-text">today</i>
                                        <input id="checkOut" type="text" placeholder="DD-MM-YYYY" class="inputDates @if ($errors->has('checkOut')) invalid @endif"  name="checkOut" required autocomplete="off" @isset($busqueda) value="{{ $busqueda["checkOut"] }}" @endisset @empty($busqueda) value="" @endempty>
                                               @if ($errors->has('checkOut'))
                                               @foreach ($errors->get('checkOut') as $error)
                                               <label id="checkOut-error" class="error" for="checkOut">{{ $error }}</label>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>-->

                <div class="col s12 m12">
                    <span id="noches" class="fechas teal-text"></span>
                </div>
                <div class="input-field col s12 m">
                    <input id="boton" class="btn" value="Buscar" type="submit">
                </div>

            </div>

        </div>

    </form>
</div>
<div class="row"></div>