<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api\Data;

interface ReportHistorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ReportHistory list.
     * @return \Hgati\ProductReviews\Api\Data\ReportHistoryInterface[]
     */
    public function getItems();

    /**
     * Set review_id list.
     * @param \Hgati\ProductReviews\Api\Data\ReportHistoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

