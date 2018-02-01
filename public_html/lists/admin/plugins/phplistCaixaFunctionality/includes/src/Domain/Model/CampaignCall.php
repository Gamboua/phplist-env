<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class CampaignCall
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class CampaignCall
{
    const TYPE_CONVOCATION = 'convocation';
    const TYPE_COMMUNICATION = 'communication';
    const TYPE_RELEVANT_FACT = 'relevant-fact';
    const TYPE_SUMMARY = 'summary';

    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $userEmail;

    /**
     * @var string
     */
    private $fromField;

    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $template;

    /**
     * @var string
     */
    private $communicationType;

    /**
     * @var mixed
     */
    private $embargo;

    /**
     * @var mixed
     */
    private $finishSending;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $templateContent;

    /**
     * CampaignCall constructor.
     */
    public function __construct()
    {
        $today = strtotime(date('Y-m-d H:i:s'));
        $later = strtotime(date('Y-m-d H:i:s', $today) . ' +1day');

        $this->embargo = date('Y-m-d H:i:s', $today);
        $this->finishSending = date('Y-m-d H:i:s', $later);
        $this->status = self::STATUS_DRAFT;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getFromField()
    {
        return $this->fromField;
    }

    /**
     * @param string $fromField
     */
    public function setFromField($fromField)
    {
        $this->fromField = $fromField;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param int $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
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
     * @return mixed
     */
    public function getEmbargo()
    {
        return $this->embargo;
    }

    /**
     * @param mixed $embargo
     */
    public function setEmbargo($embargo)
    {
        $this->embargo = $embargo;
    }

    /**
     * @return mixed
     */
    public function getFinishSending()
    {
        return $this->finishSending;
    }

    /**
     * @param mixed $finishSending
     */
    public function setFinishSending($finishSending)
    {
        $this->finishSending = $finishSending;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param array $properties
     *
     * @return CampaignCall
     */
    public static function fromArray(array $properties)
    {
        $campaignCall = new CampaignCall();
        $campaignCall->id = $properties['id'];
        $campaignCall->subject = $properties['subject'];
        $campaignCall->fromField = $properties['fromField'];
        $campaignCall->message = $properties['message'];
        $campaignCall->template = $properties['template'];
        $campaignCall->communicationType = $properties['communicationType'];
        $campaignCall->embargo = $properties['embargo'];
        $campaignCall->finishSending = $properties['finishSending'];
        $campaignCall->status = $properties['status'];

        return $campaignCall;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return string
     */
    public function getTemplateContent()
    {
        return $this->templateContent;
    }

    /**
     * @param string $templateContent
     */
    public function setTemplateContent($templateContent)
    {
        $this->templateContent = $templateContent;
    }


}