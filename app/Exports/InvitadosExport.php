<?php

namespace App\Exports;

use App\Models\Conductor;
use App\Models\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvitadosExport implements FromCollection, WithHeadings
{
    protected $evento; //Protege la propiedad del evento.

    public function headings(): array
    {
        //Nombre del campos de las casillas.
        return [
            'Empresa',
            'CIF',
            'Nombre',
            'Apellidos',
            'Telefono',
            'Dni',
            'Email',
            'KAM',
            'Asistio'
        ];
    }

    public function __construct(Evento $evento)
    {
        $this->evento = $evento; //Guarda el evento para usarlo despues.
    }

    public function collection()
    {
        /***Recoge de cada evento el invitado y recorre con un bucle para que 
         * me pinte todos los que hay ahi , ya que espera un array. */
        return $this->evento->invitados->map(function ($invitado) {
            return [
                'empresa'    => $invitado->empresa,
                'cif'        => $invitado->cif,
                'nombre'     => $invitado->nombre,
                'apellidos'  => $invitado->apellido,
                'telefono'   =>  "\t" . $invitado->telefono,//Se coloca "\t" para que se veo el formato texto (numeros).
                'dni'        => "\t" . $invitado->dni,//Se coloca "\t" para que se veo el formato texto (numeros).
                'email'      => $invitado->email,
                'kam'        => $invitado->kam,
                'asistio'    => $invitado->asiste ? 'Si' : 'No' //Condicion si el invitado asiste o no asiste.
            ];
        });
    }
}
