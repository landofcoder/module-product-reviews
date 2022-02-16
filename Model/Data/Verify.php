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

namespace Lof\ProductReviews\Model\Data;

use Lof\ProductReviews\Api\Data\VerifyInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * @inheritdoc
 */
class Verify extends AbstractSimpleObject implements VerifyInterface
{

    /**
     * @inheritdoc
     */
    public function getOrderId()
    {
        return $this->_get(self::ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderId($order_id)
    {
        return $this->setData(self::ORDER_ID, $order_id);
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getSku()
    {
        return $this->_get(self::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }
}
