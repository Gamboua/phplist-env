<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentNoEmail;
use phplist\Caixa\Functionality\Domain\Model\UserNoEmailLog;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientInvestmentLogTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class ClientInvestmentLogTest extends TestCase
{
    public function testFromArray()
    {
        $clientInvestmentLog = ClientInvestmentLog::fromArray([
            'identifier' => '123456',
            'name' => 'the user name',
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 5930,
            'clientEmail' => null,
        ]);

        $this->assertEquals('123456', $clientInvestmentLog->getIdentifier());
        $this->assertEquals('the user name', $clientInvestmentLog->getName());
        $this->assertEquals('2015-08-01', $clientInvestmentLog->getReferenceDate());
        $this->assertEquals(1941, $clientInvestmentLog->getAgencyNumber());
        $this->assertEquals(1, $clientInvestmentLog->getOperationNumber());
        $this->assertEquals(747681, $clientInvestmentLog->getAccountNumber());
        $this->assertEquals('agency@agency.com.br', $clientInvestmentLog->getAgencyEmail());
        $this->assertEquals(5930, $clientInvestmentLog->getModalityNumber());
        $this->isNull($clientInvestmentLog->getClientEmail());
    }
}
