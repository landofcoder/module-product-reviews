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
declare(strict_types=1);

namespace Lof\ProductReviews\Api;

use Lof\ProductReviews\Api\Data\ReviewInterface;

/**
 * Post product reviews by sku
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface PostProductReviewsInterface
{
    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param string $sku
     * @param ReviewInterface $review
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface|mixed|array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(int $customerId, string $sku, ReviewInterface $review): ReviewInterface;
}
