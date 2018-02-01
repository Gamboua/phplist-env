<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa;

/**
 * Class ClientInvestment
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class ClientInvestment
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var InvestmentFund
     */
    private $investmentFund;

    /**
     * ClientInvestment constructor.
     *
     * @param Client $client
     * @param InvestmentFund $investmentFund
     */
    public function __construct(Client $client, InvestmentFund $investmentFund)
    {
        $this->client = $client;
        $this->investmentFund = $investmentFund;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return InvestmentFund
     */
    public function getInvestmentFund()
    {
        return $this->investmentFund;
    }
}
