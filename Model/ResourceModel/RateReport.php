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

namespace Hgati\ProductReviews\Model\ResourceModel;

class RateReport extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const HGATI_RATE_REPORT_TABLE = 'hgati_review_report_history';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::HGATI_RATE_REPORT_TABLE, 'id');
    }
}
