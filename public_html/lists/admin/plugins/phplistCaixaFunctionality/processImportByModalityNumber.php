<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use phplist\Caixa\Functionality\Application\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Application\ImportService;

@ob_end_clean();

global $cline;

/** @var ImportService $importService */
$importService = AbstractServiceFactory::get(ImportService::class);

if (isset($cline['n'])) {
    $modalityNumber = intval($cline['n']);
    $importService->importByModalityNumber($modalityNumber);
    echo "finalizado processo de importação [{$modalityNumber}]\n";
}

exit;
