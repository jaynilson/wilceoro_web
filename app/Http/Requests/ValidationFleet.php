<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationFleet extends FormRequest
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
                    'n' => 'required|unique:fleet,id,'.$this->route('id').'|max:200',
                    'model' => 'required|max:200',
                    'type' => 'required|max:200',
                    'licence_plate' => 'required|max:200',
                    'year' => 'required|max:200',
                    'yard_location' => 'required|max:200',
                    'department' => 'required|max:200',
                    'status' => 'required',
                    'picture_upload' => 'image|max:20480' //kilobytes 10240 = 10mb
                ];
        
          }else if($this->input('id')){
      
            return [
                'n' => 'required|unique:fleet,id,'.$this->input('id').'|max:200',
                'model' => 'required|max:200',
                'type' => 'required|max:200',
                'licence_plate' => 'required|max:200',
                'year' => 'required|max:200',
                'yard_location' => 'required|max:200',
                'department' => 'required|max:200',
                'status' => 'required',
                'picture_upload' => 'image|max:20480' //kilobytes 10240 = 10mb
            ];
    
        
          }else{// store
       
           
            return [
                'n' => 'required|unique:fleet,id|max:200',
                'model' => 'required|max:200',
                'type' => 'required|max:200',
                'licence_plate' => 'required|max:200',
                'year' => 'required|max:200',
                'yard_location' => 'required|max:200',
                'department' => 'required|max:200',
                'status' => 'required',
                'picture_upload' => 'required|image|max:20480' //kilobytes 10240 = 10mb
            ];
          }
    }


  
    public function messages()
    {
        return [
            'picture_upload.image' => 'The photo of the item must be in image format such as: jpg, png.',
            'picture_upload.max' => 'The items photo cannot be larger than 20 mb.',
        ];
    }

   public function attributes(){

       return [
           'n' => 'n',
           'model' => 'model', 
           'licence_plate' => 'licence plate',
           'year' => 'year',
           'yard_location' => 'yard location',
           'department' => 'department',
           'picture_upload' => 'picture item',
           'status'=>'status',
       ];
   
      }

}
