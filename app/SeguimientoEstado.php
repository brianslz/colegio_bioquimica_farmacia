<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeguimientoEstado extends Model
{
    protected $table = 'seguimiento_estado';
    protected $primaryKey = 'id_seg';
    protected $fillable = [
        'id_afiliado','tipo_estado','fecha_inicio','fecha_fin'
    ];
    public $timestamps = false ; 
}