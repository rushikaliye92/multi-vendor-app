<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest{
    public function authorize(){ 
        return auth()->check() && auth()->user()->role==='customer'; 
    }
    public function rules(){ 
        return ['quantity'=>'required|integer|min:1']; 
    }
}

?>