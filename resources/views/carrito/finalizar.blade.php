@extends("estructura.app")

@section('contenido')

@push('scripts')
  <script>


   $("#error").delay("slow").fadeOut(5000);

</script>
  <script>
 $('#ccnum').payform('formatCardNumber');
    $('#expiry').payform('formatCardExpiry');
    $('#cvc').payform('formatCardCVC');
    // Format input for card number entry
    var cardNumber = $('#ccnum'),
        cvc = $('#cvc'),
        Boton = $('#enviar'),
        expiry = $('#expiry');


  </script>
<script>
  //   cardNumber.keyup(function() {

  //   if ($.payform.validateCardNumber(cardNumber.val()) == false) {
  //       cardNumberField.removeClass('has-success');
  //       cardNumberField.addClass('has-error');
  //   } else {
  //       cardNumberField.removeClass('has-error');
  //       cardNumberField.addClass('has-success');
  //   }

  //   if ($.payform.parseCardType(cardNumber.val()) == 'visa') {
  //       mastercard.addClass('transparent');
  //       amex.addClass('transparent');
  //   } else if ($.payform.parseCardType(cardNumber.val()) == 'amex') {
  //       mastercard.addClass('transparent');
  //       visa.addClass('transparent');
  //   } else if ($.payform.parseCardType(cardNumber.val()) == 'mastercard') {
  //       amex.addClass('transparent');
  //       visa.addClass('transparent');
  //   }


  // });


//    Boton.click(function(e) {
//     e.preventDefault();

//     var isCardValid = $.payform.validateCardNumber(cardNumber.val());
//     var isCvvValid = $.payform.validateCardCVC(cvc.val());

//     // if(owner.val().length < 5){
//     //     alert("Wrong owner name");
//     // } else 

//     if (!isCardValid) {
//         alert("incorrecta card number");
//     } else if (!isCvvValid) {
//         alert("incorrecto CVV");
//       }
//     // } else {
//     //     // Everything is correct. Add your form submission code here.
//     //     alert("Todo esta correcto");
//     // }
// }); 

$( "#form" ).submit(function( event ) {
  // event.preventDefault();

    // event.preventDefault();
    var expiryObj = $.payform.parseCardExpiry(expiry.val());
    var isCardValid = $.payform.validateCardNumber(cardNumber.val());
    var isCvvValid = $.payform.validateCardCVC(cvc.val());
    var isExpiryValid = $.payform.validateCardExpiry(expiryObj);
    // if(owner.val().length < 5){
    //     alert("Wrong owner name");
    // } else 
// alert(isExpiryValid);
    if (!isCardValid) {
        alert("ingrese un numero de targeta valido");
        // return false;
        event.preventDefault();
    } else if (!isCvvValid) {
        alert("incorrecto CVC");
        return false;
        event.preventDefault();
      }else if(!isExpiryValid){
        alert("Fecha de expiracion incorrecta");
        return false;
        event.preventDefault();

      }
    // } else {
    //     // Everything is correct. Add your form submission code here.
    //     alert("Todo esta correcto");
    // }
  // event.preventDefault();
});


</script>
@endpush

 @if(!empty($carrito))
{{-- {{dd($request)}}  --}}
{{-- {{dd($monto_total)}} --}}
{{-- {{dd($carrito)}} --}}

<div class="container">



<div class="row">
 @if(!empty($errores))
    <div class="row" id="error">
      <div class="col s12 m12">
        <div class="card-panel  red lighten-5">
          <span class="black-text">

         {{--  <h3>Mensaje Error PreAutorizacion : </h3> <br> --}}
          {{-- <span>mensaje :</span><br> --}}
          <h3>{{$errores->mensajes[0]->mensaje}}</h3><br>
          
          <span>Descripcion :</span>{{$errores->descripcion}} <br>
         <span>Codigo :</span>{{$errores->codigo}} 
          </span>
        </div>
      </div>
    </div>
@endif

</div>
<div class="row col s3 m4">
     

