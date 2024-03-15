<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api\Data;

interface CustomizeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Customize list.
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface[]
     */
    public function getItems();

    /**
     * Set advantages list.
     * @param \Hgati\ProductReviews\Api\Data\CustomizeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

