<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationTool extends FormRequest
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
                'id' => 'required|unique:tool,id,'.$this->route('id'),
                'title' => 'required|unique:tool,title,'.$this->route('id').'|max:250',
                'type' => 'required|max:200',
                'stock' => 'required|integer',
                'status' => 'required',
                'picture_upload' => 'image|max:20480' //kilobytes 10240 = 10mb
            ];
        }else if($this->input('id')){
            return [
                'id' => 'required|unique:tool,id,'.$this->input('id'),
                'title' => 'required|unique:tool,title,'.$this->input('id').'|max:200',
                'type' => 'required|max:200',
                'stock' => 'required|integer',
                'status' => 'required',
                'picture_upload' => 'image|max:20480' //kilobytes 10240 = 10mb
            ];
        }else{// store
            return [
                'title' => 'required|max:200|unique:tool,title',
                'type' => 'required|max:200',
                'stock' => 'required|integer',
                'status' => 'required',
                'picture_upload' => 'image|max:20480' //kilobytes 10240 = 10mb
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
           'title' => 'title', 
           'type' => 'type',
           'stock' => 'stock',
           'picture_upload' => 'picture item',
           'status'=>'status'
       ];
   
      }

}
