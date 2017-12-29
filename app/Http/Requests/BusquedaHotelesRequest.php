<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusquedaHotelesRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            //
            "destino" => 'required',
            "habitaciones" => 'required|min:1|max:2',
            "adultos" => 'required',
            "menores" => 'required',
            "checkIn" => 'required|date',
            "checkOut" => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'destino.required' => 'El destino es requerido',
            'habitaciones.required' => 'Es necesario el numero de habitaciones',
            'habitaciones.min' => 'El numero de habitaciones debe ser mayor a 1.',
            'habitaciones.max' => 'El numero de habitaciones debe ser menor a :max',
            'checkIn.required' => 'Es necesario indicar la fecha de Check In',
            'checkOut.required' => 'Es necesario indicar la fecha de Check Out',
        ];
    }

}
