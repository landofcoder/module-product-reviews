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

namespace Lof\ProductReviews\Model\Gallery\Source;


class ReviewSortType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $groupList = array();

        $groupList[] = array(
            'label' => __('Default'),
            'value' => 'default'
        );

        $groupList[] = array(
            'label' => __('Helpful'),
            'value' => 'helpful'
        );

        $groupList[] = array(
            'label' => __('Average Rating Percent'),
            'value' => 'rating'
        );

        return $groupList;
    }
}
