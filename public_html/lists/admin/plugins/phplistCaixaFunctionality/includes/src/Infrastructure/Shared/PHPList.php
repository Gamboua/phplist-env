<?php

namespace phplist\Caixa\Functionality\Infrastructure\Shared;

class PHPList
{
    public function setMessageData($messageId, $name, $value)
    {
        setMessageData($messageId, $name, $value);
    }

    public function loadMessageData($messageId)
    {
        return loadMessageData($messageId);
    }

    public function getEnteredNow()
    {
        return date('Y-m-d H:i:s');
    }

    public function getUniqid($table = '')
    {
        return getUniqid($table);
    }
}
