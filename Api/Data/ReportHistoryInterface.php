<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api\Data;

interface ReportHistoryInterface
{

    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const BROWSER_DATA = 'browser_data';
    const REVIEW_ID = 'review_id';
    const IP_ADDRESS = 'ip_address';
    const REPORT_TYPE = 'report_type';
    const CREATED_AT = 'created_at';
    const RATE_TYPE = 'rate_type';

    /**
     * Get id
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     * @param int $id
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setId($id);

    /**
     * Get review_id
     * @return int|null
     */
    public function getReviewId();

    /**
     * Set review_id
     * @param int $reviewId
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setReviewId($reviewId);

    /**
     * Get customer_id
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param int $customerId
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get ip_address
     * @return string|null
     */
    public function getIpAddress();

    /**
     * Set ip_address
     * @param string $ipAddress
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setIpAddress($ipAddress);

    /**
     * Get browser_data
     * @return string|null
     */
    public function getBrowserData();

    /**
     * Set browser_data
     * @param string $browserData
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setBrowserData($browserData);

    /**
     * Get rate_type
     * @return string|null
     */
    public function getRateType();

    /**
     * Set rate_type
     * @param string $rateType
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setRateType($rateType);

    /**
     * Get report_type
     * @return string|null
     */
    public function getReportType();

    /**
     * Set report_type
     * @param string $reportType
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setReportType($reportType);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface
     */
    public function setCreatedAt($createdAt);
}

