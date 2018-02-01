<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class MessageDataService
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class MessageDataService
{
    private $phpList;

    public function __construct(PHPList $phpList)
    {
        $this->phpList = $phpList;
    }

    public function setAlwaysSendToNotViewed($messageId, $value)
    {
        $this->phpList->setMessageData($messageId, 'alwaysSendToNotViewed', boolval($value));
    }
}
