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

use Lof\ProductReviews\Api\Data\DetailedSummaryInterface;

class CustomReview extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('lof_review_customize', 'review_customize_id');
    }

    /**
     * Save detailed summary for product
     *
     * @param DetailedSummaryInterface $detailedSummary
     * @param int $product_id
     * @return bool
     */
    public function saveDetailedSummaryForProduct(DetailedSummaryInterface $detailedSummary, $product_id = 0)
    {
        if($product_id && $detailedSummary) {
            $table = $this->getTable('review_entity_summary');

            $select = $this->getConnection()->select()->from(
                        ['cp' => $table]
                    )->where(
                        'cp.entity_pk_value = ?',
                        (int)$product_id
                    );

            $rows = $this->getConnection()->fetchAll($select);

            if($rows) {
                foreach ($rows as $_row) {
                    $data = [];
                    $data["rate_one"] = $detailedSummary->getOne();
                    $data["rate_two"] = $detailedSummary->getTwo();
                    $data["rate_three"] = $detailedSummary->getThree();
                    $data["rate_four"] = $detailedSummary->getFour();
                    $data["rate_five"] = $detailedSummary->getFive();

                    $this->getConnection()->update($table, $data, ['primary_id = ?' => (int)$_row['primary_id']]);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * get review summary
     *
     * @param int $productId
     * @param int $storeId
     * @return mixed|array
     */
    public function getReviewSummary(int $productId, int $storeId = 0)
    {
        $table = $this->getTable('review_entity_summary');
        $select = $this->getConnection()->select()->from(
            ['cp' => $table]
        )->where(
            'cp.entity_pk_value = ?',
            (int)$productId
        )->where(
            'cp.store_id = ?',
            (int)$storeId
        )->limit(1);

        $rows = $this->getConnection()->fetchAll($select);
        return $rows ? $rows[0] : [];
    }
}
