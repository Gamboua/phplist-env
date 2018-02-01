<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\Site;

use phplist\Caixa\Functionality\Application\ImportService;
use phplist\Caixa\Functionality\Application\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;


/**
 * Class TestController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\Site
 */
class TestController extends AbstractController
{
    public function __invoke()
    {
        /** @var ImportService $importService */
        $importService = AbstractServiceFactory::get(ImportService::class);
        $modalityNumbers = $importService->getAllModalityNumbers();

        $table = new \WebblerListing('Nº do Fundo de Investimento');
        foreach ($modalityNumbers as $modalityNumber) {
            $table->addElement($modalityNumber);
        }

        $needle = '<div class="header"><h2>Nº do Fundo de Investimento</h2></div>';
        $replace = '<div class="header"><h2>Fundos de Investimento</h2></div>';
        echo str_replace($needle, $replace, $table->display());


        $inicio1 = microtime(true);
        // executar importacao
        foreach ($modalityNumbers as $modalityNumber) {
            $importService->importByModalityNumber($modalityNumber);
        }

        $total1 = microtime(true) - $inicio1;
        echo 'Tempo de execução do primeiro script: ' . $total1.'<br>';
        $memory = round(((memory_get_peak_usage(true) / 1024) / 1024), 2);
        echo($memory.' Mb');

        echo '<br>FIM';
    }
}
