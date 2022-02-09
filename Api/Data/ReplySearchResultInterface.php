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
declare(strict_types=1);

namespace Lof\ProductReviews\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Search results of Repository::getList method
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ReplySearchResultInterface extends SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Lof\ProductReviews\Api\Data\ReplyInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Lof\ProductReviews\Api\Data\ReplyInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
