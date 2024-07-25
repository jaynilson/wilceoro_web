<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Simple extends Mailable
{
    use Queueable, SerializesModels;

 
use Queueable, SerializesModels;
public $data;
/**
 * Create a new message instance.
 *
 * @return void
 */
public function __construct($data)
{
    $this->data = $data;
}

/**
 * Build the message.
 *
 * @return $this
 */
public function build()
{
    return $this->view("emails.simple")->with([$this->data])->subject($this->data['asunto']);
}

}