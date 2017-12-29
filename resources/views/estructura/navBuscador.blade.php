<div class="container-fluid navBuscador">
    <div class="row teal ">
        <div class="container white-text">
            <div class="col s6 l3">
                <span>DESTINO</span><br>
                
                <label>{{ $busqueda["destino"][0]["text"] }}</label>
            </div>
            <div class="col s6 l3">
                <span>FECHA</span><br>
                <label>{{ $busqueda["checkIn"]." - ".$busqueda["checkOut"] }}</label>
            </div>
            <div class="col s6 l3">
                <span>HABITACIONES</span><br>
                <label>{{ $busqueda["habitaciones"] }}</label>
            </div>
            <div class="col s6 l3">
                <div class="center">
                    <a class="cursosPointer"><i class="material-icons">edit</i> Cambiar busqueda</a>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="navBuscadorAnchor"></div>