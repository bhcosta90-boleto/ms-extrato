<?php

namespace App\Services;

use App\Models\Extrato;
use Carbon\Carbon;
use PJBank\Package\Services\ValidateTrait;

final class ExtratoService
{
    use ValidateTrait;

    public function __construct(private Extrato $repository)
    {

    }

    public function cadastrarNovoExtrato($data)
    {
        $rules = [
            "uuid" => "required|uuid",
            "credencial" => "required",
            "data_pagamento" => "required|date",
            "valor" => "required|numeric",
            "valor_pago" => "required|numeric",
            "taxa_cobranca" => "required|numeric",
            "valor_transferencia" => "required|numeric",
        ];

        if($split = isset($data['splits']) && isset($data['splits']) && count($data['splits'])) {
            $rules += [
                'splits.*.tarifa' => "required|numeric",
                'splits.*.valor_split' => "required|numeric",
                'splits.*.valor_transferencia' => "required|numeric",
                'splits.*.credencial' => "required|string",
            ];
        }

        $data = $this->validate($data, $rules);

        $ret = [];

        $ret[] = $this->repository->create([
            'credencial' => $data['credencial'],
            'cobranca_id' => $data['uuid'],
            'tipo' => Extrato::TIPO_COBRANCA,
            'valor_cobranca' => $data['valor'],
            'valor_pago' => $data['valor_pago'],
            'valor_extrato' => $data['valor_transferencia'],
            'movimentacao' => 'credito',
            'data_pagamento' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
            'data_creditobanco' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
            'data_creditocliente' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
        ]);


        $ret[] = $this->repository->create([
            'cobranca_id' => $data['uuid'],
            'credencial' => $data['credencial'],
            'tipo' => Extrato::TIPO_TARIFA_COBRANCA,
            'valor_cobranca' => $data['valor'],
            'valor_pago' => $data['valor_pago'],
            'valor_extrato' => $data['taxa_cobranca'],
            'movimentacao' => 'debito',
            'data_pagamento' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
            'data_creditobanco' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
            'data_creditocliente' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
        ]);

        if($split) {
            foreach($data['splits'] as $rsSplit) {
                $ret[] = $this->repository->create([
                    'cobranca_id' => $data['uuid'],
                    'credencial' => $rsSplit['credencial'],
                    'tipo' => Extrato::TIPO_SPLIT,
                    'valor_cobranca' => $rsSplit['valor_split'],
                    'valor_pago' => $rsSplit['valor_split'],
                    'valor_extrato' => $rsSplit['valor_transferencia'],
                    'movimentacao' => 'credito',
                    'data_pagamento' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
                    'data_creditobanco' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
                    'data_creditocliente' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
                ]);

                $ret[] = $this->repository->create([
                    'cobranca_id' => $data['uuid'],
                    'credencial' => $rsSplit['credencial'],
                    'tipo' => Extrato::TIPO_TARIFA_SPLIT,
                    'valor_cobranca' => $rsSplit['valor_split'],
                    'valor_pago' => $rsSplit['valor_split'],
                    'valor_extrato' => $rsSplit['tarifa'],
                    'movimentacao' => 'debito',
                    'data_pagamento' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
                    'data_creditobanco' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
                    'data_creditocliente' => (new Carbon($data['data_pagamento']))->format('Y-m-d'),
                ]);
            }
        }

        return $ret;
    }
}
