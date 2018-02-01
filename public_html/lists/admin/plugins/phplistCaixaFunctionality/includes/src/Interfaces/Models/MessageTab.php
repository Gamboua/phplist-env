<?php

namespace phplist\Caixa\Functionality\Interfaces\Models;

/**
 * Class MessageTab
 *
 * @package phplist\Caixa\Functionality\Interfaces\Models
 */
class MessageTab
{
    public $caixaTabSubmitted;
    public $alwaysSendToNotViewed;

    private function __construct()
    {
        $this->caixaTabSubmitted = false;
        $this->alwaysSendToNotViewed = false;
    }

    public static function isSubmitted(array $postData)
    {
        $submitted = isset($postData['caixaTabSubmitted']);
        $submitted = $submitted && 'yes' === $postData['caixaTabSubmitted'];

        return $submitted;
    }

    public static function fromMessage(array $messageData)
    {
        $messageTab = new MessageTab();
        $messageTab->alwaysSendToNotViewed = isset($messageData['alwaysSendToNotViewed']);
        $messageTab->alwaysSendToNotViewed = $messageTab->alwaysSendToNotViewed && $messageData['alwaysSendToNotViewed'];

        return $messageTab;
    }

    public static function fromPost(array $postData)
    {
        $messageTab = new MessageTab();
        $messageTab->caixaTabSubmitted = isset($postData['caixaTabSubmitted']);
        $messageTab->caixaTabSubmitted = $messageTab->caixaTabSubmitted && 'yes' === $postData['caixaTabSubmitted'];

        $messageTab->alwaysSendToNotViewed = isset($postData['alwaysSendToNotViewed']);
        $messageTab->alwaysSendToNotViewed = $messageTab->alwaysSendToNotViewed && $postData['alwaysSendToNotViewed'];

        return $messageTab;
    }
}
