<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Api\Data;

interface CustomizeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Customize list.
     * @return \Lof\ProductReviews\Api\Data\CustomizeInterface[]
     */
    public function getItems();

    /**
     * Set advantages list.
     * @param \Lof\ProductReviews\Api\Data\CustomizeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

