<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationProfileUser extends FormRequest
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
            return [
                'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'user_name' => 'nullable|max:100|unique:user,user_name,'.$this->route('id'),
                'password' => 'nullable|min:5',
                'password_confirmation' => 'nullable|required_with:password|same:password|min:5',
              
                'tel' => 'max:100',
                'email' => 'required|email|max:100|unique:user,email,'.$this->route('id'),
                'address' => 'max:500',
                'sex' => 'max:255',
                'date_of_birth' => 'date_format:Y-m-d',
            
      
                'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
            ];
          }else if($this->input('id')){
            return [
                'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'user_name' => 'nullable|max:100|unique:user,user_name,'.$this->input('id'),
                'password' => 'nullable|min:5',
                'password_confirmation' => 'nullable|required_with:password|same:password|min:5',
              
                'tel' => 'max:100',
                'email' => 'required|email|max:100|unique:user,email,'.$this->input('id'),
                'address' => 'max:500',
                'sex' => 'max:255',
                'date_of_birth' => 'date_format:Y-m-d',
     
              
                'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
            ];
            
          }else{// store
            return [
                'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'user_name' => 'nullable|max:100|unique:user,user_name,'.$this->route('id'),
                'password' => 'required|min:5',
                'password_confirmation' => 'required|same:password|min:5',
               
                'tel' => 'max:100',
                'email' => 'required|email|max:100|unique:user,email,'.$this->route('id'),
                'address' => 'max:500',
                'sex' => 'max:255',
                'date_of_birth' => 'date_format:Y-m-d',
     
                'status' => 'required',
                
                'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
            ];
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
           'name' => 'nombre',
           'last_name' => 'apellidos',
           'user_name' => 'nombre de usuario',
           'tel' => 'teléfono',
           'address' => 'dirección',
           'sex' => 'sexo',
           'date_of_birth' => 'fecha de nacimiento',
           'picture_upload' => 'imagen de perfil',
           'password'=>'contraseña',
           'password_confirmation'=>'confirmar contraseña',
           'status'=>'estado de cuenta',
       ];
   
      }

}
