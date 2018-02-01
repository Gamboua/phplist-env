<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class Campaign
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class Campaign
{
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_INPROCESS = 'inprocess';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $fromField;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $status = 'draft';

    /**
     * @var integer
     */
    private $template;

    /**
     * @var mixed
     */
    private $embargo;

    /**
     * @var mixed
     */
    private $finishSending;

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
     * @param array $properties
     *
     * @return Campaign
     */
    public static function fromArray(array $properties)
    {
        $campaign = new Campaign();
        $campaign->id = $properties['id'];
        $campaign->subject = $properties['subject'];
        $campaign->fromField = $properties['fromField'];
        $campaign->message = $properties['message'];
        $campaign->status = $properties['status'];
        $campaign->template = $properties['template'];
        $campaign->embargo = $properties['embargo'];

        return $campaign;
    }
}
