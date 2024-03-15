<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api\Data;

interface RemindersInterface
{

    const EMAIL = 'email';
    const ORDER_ID = 'order_id';
    const CUSTOMER_ID = 'customer_id';
    const STATUS = 'status';
    const STORE_ID = 'store_id';
    const NAME = 'name';
    const ORDER_INCREMENT_ID = 'order_increment_id';
    const PRODUCT_ID = 'product_id';
    const CREATED_AT = 'created_at';
    const ID = 'id';

    /**
     * Get id
     * @return int|null
     */
    public function getId();

    /**
     * Set reminders_id
     * @param int $id
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setId($id);

    /**
     * Get order_id
     * @return int|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param int $orderId
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setOrderId($orderId);

    /**
     * Get order_increment_id
     * @return string|null
     */
    public function getOrderIncrementId();

    /**
     * Set order_increment_id
     * @param string $orderIncrementId
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setOrderIncrementId($orderIncrementId);

    /**
     * Get product_id
     * @return int|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param int $productId
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setProductId($productId);

    /**
     * Get customer_id
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param int $customerId
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setName($name);

    /**
     * Get email
     * @return string|null
     */
    public function getEmail();

    /**
     * Set email
     * @param string $email
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setEmail($email);

    /**
     * Get store_id
     * @return int|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param int $storeId
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setStoreId($storeId);

    /**
     * Get status
     * @return int|null
     */
    public function getStatus();

    /**
     * Set status
     * @param int $status
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setStatus($status);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Hgati\ProductReviews\Api\Data\RemindersInterface
     */
    public function setCreatedAt($createdAt);
}

