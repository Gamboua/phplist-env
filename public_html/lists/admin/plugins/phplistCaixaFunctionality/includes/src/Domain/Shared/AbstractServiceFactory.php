<?php

namespace phplist\Caixa\Functionality\Domain\Shared;

use phplist\Caixa\Functionality\Domain\DummyImportGeneratorService;
use phplist\Caixa\Functionality\Domain\MessageDataService;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestmentRepository;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ImportClientsFundRepository;
use phplist\Caixa\Functionality\Domain\UserImportService;
use phplist\Caixa\Functionality\Domain\UserMessageService;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CaixaDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ClientInvestmentDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ImportFundsDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\PHPListDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class AbstractServiceFactory
 *
 * @package phplist\Caixa\Functionality\Domain\Shared
 */
abstract class AbstractServiceFactory
{
    public static function get($clazz)
    {
        static $factories;

        if (!isset($factories)) {
            $factories = [
                MessageDataService::class => function () {
                    $phpList = new PHPList();
                    return new MessageDataService($phpList);
                },
                UserMessageService::class => function () {
                    $phpList = new PHPList();
                    $phpListDAO = AbstractDAOFactory::get(PHPListDAO::class);
                    return new UserMessageService($phpList, $phpListDAO);
                },
                DummyImportGeneratorService::class => function() {
                    $phpList = new PHPList();
                    $importFundsDAO = AbstractDAOFactory::get(ImportClientsFundRepository::class);
                    return new DummyImportGeneratorService($phpList, $importFundsDAO);
                },
                UserImportService::class => function () {
                    $phpListDAO = AbstractDAOFactory::get(PHPListDAO::class);
                    $caixaDAO = AbstractDAOFactory::get(CaixaDAO::class);
                    return new UserImportService($caixaDAO, $phpListDAO);
                },
            ];
        }

        $factory = null;
        if (array_key_exists($clazz, $factories)) {
            $factory = $factories[$clazz];
        }

        return is_callable($factory) ? $factory() : null;
    }
}
