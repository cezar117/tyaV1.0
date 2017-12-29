function cMenores(menores,from) {
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
                if(from === "resultados"){
                    html += '<div id="divM' + countSelects + '" class="col s4 m4 l3"><div class="row" style="margin-bottom: 10px;"><span>Menor ' + countSelects + '</span></div><select name="edades1[]" class="validate browser-default selectMenores form-control" type="number" value="1" value="0" >';
                }else{
                    html += '<div id="divM' + countSelects + '" class="col s4 m4 l2"><div class="row" style="margin-bottom: 10px;"><span>Menor ' + countSelects + '</span></div><select name="edades1[]" class="validate browser-default selectMenores form-control" type="number" value="1" value="0" >';
                }
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

function cMenoresH(menores,hab,from) {
    var countSelects = $(".selectMenores"+hab).length;
    var html = '';
    if (menores > 0) {
        var rec = 0;
        if (countSelects > menores) {
            var x = parseInt(menores) + 1;
            for (x; x <= countSelects; x++) {
                $("#divM"+hab + x).remove();
            }
        } else {
            if (countSelects === 0) {
                rec = menores;
            } else {
                rec = menores - countSelects;
            }
            for (var i = 1; i <= rec; i++) {
                countSelects++;
                if(from === "resultados"){
                    html += '<div id="divM'+hab + countSelects + '" class="col s4 m4"><div class="row" style="margin-bottom: 10px;"><span>Menor ' + countSelects + '</span></div><select name="edades'+hab+'[]" class="validate browser-default selectMenores'+hab+' form-control" type="number" value="1" value="0" >';
                }
                else{
                    html += '<div id="divM'+hab + countSelects + '" class="col s4 m4 l2"><div class="row" style="margin-bottom: 10px;"><span>Menor ' + countSelects + '</span></div><select name="edades'+hab+'[]" class="validate browser-default selectMenores'+hab+' form-control" type="number" value="1" value="0" >';
                }
                
                html += '<option value="0">< 1</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>';
                html += '<option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>';
                html += '</select></div>';
            }
            $(".edadesMenores"+hab).append(html);
        }

    } else {
        $(".edadesMenores"+hab).html("");
    }
}

function cHabitaciones(habitaciones,from){
    var countDhabitaciones=$(".divHabitaciones").length;
    var html='';
    var rec=0;
    $('.habitaciones').html("");
    $('.edadesMenores').html("");
    if(habitaciones >0){
        if(habitaciones==1){
            if(from === "resultados"){
                html+='<div class="col s12 m6 l4"><div class="row"><span for="adultos">Adultos +18</span></div><div class="row"><select id="adultos" class="browser-default form-control" name="adultos">';
            }else{
                html+='<div class="col s12 m4 l3"><div class="row"><span for="adultos">Adultos +18</span></div><div class="row"><select id="adultos" class="browser-default form-control" name="adultos">';
            }
            
                html+='<option value="0">0</option><option value="1">1</option><option value="2" selected="">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>';
                html+='</select></div></div>';
                if(from === "resultados"){
                    html+='<div class="col s12 m6 l4"><div class="row"><span>Niños(-18)</span></div><div class="row"><select id="menores" class="browser-default menores" name="menores">';
                }else{
                    html+='<div class="col s12 m4 l3"><div class="row"><span>Niños(-18)</span></div><div class="row"><select id="menores" class="browser-default menores" name="menores">';
                }
                
                html+='<option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div></div>';
                html+='<script>$(".menores").on("change", function() {cMenores($(this).val(),"'+from+'");});</script>';
        }
        else{
//            $('.habitaciones').append(html);
            for (var i = 1; i <= habitaciones;i++) {
                html+='<div class="divHabitaciones divHabitacion'+i+' col s12 m12"><fieldset><legend>Habitacion '+i+'</legend>';
                if(from === "resultados"){
                    html+='<div class="col s12 m6"><div class="row"><span for="adultos">Adultos +18</span></div><div class="row"><select id="adultos[]" class="browser-default form-control" name="adultos[]">';
                }else{
                    html+='<div class="col s12 m6 l3"><div class="row"><span for="adultos">Adultos +18</span></div><div class="row"><select id="adultos[]" class="browser-default form-control" name="adultos[]">';
                }
                html+='<option value="0">0</option><option value="1">1</option><option value="2" selected="">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>';
                html+='</select></div></div>';
                if(from === "resultados"){
                    html+='<div class="col s12 m6"><div class="row"><span>Niños(-18)</span></div><div class="row"><select id="menores'+i+'" class="browser-default menores'+i+'" name="menores[]">';
                }else{
                    html+='<div class="col s12 m6 l3"><div class="row"><span>Niños(-18)</span></div><div class="row"><select id="menores'+i+'" class="browser-default menores'+i+'" name="menores[]">';
                }
                
                if(from === "resultados"){
                    html+='<option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div></div><div class="col s12 m12 edadesMenoresH"><div class="edadesMenores'+i+'"></div></div>';
                }else{
                    html+='<option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div></div><div class="col s12 m12 l6 edadesMenoresH"><div class="edadesMenores'+i+'"></div></div>';
                }
                
                html+='</fieldset></div>';
                html+='<script>$(".menores'+i+'").on("change", function() {cMenoresH($(this).val(),'+i+',"'+from+'");});</script>';
            }
            
        }
        $('.habitaciones').append(html);
    }
    
    
}

function detectChangeHm(valor,hab){
    
}

