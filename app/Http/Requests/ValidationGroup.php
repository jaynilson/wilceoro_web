<?php

namespace App\Http\Requests;

use App\Rules\RuleDuplicateGroup;
use Illuminate\Foundation\Http\FormRequest;

class ValidationGroup extends FormRequest
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
        
            return [
                'title' => [new RuleDuplicateGroup($this->input('title'),$this->input('id')),'required','max:150','unique:group,title,'.$this->input('title')],
            ];
          
    }


  

   public function attributes(){

       return [
           'title' => 'título'
       ];
   
      }

}
