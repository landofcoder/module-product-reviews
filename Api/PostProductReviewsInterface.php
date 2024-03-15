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
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api;

use Hgati\ProductReviews\Api\Data\ReviewInterface;

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
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface|mixed|array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(int $customerId, string $sku, ReviewInterface $review): ReviewInterface;
}
