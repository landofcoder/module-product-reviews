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
namespace Lof\ProductReviews\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface GallerySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return \Lof\ProductReviews\Api\Data\GalleryInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
