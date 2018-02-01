<?php

namespace phplist\Caixa\Functionality\Interfaces\Models;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;

/**
 * Class AnalyticalReport
 *
 * @package phplist\Caixa\Functionality\Interfaces\Models
 */
class AnalyticalReportModel
{
    private $pageRequest;
    private $orderBy;
    private $sort;
    private $messageDateStarted;
    private $messageDateFinished;

    private $category;
    private $fund;
    private $account;
    private $registration;
    private $email;
    private $attempts;
    private $status;

    /**
     * AnalyticalReportModel constructor.
     * @param $pageRequest
     * @param $orderBy
     * @param $sort
     * @param $category
     * @param $fund
     * @param $account
     * @param $registration
     * @param $email
     * @param $attempts
     * @param $status
     */
    public function __construct(PageRequest $pageRequest, $orderBy, $sort, $category, $fund, $account, $registration, $email, $attempts, $status)
    {
        $this->pageRequest = $pageRequest;
        $this->orderBy = $orderBy;
        $this->sort = $sort;
        $this->category = $category;
        $this->fund = $fund;
        $this->account = $account;
        $this->registration = $registration;
        $this->email = $email;
        $this->attempts = $attempts;
        $this->status = $status;


        $today = strtotime(date('Y-m-d H:i:s'));
        $early = strtotime(date('Y-m-d H:i:s', $today) . ' -1year');

        $this->messageDateStarted = date('Y-m-d H:i:s', $early);
        $this->messageDateFinished = date('Y-m-d H:i:s', $today);


    }


    /**
     * @return PageRequest
     */
    public function getPageRequest()
    {
        return $this->pageRequest;
    }

    /**
     * @param PageRequest $pageRequest
     */
    public function setPageRequest($pageRequest)
    {
        $this->pageRequest = $pageRequest;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return date
     */
    public function getMessageDateStarted()
    {
        return $this->messageDateStarted;
    }

    /**
     * @param date $messageDateStarted
     */
    public function setMessageDateStarted($messageDateStarted)
    {
        $this->messageDateStarted = $messageDateStarted;
    }

    /**
     * @return date
     */
    public function getMessageDateFinished()
    {
        return $this->messageDateFinished;
    }

    /**
     * @param date $messageDateFinished
     */
    public function setMessageDateFinished($messageDateFinished)
    {
        $this->messageDateFinished = $messageDateFinished;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param mixed $registration
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


//
//    /**
//     * AnalyticalReport constructor.
//     * @param $pageRequest
//     * @param $orderBy
//     * @param $sort
//     * @param $fromDate
//     * @param $untilDate
//     */
//    public function __construct($pageRequest, $orderBy, $sort, $fromDate, $untilDate)
//    {
//        $this->pageRequest = $pageRequest;
//        $this->orderBy = $orderBy;
//        $this->sort = $sort;
//        $this->fromDate = $fromDate;
//        $this->untilDate = $untilDate;
//
//
//        $today = strtotime(date('Y-m-d H:i:s'));
//        $early = strtotime(date('Y-m-d H:i:s', $today) . ' -1year');
//
//    }



}