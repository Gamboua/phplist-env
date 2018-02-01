<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb;

/**
 * Class ClientInvestment
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class ListTbClientInvestment
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
    public function __construct(ListTbClient $client, ListTbInvestmentFund $investmentFund)
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
