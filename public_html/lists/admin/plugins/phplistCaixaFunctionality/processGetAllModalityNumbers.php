<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use phplist\Caixa\Functionality\Application\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Application\ImportService;

@ob_end_clean();

/** @var ImportService $importService */
$importService = AbstractServiceFactory::get(ImportService::class);
$modalityNumbers = $importService->getAllModalityNumbers();

foreach ($modalityNumbers as $modalityNumber) {
    echo "{$modalityNumber} ";
}

echo "\n";

exit;
