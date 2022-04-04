<?php

namespace App\Http\Controllers;

use App\Services\ExtratoService;
use Illuminate\Http\Request;

class ExtratoController extends Controller
{
    public function cadastrar(Request $request, ExtratoService $extratoService){
        return $extratoService->cadastrarNovoExtrato($request->all());
    }
}
