<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Api\Data;

interface RemindersSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Reminders list.
     * @return \Lof\ProductReviews\Api\Data\RemindersInterface[]
     */
    public function getItems();

    /**
     * Set order_id list.
     * @param \Lof\ProductReviews\Api\Data\RemindersInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

