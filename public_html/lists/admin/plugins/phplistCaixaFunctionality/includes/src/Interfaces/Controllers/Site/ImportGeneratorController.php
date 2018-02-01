<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\Site;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use phplist\Caixa\Functionality\Domain\DummyImportGeneratorService;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClient;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbInvestmentFund;
use phplist\Caixa\Functionality\Domain\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;


/**
 * Class ImportGeneratorController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\Site
 */
class ImportGeneratorController extends AbstractController
{
    const QTD_USERS = 800;
    /** @var DummyImportGeneratorService $dummyService */
    private $dummyService = null;
    
    private $funds = [];
    
    private $clients = [];

    public function __invoke()
    {
        $this->dummyService = AbstractServiceFactory::get(DummyImportGeneratorService::class);

        $clients = $this->generateClients();
        $investmentFund = $this->generateInvestmentFunds();
        $this->dummyService->insertClientsAtFund($this->clients, $this->funds);
        $this->dummyService->refreshMaterializedView();
        echo('FIM');

    }

    private function generateClients()
    {
        $clients = [];
        $lastClientId = $this->dummyService->getLastClientId();

        for ($i = $lastClientId; $i <= $lastClientId+ImportGeneratorController::QTD_USERS; $i++) {
            $email = 'client' . $i . '@client.com.br';
            
            if($i > 100 && $i <= 130){
                $email = null;
            }
            
            $clients[] = ListTbClient::fromArray([
                'identifier' => $i,
                'email' => $email,
                'name' => 'client' . $i,
            ]);
        }
        
        $this->clients = $clients;
    }

    private function generateInvestmentFunds()
    {
        $now = date('Y-m-d H:i:s');
        
        for($x = 0; $x <= 20; $x++){
            $investmentFund = ListTbInvestmentFund::fromArray([
                'referenceDate' => $now,
                'agencyNumber' => 88 . $x,
                'operationNumber' => 10 . $x ,
                'accountNumber' => 5432 . $x,
                'agencyEmail' => 'email_' . $x . '@agencia.com',
                'modalityNumber' => 10 . $x,
                'modalityName' => 'Modalidade - ' . $x,
                'dtApplication' => $now,
                'dtExpiration' => $now,
                'vrBase' => '545454' . $x,
                'vrUpdated' => 34564 . $x,
                'certifiedNumber' => 4546 . $x,
                'tsUpdated' => $now
            ]);
            
            $this->funds[] = $investmentFund;
        }

    }


}
