<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PJBank\Package\Models\Traits\SendQueue;
use PJBank\Package\Models\Traits\UuidGenerate;

class Extrato extends Model
{
    use UuidGenerate, SendQueue;

    const TIPO_COBRANCA = 0;
    const TIPO_SPLIT = 1;
    const TIPO_TARIFA_COBRANCA = 2;
    const TIPO_TARIFA_SPLIT = 3;

    public $fillable = [
        'credencial',
        'cobranca_id',
        'tipo',
        'valor_cobranca',
        'valor_pago',
        'valor_extrato',
        'data_pagamento',
        'data_creditobanco',
        'movimentacao',
        'data_creditocliente',
    ];
}
