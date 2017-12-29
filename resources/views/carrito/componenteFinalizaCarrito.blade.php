
@push('scripts')
<script>
$(document).ready(function() {

});

</script>
@endpush

<div class="">
    <h5>Finalizar</h5>
    {{-- <form action="{{ route('carrito.finaliza') }}" method="POST" name="form" id="form"> --}}
        <div class="form-group form-float">
            <div class="form-line">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">

           <h4>TOTAL : $</h4>
                <div class="input-field col s12">
                    <input class="btn right" value="Finalizar" type="submit">
                </div>

            </div>

        </div>

    </form>
</div>