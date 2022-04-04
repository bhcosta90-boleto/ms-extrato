<?php

namespace Tests\Http\Controller\Cobranca;

use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class ContaControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_cadastrar()
    {
        $this->seed();

        $response = $this->json('POST', 'cobrancas', [
            'credencial' => self::$storage['contas']['_credencial'],
            'cobrancas' => [
                [
                    'data_vencimento' => ($data = Carbon::now())->format('d/m/Y'),
                    'cliente_nome' => 'bruno costa'
                ]
            ]
        ]);

        $response->assertResponseStatus(201);

        $this->seeInDatabase('cobrancas', [
            'id' => 1,
            'banco_id' => self::$storage['bancos']['_principal'],
            'conta_id' => self::$storage['contas']['_credencial'],
            'data_vencimento' => $data->format('Y-m-d'),
            'cliente_nome' => 'bruno costa'
        ]);
    }
}
