<?php

namespace App\Console\Commands;

use App\Services\ExtratoService;
use Illuminate\Console\Command;
use PJBank\Package\Services\ValidateTrait;
use PJBank\Package\Support\ConsumeSupport;

class SincronizacaoCobrancaPagaCommand extends Command
{
    use ValidateTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cobrancapaga:sincronizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronização das agências';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ConsumeSupport $consumeSupport)
    {
        $consumeSupport->service(
            'app.ms_cobrancas.table.cobrancas.cobrancapaga',
            ExtratoService::class,
            'cadastrarNovoExtrato'
        );
    }
}
