<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb;


/**
 * Interface ImportClientsFundRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb
 */
interface ImportClientsFundRepository
{

    /**
     * @param ClientInvestment
     */
    public function insertClientInvestment(ListTbClientInvestment $clientInvestment);

    /**
     * @param Client
     */
    public function insertClient(ListTbClient $client);

    /**
     * @param ClientEmail
     */
    public function insertClientEmail(ListTbClientEmail $clientEmail);

    public function getLastClientId();

    public function refreshMaterializedView();
}