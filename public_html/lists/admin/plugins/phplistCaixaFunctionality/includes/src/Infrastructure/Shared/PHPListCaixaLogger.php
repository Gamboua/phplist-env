<?php
namespace phplist\Caixa\Functionality\Infrastructure\Shared;

use Monolog\Logger;

class PHPListCaixaLogger
{

    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function generateLogImport($newUsers, $updatedUsers, $newUsersNoEmail, $updatedUsersNoEmail, $timeUsed)
    {
        $this->logTimeUsed($timeUsed);
        $this->logImportNewUsers($newUsers);
        $this->logImportUpdateUsers($updatedUsers);
        $this->logImportNewUsersNoEmail($newUsersNoEmail);
        $this->logImportUpdatedUsersNoEmail($updatedUsersNoEmail);
    }

    protected function logImportNewUsers($total)
    {
        $this->logger->addInfo('Total new Imported: ' . $total);
    }

    protected function logImportUpdateUsers($total)
    {
        $this->logger->addInfo('Total Updated: ' . $total);
    }

    protected function logImportNewUsersNoEmail($total)
    {
        $this->logger->addInfo('Total new Imported Without Email: ' . $total);
    }

    protected function logImportUpdatedUsersNoEmail($total)
    {
        $this->logger->addInfo('Total Updated Without Email: ' . $total);
    }

    protected function logTimeUsed($timeUsed)
    {
        $this->logger->addInfo('Time Used: ' . $timeUsed);
    }
    
    public function generateLogException(\Exception $err)
    {
        $this->logger->addDebug('Excpetion: ' . $err->getMessage());
    }
    
    public function generateLogReadingTimeView($readingTime)
    {
        $this->logger->addInfo('Reading Time View: ' . $readingTime);
    }
}
