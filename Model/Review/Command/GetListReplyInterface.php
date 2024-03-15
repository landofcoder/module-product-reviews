<?php
/**
 * Hgati
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Hgati.com license that is
 * available through the world-wide-web at this URL:
 * https://hgati.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Hgati
 * @package    Hgati_ProductReviews
 * @copyright  Copyright (c) 2022 Hgati (https://hgati.com/)
 * @license    https://hgati.com/terms
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Model\Review\Command;

use Hgati\ProductReviews\Api\Data\ReplySearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Find Reviews by SearchCriteria command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial GetList call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \Hgati\ProductReviews\Api\ReviewRepositoryInterface
 * @api
 */
interface GetListReplyInterface
{
    /**
     * Find Sources by given SearchCriteria. SearchCriteria is not required because load all sources is useful case
     *
     * @param int $reviewId
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return ReplySearchResultInterface
     */
    public function execute(int $reviewId, SearchCriteriaInterface $searchCriteria = null): ReplySearchResultInterface;

    /**
     * Find Sources by given SearchCriteria. SearchCriteria is not required because load all sources is useful case for logged in customer
     *
     * @param int $customerId
     * @param int $reviewId
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return ReplySearchResultInterface
     */
    public function executeByCustomer(int $customerId, int $reviewId, SearchCriteriaInterface $searchCriteria = null): ReplySearchResultInterface;
}
