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

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * @var \Hgati\ProductReviews\Model\Gallery
     */
    protected $_reviewGallery;

    /**
     * Status constructor.
     * @param \Hgati\ProductReviews\Model\Gallery $reviewGallery
     */
    public function __construct(\Hgati\ProductReviews\Model\Gallery $reviewGallery)
    {
        $this->_reviewGallery = $reviewGallery;
    }

    /**
     * Get options
     *
     * @return mixed|array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_reviewGallery->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
