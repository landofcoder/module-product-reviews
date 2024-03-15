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

namespace Hgati\ProductReviews\Api\Data;

/**
 * Represents a ReviewVote object
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface VerifyInterface
{
    const ORDER_ID = 'order_id';
    const EMAIL = 'email';
    const SKU = 'sku';

    /**
     * Get order_id
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Set order_id
     *
     * @param string $order_id
     *
     * @return $this
     */
    public function setOrderId($order_id);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku);
}
