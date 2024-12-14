<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Recordatorio;

class MailController extends Controller
{
    public function enviarCorreo()
    {
        // Datos de prueba
        $nombre = 'Juan Pérez';
        $sucursal = 'Sucursal Centro';
        $fecha_hora = '2024-12-20 10:00 AM';

        // Enviar el correo
        Mail::to('recordatoriospp@gmail.com')->send(new Recordatorio($nombre, $sucursal, $fecha_hora));

        return response()->json(['mensaje' => 'Correo enviado exitosamente.']);
    }
}
