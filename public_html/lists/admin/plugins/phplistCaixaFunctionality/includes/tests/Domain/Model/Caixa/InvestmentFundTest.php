<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestmentFundTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class InvestmentFundTest extends TestCase
{
    public function testFromArray()
    {
        $investmentFund = InvestmentFund::fromArray([
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 5930,
        ]);

        $this->assertEquals('2015-08-01', $investmentFund->getReferenceDate());
        $this->assertEquals(1941, $investmentFund->getAgencyNumber());
        $this->assertEquals(1, $investmentFund->getOperationNumber());
        $this->assertEquals(747681, $investmentFund->getAccountNumber());
        $this->assertEquals('agency@agency.com.br', $investmentFund->getAgencyEmail());
        $this->assertEquals(5930, $investmentFund->getModalityNumber());
    }
}
