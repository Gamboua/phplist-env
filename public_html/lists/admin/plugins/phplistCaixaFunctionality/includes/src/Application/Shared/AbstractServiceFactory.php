<?php
namespace phplist\Caixa\Functionality\Application\Shared;

use phplist\Caixa\Functionality\Application\ImportService;
use phplist\Caixa\Functionality\Application\TemplateParserService;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestmentRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use phplist\Caixa\Functionality\Domain\Model\UserAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\UserRepository;
use phplist\Caixa\Functionality\Domain\Model\UserSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Service\CampaignCallAttributeTemplateParser;
use phplist\Caixa\Functionality\Domain\Service\ImportClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Service\ImportInvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Service\ImportSubscriptionList;
use phplist\Caixa\Functionality\Domain\Service\ImportUser;
use phplist\Caixa\Functionality\Domain\Service\ImportUserSubscription;
use phplist\Caixa\Functionality\Domain\Service\InvestmentFundSubscriptionTemplateParser;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPListCaixaLogger;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;

/**
 * Class AbstractServiceFactory
 *
 * @package phplist\Caixa\Functionality\Application\Shared
 */
abstract class AbstractServiceFactory
{

    public static function get($clazz)
    {
        static $factories;
        
        if (! isset($factories)) {
            $factories = [
                ImportService::class => function () {
                    
                    /** @var UserRepository $userRepository */
                    $userRepository = AbstractDAOFactory::get(UserRepository::class);
                    
                    /** @var UserAttributeRepository $userAttributeRepository */
                    $userAttributeRepository = AbstractDAOFactory::get(UserAttributeRepository::class);
                    
                    /** @var SubscriptionListRepository $subscriptionListRepository */
                    $subscriptionListRepository = AbstractDAOFactory::get(SubscriptionListRepository::class);
                    
                    /** @var UserSubscriptionRepository $userSubscriptionRepository */
                    $userSubscriptionRepository = AbstractDAOFactory::get(UserSubscriptionRepository::class);
                    
                    /** @var InvestmentFundSubscriptionRepository $investmentFundSubscriptionRepository */
                    $investmentFundSubscriptionRepository = AbstractDAOFactory::get(InvestmentFundSubscriptionRepository::class);
                    
                    /** @var ClientInvestmentRepository $clientInvestmentRepository */
                    $clientInvestmentRepository = AbstractDAOFactory::get(ClientInvestmentRepository::class);
                    
                    /** @var ClientInvestmentLogRepository $clientInvestmentLogRepository */
                    $clientInvestmentLogRepository = AbstractDAOFactory::get(ClientInvestmentLogRepository::class);
                    
                    $importUser = new ImportUser($userRepository, $userAttributeRepository);
                    $importSubscriptionList = new ImportSubscriptionList($subscriptionListRepository);
                    $importUserSubscription = new ImportUserSubscription($userSubscriptionRepository);
                    $importInvestmentFundSubscription = new ImportInvestmentFundSubscription($investmentFundSubscriptionRepository);
                    
                    $logger = new Logger('log_import');
                    $logger->pushHandler(new StreamHandler('/var/log/log_processimport.log', Logger::DEBUG));
                    $logger->pushHandler(new FirePHPHandler());
                    
                    $loggerPHPListCaixa = new PHPListCaixaLogger($logger);
                    
                    $importClientInvestmentLog = new ImportClientInvestmentLog($clientInvestmentLogRepository);
                    
                    return new ImportService($importUser, $importSubscriptionList, $importUserSubscription, $importInvestmentFundSubscription, $importClientInvestmentLog, $clientInvestmentRepository, Connection::fromPHPList(), $loggerPHPListCaixa);
                },
                TemplateParserService::class => function () {

                    $campaignCallAttributeDAO = AbstractDAOFactory::get(CampaignCallAttributeRepository::class);

                    $campaignCallListDAO = AbstractDAOFactory::get(CampaignCallListRepository::class);
                    $investmentFundSubscriptionDAO = AbstractDAOFactory::get(InvestmentFundSubscriptionRepository::class);
                    $userRepository = AbstractDAOFactory::get(UserRepository::class);                    
                    $investmentFundParser = new InvestmentFundSubscriptionTemplateParser($campaignCallListDAO, $investmentFundSubscriptionDAO, $userRepository);
                    $campaignCallAttributeParser = new CampaignCallAttributeTemplateParser($campaignCallListDAO, $campaignCallAttributeDAO);

                    return new TemplateParserService($investmentFundParser, $campaignCallAttributeParser);
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
