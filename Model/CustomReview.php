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

class CustomReview extends \Magento\Framework\Model\AbstractModel
{

    protected function _construct()
    {
        $this->_init('Lof\ProductReviews\Model\ResourceModel\CustomReview');
    }

    public function addCountRating($reviewId) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $getConnection = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);

        $query = 'SELECT *, SUM(rate_vote.percent) AS sum, COUNT(*) AS count, SUM(rate_vote.percent)/COUNT(*) AS average';
        $query .= ' FROM rating_option_vote as rate_vote';
        $query .= ' WHERE review_id = '.(int)$reviewId;

        $item = $getConnection->getConnection()->fetchAll($query);
        return $item[0]['average'];
    }
}
