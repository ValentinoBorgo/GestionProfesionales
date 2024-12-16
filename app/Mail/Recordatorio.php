<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Recordatorio extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $sucursal;
    public $fecha_hora;

    /**
     * Create a new message instance.
     *
     * @param string $nombre
     * @param string $sucursal
     * @param string $fecha_hora
     */
    public function __construct($nombre, $fecha_hora)
    {
        $this->nombre = $nombre;
        $this->fecha_hora = $fecha_hora;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recordatorio de turno')
                    ->view('mail.mail');
    }
}
