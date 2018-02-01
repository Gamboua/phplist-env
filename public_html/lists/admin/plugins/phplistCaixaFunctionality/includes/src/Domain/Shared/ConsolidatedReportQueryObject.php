<?php

namespace phplist\Caixa\Functionality\Domain\Shared;

/**
 * Class ConsolidatedReportQueryObject
 *
 * @package phplist\Caixa\Functionality\Domain\Shared
 */
final class ConsolidatedReportQueryObject
{
    /**
     * @var string
     */
    private $fundName;

    /**
     * @var string
     */
    private $fundGroup;

    /**
     * @var mixed
     */
    private $messageDateStarted;

    /**
     * @var mixed
     */
    private $messageDateFinished;

    /**
     * @var string
     */
    private $clientIdentifier;

    /**
     * @var string
     */
    private $communicationType;

    /**
     * ConsolidatedReportQueryObject constructor.
     */
    public function __construct()
    {
        $today = strtotime(date('Y-m-d H:i:s'));
        $early = strtotime(date('Y-m-d H:i:s', $today) . ' -1year');

        $this->messageDateStarted = date('Y-m-d H:i:s', $early);
        $this->messageDateFinished = date('Y-m-d H:i:s', $today);
    }

    /**
     * @return string
     */
    public function getFundName()
    {
        return $this->fundName;
    }

    /**
     * @param string $fundName
     */
    public function setFundName($fundName)
    {
        $this->fundName = $fundName;
    }

    /**
     * @return string
     */
    public function getFundGroup()
    {
        return $this->fundGroup;
    }

    /**
     * @param string $fundGroup
     */
    public function setFundGroup($fundGroup)
    {
        $this->fundGroup = $fundGroup;
    }

    /**
     * @return mixed
     */
    public function getMessageDateStarted()
    {
        return $this->messageDateStarted;
    }

    /**
     * @param mixed $messageDateStarted
     */
    public function setMessageDateStarted($messageDateStarted)
    {
        $this->messageDateStarted = $messageDateStarted;
    }

    /**
     * @return mixed
     */
    public function getMessageDateFinished()
    {
        return $this->messageDateFinished;
    }

    /**
     * @param mixed $messageDateFinished
     */
    public function setMessageDateFinished($messageDateFinished)
    {
        $this->messageDateFinished = $messageDateFinished;
    }

    /**
     * @return string
     */
    public function getClientIdentifier()
    {
        return $this->clientIdentifier;
    }

    /**
     * @param string $clientIdentifier
     */
    public function setClientIdentifier($clientIdentifier)
    {
        $this->clientIdentifier = $clientIdentifier;
    }

    /**
     * @return string
     */
    public function getCommunicationType()
    {
        return $this->communicationType;
    }

    /**
     * @param string $communicationType
     */
    public function setCommunicationType($communicationType)
    {
        $this->communicationType = $communicationType;
    }

    /**
     * Get array with better status names to be displayed within views.
     *
     * @return array
     */
    public static function betterStatusNames()
    {
        return [
            'sent' => 'Enviado',
            'not sent' => 'Não Enviado',
            'todo' => 'Preparando p/ Envio',
            'active' => 'Ativo',
            'excluded' => 'Excluído',
            'unconfirmed user' => 'Usuário não confirmado',
            'invalid email address' => 'Endereço de e-mail inválido',
            'submitted' => 'Submetido',
        ];
    }
}
