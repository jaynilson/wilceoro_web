<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRequestCategory extends FormRequest
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
                    'id' => 'required|unique:request_category,id,'.$this->route('id'),
                    'title' => 'required|max:200',
                    'type' => 'required|max:200',
                    'status' => 'required'
                ];
        
          }else if($this->input('id')){
      
            return [
                'id' => 'required|unique:request_category,id,'.$this->input('id'),
                'title' => 'required|max:200',
                'type' => 'required|max:200',
                'status' => 'required'
            ];
    
        
          }else{// store
       
           
            return [
                'title' => 'required|max:200',
                'type' => 'required|max:200',
                'status' => 'required'
            ];
          }
    }


  
  

   public function attributes(){

       return [
           'title' => 'title', 
           'type' => 'type',
           'status'=>'status'
       ];
   
      }

}
