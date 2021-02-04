<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for review gallery search results.
 * @api
 * @since 100.0.2
 */
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
