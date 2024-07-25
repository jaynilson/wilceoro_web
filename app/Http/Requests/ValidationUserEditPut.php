<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationUserEditPut extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
          if($this->route('id')){//is edit
            if($this->input('id_rol')=="4"){
                return [
                    'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'pin' => 'nullable|min:5',
                    'tel' => 'max:100',
                    'email' => 'required|email|max:100|unique:user,email,'.$this->input('id'),
                    'address' => 'max:500',
                    'sex' => 'max:255',
                    'date_of_birth' => 'date_format:Y-m-d',
                    'department' => 'required',
                    'status' => 'required',
                  
                    'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
                ];
            }else{
                return [
                    'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
               
                    'password' => 'nullable|min:5',
                    'password_confirmation' => 'nullable|required_with:password|same:password|min:5',
                  
                    'tel' => 'max:100',
                    'email' => 'required|email|max:100|unique:user,email,'.$this->input('id'),
                    'address' => 'max:500',
                    'sex' => 'max:255',
                    'date_of_birth' => 'date_format:Y-m-d',
                    'department' => 'required',
                    'status' => 'required',
                  
                    'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
                ];
            }
          }else if($this->input('id')){
      
            if($this->input('id_rol')=="4"){
                return [
                    'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'pin' => 'nullable|min:5',
                    'tel' => 'max:100',
                    'email' => 'required|email|max:100|unique:user,email,'.$this->input('id'),
                    'address' => 'max:500',
                    'sex' => 'max:255',
                    'date_of_birth' => 'date_format:Y-m-d',
                    'department' => 'required',
                    'status' => 'required',
                  
                    'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
                ];
            }else{
                return [
                    'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
               
                    'password' => 'nullable|min:5',
                    'password_confirmation' => 'nullable|required_with:password|same:password|min:5',
                  
                    'tel' => 'max:100',
                    'email' => 'required|email|max:100|unique:user,email,'.$this->input('id'),
                    'address' => 'max:500',
                    'sex' => 'max:255',
                    'date_of_birth' => 'date_format:Y-m-d',
                    'department' => 'required',
                    'status' => 'required',
                  
                    'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
                ];
            }
          }else{// store
            if($this->input('id_rol')=="4"){
                return [
                    'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'pin' => 'required|min:5',
                    'tel' => 'max:100',
                    'email' => 'required|email|max:100|unique:user,email,'.$this->route('id'),
                    'address' => 'max:500',
                    'sex' => 'max:255',
                    'date_of_birth' => 'date_format:Y-m-d',
                    'department' => 'required',
                    'status' => 'required',
                    'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
                ];
            }else{
                return [
                    'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                    'password' => 'required|min:5',
                    'password_confirmation' => 'required|same:password|min:5',
                    'tel' => 'max:100',
                    'email' => 'required|email|max:100|unique:user,email,'.$this->route('id'),
                    'address' => 'max:500',
                    'sex' => 'max:255',
                    'date_of_birth' => 'date_format:Y-m-d',
                    'department' => 'required',
                    'status' => 'required',
                    'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
                ];
            }
         
          }
    }


  
    public function messages()
    {
        return [
            'picture_upload.image' => 'La foto del empleado debe ser en formato de imagen como: jpg, png.',
            'picture_upload.max' => 'La foto del empleado no puede ser mayor a 10 mb.',
        ];
    }

   public function attributes(){

       return [
           'name' => 'name',
           'last_name' => 'last name',
          
           'tel' => 'phone number',
           'address' => 'adddress',
           'sex' => 'sexo',
           'date_of_birth' => 'date of birth',
           'picture_upload' => 'picture profile',
           'password'=>'password',
           'password_confirmation'=>'repeat password',
           'department' => 'department',
           'yard_location' => 'yard_location',
           'status'=>'status account',
       ];
   
      }

}
