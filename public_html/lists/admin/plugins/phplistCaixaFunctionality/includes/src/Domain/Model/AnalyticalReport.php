<?php
/**
 * Created by PhpStorm.
 * User: gustavo
 * Date: 10/01/18
 * Time: 11:04
 */

namespace phplist\Caixa\Functionality\Domain\Model;


class AnalyticalReport
{
    /**
     * @var string
     */
    private $group;

    /**
     * @var string
     */
    private $fund;

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $registration;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \date
     */
    private $dtSent;

    /**
     * @var int
     */
    private $attempts;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $messageId;

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getFund()
    {
        return $this->fund;
    }

    /**
     * @param string $fund
     */
    public function setFund($fund)
    {
        $this->fund = $fund;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param string $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param string $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return \date
     */
    public function getDtSent()
    {
        return $this->dtSent;
    }

    /**
     * @param \date $dtSent
     */
    public function setDtSent($dtSent)
    {
        $this->dtSent = $dtSent;
    }

    /**
     * @return int
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param int $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
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
     * @return int
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }



    /**
     * @param array $properties
     *
     * @return AnalyticalReport
     */
    public static function fromArray(array $properties)
    {
        $analyticalReport = new AnalyticalReport();
        $analyticalReport->group = $properties['group'];
        $analyticalReport->fund = $properties['fund'];
        $analyticalReport->account = $properties['account'];
        $analyticalReport->registration = $properties['registration'];
        $analyticalReport->email = $properties['email'];
        $analyticalReport->dtSent = $properties['dtSent'];
        $analyticalReport->attempts = $properties['attempts'];
        $analyticalReport->status = $properties['status'];
        $analyticalReport->message = $properties['message'];
        $analyticalReport->userId = $properties['userId'];
        $analyticalReport->messageId = $properties['messageId'];

        return $analyticalReport;
    }


}