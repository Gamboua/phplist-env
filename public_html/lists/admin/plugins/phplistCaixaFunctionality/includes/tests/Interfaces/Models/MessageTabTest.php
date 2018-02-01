<?php

namespace phplist\Caixa\Functionality\Interfaces\Models;

use PHPUnit\Framework\TestCase;

/**
 * Class MessageTabTest
 *
 * @package phplist\Caixa\Functionality\Interfaces\Models
 */
class MessageTabTest extends TestCase
{
    public function testIsSubmitted()
    {
        $this->assertFalse(MessageTab::isSubmitted([]));
        $this->assertFalse(MessageTab::isSubmitted(['caixaTabSubmitted']));
        $this->assertFalse(MessageTab::isSubmitted(['caixaTabSubmitted' => 'no']));
        $this->assertTrue(MessageTab::isSubmitted(['caixaTabSubmitted' => 'yes']));
    }

    public function testFromMessage()
    {
        $messageTab = MessageTab::fromMessage(['alwaysSendToNotViewed' => true]);
        $this->assertTrue($messageTab->alwaysSendToNotViewed);
    }

    public function testFromPost()
    {
        $messageTab = MessageTab::fromPost([
            'caixaTabSubmitted' => 'yes',
            'alwaysSendToNotViewed' => true,
        ]);

        $this->assertTrue($messageTab->caixaTabSubmitted);
        $this->assertTrue($messageTab->alwaysSendToNotViewed);
    }

    public function testFromPostWhenCaixaTabSubmittedMustReturnFalse()
    {
        $messageTab = MessageTab::fromPost([]);
        $this->assertFalse($messageTab->caixaTabSubmitted);

        $messageTab = MessageTab::fromPost(['caixaTabSubmitted']);
        $this->assertFalse($messageTab->caixaTabSubmitted);

        $messageTab = MessageTab::fromPost(['caixaTabSubmitted' => 'no']);
        $this->assertFalse($messageTab->caixaTabSubmitted);
    }
}
