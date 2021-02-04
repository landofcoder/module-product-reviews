<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://www.venustheme.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ProductReviews
 * @copyright  Copyright (c) 2014 Landofcoder (https://landofcoder.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
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