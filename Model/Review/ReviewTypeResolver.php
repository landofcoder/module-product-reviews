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
 * @copyright  Copyright (c) 2022 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Model\Review;

use Lof\ProductReviews\Api\Data\ReviewInterface;
use Magento\Store\Model\Store;

/**
 * Class ResolveReviewType
 */
class ReviewTypeResolver implements ReviewTypeResolverInterface
{
    /**
     * Retrieve review type
     *
     * @param \Magento\Review\Model\Review $productReview
     *
     * @return int
     */
    public function getReviewType($productReview): int
    {
        $customerId = $productReview->getCustomerId();

        if ($customerId) {
            return ReviewInterface::REVIEW_TYPE_CUSTOMER;
        }

        $storeId = (int)$productReview->getStoreId();

        if ($storeId === Store::DEFAULT_STORE_ID) {
            return ReviewInterface::REVIEW_TYPE_ADMIN;
        }

        return ReviewInterface::REVIEW_TYPE_GUEST;
    }
}
