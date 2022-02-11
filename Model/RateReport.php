<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ProductReviews
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\Data\ReportHistoryInterface;

class RateReport extends \Magento\Framework\Model\AbstractModel implements ReportHistoryInterface
{
    /**
     * ResourceModel RateReport
     */
    protected function _construct()
    {
        $this->_init(\Lof\ProductReviews\Model\ResourceModel\RateReport::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getReviewId()
    {
        return $this->getData(self::REVIEW_ID);
    }

    /**
     * @inheritDoc
     */
    public function setReviewId($reviewId)
    {
        return $this->setData(self::REVIEW_ID, $reviewId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getIpAddress()
    {
        return $this->getData(self::IP_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setIpAddress($ipAddress)
    {
        return $this->setData(self::IP_ADDRESS, $ipAddress);
    }

    /**
     * @inheritDoc
     */
    public function getBrowserData()
    {
        return $this->getData(self::BROWSER_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setBrowserData($browserData)
    {
        return $this->setData(self::BROWSER_DATA, $browserData);
    }

    /**
     * @inheritDoc
     */
    public function getRateType()
    {
        return $this->getData(self::RATE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setRateType($rateType)
    {
        return $this->setData(self::RATE_TYPE, $rateType);
    }

    /**
     * @inheritDoc
     */
    public function getReportType()
    {
        return $this->getData(self::REPORT_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setReportType($reportType)
    {
        return $this->setData(self::REPORT_TYPE, $reportType);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
