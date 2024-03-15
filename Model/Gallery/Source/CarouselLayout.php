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

namespace Hgati\ProductReviews\Model\Gallery\Source;

class CarouselLayout implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return mixed|array
     */
    public function toOptionArray()
    {
        $groupList = [];
        $groupList[] = [
            'label' => __('Owl Carousel'),
            'value' => 'owl_carousel'
            ];

        $groupList[] = [
            'label' => __('Disabled Carousel - use listing'),
            'value' => 'disabled'
            ];
        return $groupList;
    }
}
