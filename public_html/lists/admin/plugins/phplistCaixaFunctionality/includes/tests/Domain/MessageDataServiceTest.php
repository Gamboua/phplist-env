<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageDataServiceTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class MessageDataServiceTest extends TestCase
{
    public function testSetAlwaysSendToNotViewed()
    {
        $phpList = $this->getMockBuilder(PHPList::class)
            ->setMethods(['setMessageData'])
            ->getMock();

        // first
        $phpList->expects($this->at(0))
            ->method('setMessageData')
            ->with(25, 'alwaysSendToNotViewed', true);

        // second
        $phpList->expects($this->at(1))
            ->method('setMessageData')
            ->with(25, 'alwaysSendToNotViewed', true);

        // third
        $phpList->expects($this->at(2))
            ->method('setMessageData')
            ->with(25, 'alwaysSendToNotViewed', false);

        $messageDataService = new MessageDataService($phpList);
        $messageDataService->setAlwaysSendToNotViewed(25, true);
        $messageDataService->setAlwaysSendToNotViewed(25, 'on');
        $messageDataService->setAlwaysSendToNotViewed(25, false);
    }
}
