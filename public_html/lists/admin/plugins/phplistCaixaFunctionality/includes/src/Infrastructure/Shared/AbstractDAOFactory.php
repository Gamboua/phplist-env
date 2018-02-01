<?php
namespace phplist\Caixa\Functionality\Infrastructure\Shared;

use phplist\Caixa\Functionality\Domain\Model\AnalyticalReportRepository;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestmentRepository;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ImportClientsFundRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignRepository;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use phplist\Caixa\Functionality\Domain\Model\ConsolidateReportRepository;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use phplist\Caixa\Functionality\Domain\Model\UserAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\UserRepository;
use phplist\Caixa\Functionality\Domain\Model\UserSubscriptionRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\AnalyticalReportDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CaixaDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CampaignCallAttributeDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CampaignCallDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CampaignCallListDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CampaignDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ClientInvestmentDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ClientInvestmentLogDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ConsolidateReportDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ImportFundsDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\InvestmentFundSubscriptionDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\PHPListDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\SubscriptionListDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\UserAttributeDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\UserDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\UserSubscriptionDAO;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Class AbstractDAOFactory
 *
 * @package phplist\Caixa\Functionality\Infrastructure\Shared
 */
abstract class AbstractDAOFactory
{

    public static function get($clazz)
    {
        static $factories;

        if (!isset($factories)) {
            $factories = [
                CaixaDAO::class => function () {
                    return new CaixaDAO(Connection::fromCaixa());
                },

                ClientInvestmentRepository::class => function () {
                    $logger = new Logger('log_import_reading time');
                    $logger->pushHandler(new StreamHandler('/var/log/log_reading_time.log', Logger::DEBUG));
                    $logger->pushHandler(new FirePHPHandler());
                    $loggerPHPListCaixa = new PHPListCaixaLogger($logger);
                    $clientInvestmentDao = new ClientInvestmentDAO(Connection::fromCaixa()); 
                    $clientInvestmentDao->setPhpListCaixaLogger($loggerPHPListCaixa);
                    return $clientInvestmentDao;
                },

                ClientInvestmentLogRepository::class => function () {
                    return new ClientInvestmentLogDAO(Connection::fromPHPList());
                },

                InvestmentFundSubscriptionRepository::class => function () {
                    return new InvestmentFundSubscriptionDAO(Connection::fromPHPList());
                },

                PHPListDAO::class => function () {
                    return new PHPListDAO(Connection::fromPHPList());
                },

                ImportClientsFundRepository::class => function () {
                    return new ImportFundsDAO(Connection::fromCaixa());
                },

                SubscriptionListRepository::class => function () {
                    $subscriptionListDAO = new SubscriptionListDAO(Connection::fromPHPList());
                    $subscriptionListDAO->setPhpList(new PHPList());
                    return $subscriptionListDAO;
                },

                UserAttributeRepository::class => function () {
                    return new UserAttributeDAO(Connection::fromPHPList());
                },

                UserRepository::class => function () {
                    $userDAO = new UserDAO(Connection::fromPHPList());
                    $userDAO->setPhpList(new PHPList());
                    return $userDAO;
                },

                UserSubscriptionRepository::class => function () {
                    $userSubscriptionDAO = new UserSubscriptionDAO(Connection::fromPHPList());
                    $userSubscriptionDAO->setPhpList(new PHPList());
                    return $userSubscriptionDAO;
                },

                AnalyticalReportRepository::class => function () {
                    $dao = new AnalyticalReportDAO(Connection::fromPHPList());
                    $dao->setPhpList(new PHPList());
                    return $dao;
                },

                ConsolidateReportRepository::class => function () {
                    $dao = new ConsolidateReportDAO(Connection::fromPHPList());
                    $dao->setPhpList(new PHPList());
                    return $dao;
                },

                CampaignCallRepository::class => function () {
                    $campaignCallDAO = new CampaignCallDAO(Connection::fromPHPList());
                    $campaignCallDAO->setPhpList(new PHPList());
                    return $campaignCallDAO;
                },

                CampaignCallAttributeRepository::class => function () {
                    $campaignCallAttributeDAO = new CampaignCallAttributeDAO(Connection::fromPHPList());
                    $campaignCallAttributeDAO->setPhpList(new PHPList());
                    return $campaignCallAttributeDAO;
                },

                CampaignCallListRepository::class => function () {
                    $campaignCallListDAO = new CampaignCallListDAO(Connection::fromPHPList());
                    $campaignCallListDAO->setPhpList(new PHPList());
                    return $campaignCallListDAO;
                },

                CampaignRepository::class => function () {
                    $campaignDAO = new CampaignDAO(Connection::fromPHPList());
                    $campaignDAO->setPhpList(new PHPList());
                    return $campaignDAO;
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