<div class="card-panel blue lighten-5" id="stiker" >
<div id="wizard_vertical" class="wizard clearfix vertical">
        <div class="steps clearfix">
          <ul role="tablist">
            <li role="tab" class="last done" aria-disabled="false" aria-selected="true"><a id="wizard_vertical-t-0" href="#wizard_vertical-h-0" aria-controls="wizard_vertical-p-0"><span class="current-info audible">current step: </span><span class="number">1.</span> Listado de Compras</a></li>

            <li role="tab" class="last done" aria-disabled="true"><a id="wizard_vertical-t-1" href="#wizard_vertical-h-1" aria-controls="wizard_vertical-p-1"><span class="number">2.</span> Completar Datos</a></li>
            
            <li role="tab" class="first current" aria-disabled="true"><a id="wizard_vertical-t-2" href="#wizard_vertical-h-2" aria-controls="wizard_vertical-p-2"><span class="number">3.</span> Forma de Pago</a></li>

           <li role="tab" class="disabled" aria-disabled="false" aria-selected="false"><a id="wizard_vertical-t-3" href="#wizard_vertical-h-3" aria-controls="wizard_vertical-p-3"><span class="number">4.</span> Compra Exitosa</a></li>

          </ul>
        </div>
      </div>

{{-- 

       

           <h5>TOTAL: <b>$<span id="total" class="grey lighten-4">{{obtenerTotalCarrito()}}
           </span class="grey lighten-4"></b>
           </h5>
           <!-- volar el input total y obtenerlo desde helper -->
        
      --}}
     <div class="row"></div>
  </div>

      
     
    </div>
           

      <div class="row">
      <div class="col s3 m8">
        <div class="card-panel cyan lighten-5">
        <div class="row"> 

            <form class="col s12" action="{{ route('carrito.PreAutorizar') }}" method="POST" name="form" id="form" autocomplete="on">
     <input name="_token" id="_token" value="{{ csrf_token() }}" type="hidden">
    <input type="hidden" name="token" 
    value="205c4bb1-b5ab-4b15-82b7-257fee9e5382">
    <input type="hidden" name="currency" value="MXN">
    <input type="hidden" name="language" value="ES-MX">
    <input type="hidden" name="monto_total" value="{{$monto_total}}">

      <div class="row">
        <div class="input-field col s6">
          <input id="first_name" name="first_name" type="text" class="validate">
          <label for="first_name" class=""><span class="blue-text"><i class="fa fa-user fa-lg" aria-hidden="true"></i></span> Nombre (s)</label>
        </div>
        <div class="input-field col s6">
          <input id="last_name" name="last_name"type="text" class="validate">
          <label for="last_name">Apellido (s)</label>
        </div>
       </div> 

       <div class="row">

    <div class="col s3" style="position: relative; padding-top: 25px;">
 <span class="blue-text darken-4"><i class="fa fa-cc-visa fa-2x" aria-hidden="true"></i> <i class="fa fa-cc-mastercard fa-2x" aria-hidden="true"></i> <i class="fa fa-cc-amex fa-2x" aria-hidden="true"></i></span>
</div>
        <div id="ccnum-field" class="input-field col s4">
    
          <input type="text" id="ccnum" name="ccnum" class="cc-number">
          <div class="errorTxt1"></div>
          <label for="ccnum" class="active"><span class="blue-text"><i class="fa fa-credit-card-alt fa-lg" aria-hidden="true"></i></span> Numero de Tarjeta</label>
        </div>
        <div class="input-field col s2">
          <input id="cvc" name="cvc" type="text" class="cc-cvc">
          <div class="errorTxt2"></div>
          <label for="cvc" class=""><span class="blue-text"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></span> CCV</label>
        </div>
        
        <div class="input-field col s2">
            <input id="expiry" name="expiry" type="text" class="validate">
            <div class="errorTxt3"></div>
            <label for="expiry" class=""><span class="blue-text"><i class="fa fa-calendar-o fa-lg" aria-hidden="true"></i></span> Expiracion</label>
    </div>
      </div>


      <div class="row">
        <div class="input-field col s3">
          <input id="email" type="email" name="email" class="validate">
          <label for="email" class=""><span class="blue-text"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></span> Email</label>
        </div>
        <div class="input-field col s9">
        <h4>Monto Total :<span id="total" class="grey lighten-4"><b>${{obtenerTotalCarrito()}}</b></span></h4>
        </div>
      </div>
      <div class="row">
  
        <div class="col s6">
          
          <input type="submit" class="btn right" name="enviar" id="enviar" value="Enviar">
        </div>
      </div>
    </form>

        </div>
              

         
        </div>
      </div>
    </div>

</div>
 @endif



    


  @endsection