<?php

namespace App\Http\Controllers;

use App\Services\CobrancaService;
use App\Services\ContaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CobrancaController extends Controller
{
    public function store(Request $request, CobrancaService $cobrancaService)
    {
        $result = [];

        DB::beginTransaction();

        $data = $this->validate($request, [
            'credencial' => 'required|exists:contas,credencial'
        ]);

        $objConta = $this->getContaService()->get($data['credencial']);

        foreach ($request->cobrancas ?: [] as $rs) {
            try {
                $result[] = $cobrancaService->cadastrarNovaCobranca($objConta, [
                    'credencial' => $data['credencial']
                ] + $rs);
                DB::commit();
            } catch (\Illuminate\Validation\ValidationException $e) {
                $result[] = [
                    'status' => $e->status,
                    'message' => $e->errors(),
                    'data' => $rs,
                ];
            } catch (Exception $e) {
                $result[] = [
                    'status' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'data' => $rs,
                ];
                DB::rollBack();
            }
        }
        return response()->json($result, 201);
    }

    private function getContaService(): ContaService
    {
        return app(ContaService::class);
    }
}
