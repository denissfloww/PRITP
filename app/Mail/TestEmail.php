<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $tenders;
    private $okvad2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tenders, $okvad2)
    {
        $this->tenders = $tenders;
        $this->okvad2 = $okvad2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject = "Тендеры по коду ОКВЭД2 $this->okvad2";

        return $this->view('email', ['tenders' => $this->tenders, 'okvad2' => $this->okvad2]);
    }
}
