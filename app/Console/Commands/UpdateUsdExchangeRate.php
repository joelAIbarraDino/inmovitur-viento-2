<?php

namespace App\Console\Commands;

use App\Models\Badge;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateUsdExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update-usd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta Banxico y guarda el tipo de cambio USD/MXN';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = config('services.banxico.token');

        $response = Http::get(
            'https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno',
            ['token' => $token]
        );

        if(!$response->successful()) {
            $this->error('Error consultando Banxico');
            return Command::FAILURE;
        }

        $data = $response->json();

        $dato = data_get($data, 'bmx.series.0.datos.0');

        if (! $dato || ! isset($dato['dato'])) {
            $this->error('No se pudo obtener el tipo de cambio');
            return Command::FAILURE;
        }

        Badge::create([
            'currency_origin' => 'USD',
            'currency_target' => 'MXN',
            'rate' => round((float) $dato['dato'], 4),
        ]);

        $this->info('Tipo de cambio actualizado correctamente');
        return Command::SUCCESS;
    }
}
