<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Api\Data;

interface ReportHistorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ReportHistory list.
     * @return \Lof\ProductReviews\Api\Data\ReportHistoryInterface[]
     */
    public function getItems();

    /**
     * Set review_id list.
     * @param \Lof\ProductReviews\Api\Data\ReportHistoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

