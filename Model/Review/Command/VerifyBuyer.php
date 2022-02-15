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

namespace Lof\ProductReviews\Model\Review\Command;

/**
 * Save review rating summary data command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial GetList call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \Lof\ProductReviews\Api\ReviewRepositoryInterface
 * @api
 */
class VerifyBuyer implements VerifyBuyerInterface
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    private $_order;

    /**
     * @var \Lof\ProductReviews\Helper\Data
     */
    private $helperData;

    /**
     * GetProductReviews constructor.
     *
     * @param \Lof\ProductReviews\Helper\Data $helperData
     * @param \Magento\Sales\Model\Order $order
     */
    public function __construct(
        \Lof\ProductReviews\Helper\Data $helperData,
        \Magento\Sales\Model\Order $order
    ) {
        $this->_order = $order;
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function execute(int $customer_id, string $customer_email, int $product_id, string $order_id = "" ): bool
    {
        $is_verified = false;

        if ($product_id) {
            if ($customer_id && $this->helperData->getAutoVerifyConfig()) {
                $is_verified = $this->helperData->getAutoVerified($product_id, $customer_id);
            } elseif ($customer_id && $order_id) {
                $order = $this->_order->loadByIncrementId($order_id);
                if (!empty($order) && $customer_id == $order->getCustomerId()) {
                    $allItems = $order->getAllVisibleItems();
                    $productIds = [];
                    foreach ($allItems as $item) {
                        $productIds[] = $item->getProductId();
                    }
                    if (in_array($product_id, $productIds)) {
                        $is_verified = true;
                    }
                }
            } elseif ($order_id && $customer_email) {
                $order = $this->_order->loadByIncrementId($order_id);
                if (!empty($order)) {
                    $email = $order->getCustomerEmail();
                    if ($email == $customer_email) {
                        $allItems = $order->getAllVisibleItems();
                        $productIds = [];
                        foreach ($allItems as $item) {
                            $productIds[] = $item->getProductId();
                        }
                        if (in_array($product_id, $productIds)) {
                            $is_verified = true;
                        }
                    }
                }
            }
        }

        return $is_verified;
    }
}
