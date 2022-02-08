<?php
/**
 * Copyright Divante Sp. z o.o.
 * See LICENSE_DIVANTE.txt for license details.
 */
namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\Data\ReviewSearchResultInterface;
use Magento\Framework\Api\SearchResults;

/**
 * @inheritdoc
 */
class ReviewSearchResult extends SearchResults implements ReviewSearchResultInterface
{
}
