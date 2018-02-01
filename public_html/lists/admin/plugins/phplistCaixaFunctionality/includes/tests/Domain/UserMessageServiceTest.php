<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Infrastructure\DB\DAO\PHPListDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;
use PHPUnit\Framework\TestCase;

/**
 * Class UserMessageServiceTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class UserMessageServiceTest extends TestCase
{
    public function testMaybeSendToNotViewed()
    {
        $phpList = $this->getMockBuilder(PHPList::class)
            ->setMethods(['loadMessageData'])
            ->getMock();

        $phpListDAO = $this->getMockBuilder(PHPListDAO::class)
            ->disableOriginalConstructor()
            ->setMethods(['deleteUserMessageByNotViewed'])
            ->getMock();

        // first
        $phpList->expects($this->at(0))
            ->method('loadMessageData')
            ->with($this->equalTo(25))
            ->willReturn(['alwaysSendToNotViewed' => true]);

        $phpListDAO->expects($this->at(0))
            ->method('deleteUserMessageByNotViewed')
            ->with($this->equalTo(25));

        // second
        $phpList->expects($this->at(1))
            ->method('loadMessageData')
            ->with($this->equalTo(25))
            ->willReturn(['alwaysSendToNotViewed' => 'on']);

        $phpListDAO->expects($this->at(1))
            ->method('deleteUserMessageByNotViewed')
            ->with($this->equalTo(25));

        // assert
        $userMessageService = new UserMessageService($phpList, $phpListDAO);
        $userMessageService->maybeSendToNotViewed(25);
        $userMessageService->maybeSendToNotViewed(25);
    }

    public function testMaybeSendToNotViewedWhenExpectedNeverChangeTheCurrentValue()
    {
        $phpList = $this->getMockBuilder(PHPList::class)
            ->setMethods(['loadMessageData'])
            ->getMock();

        $phpListDAO = $this->getMockBuilder(PHPListDAO::class)
            ->disableOriginalConstructor()
            ->setMethods(['deleteUserMessageByNotViewed'])
            ->getMock();

        // first
        $phpList->expects($this->at(0))
            ->method('loadMessageData')
            ->with($this->equalTo(25))
            ->willReturn([]);

        // second
        $phpList->expects($this->at(1))
            ->method('loadMessageData')
            ->with($this->equalTo(25))
            ->willReturn(['alwaysSendToNotViewed' => false]);

        // never be touched by service
        $phpListDAO->expects($this->never())
            ->method('deleteUserMessageByNotViewed');

        $userMessageService = new UserMessageService($phpList, $phpListDAO);
        $userMessageService->maybeSendToNotViewed(25);
        $userMessageService->maybeSendToNotViewed(25);
    }
}
