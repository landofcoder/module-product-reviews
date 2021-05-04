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

class Gallery extends \Magento\Framework\Model\AbstractModel
{

    /**#@+
     * Gallery Status values
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Lof\ProductReviews\Model\ResourceModel\Gallery::class);
    }

    /**
     * Prepare gallery's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
