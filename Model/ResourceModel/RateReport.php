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

namespace Lof\ProductReviews\Model\ResourceModel;

class RateReport extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const LOF_RATE_REPORT_TABLE = 'lof_review_report_history';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::LOF_RATE_REPORT_TABLE, 'id');
    }
}
