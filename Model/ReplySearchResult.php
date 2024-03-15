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
 * @copyright  Copyright (c) 2022 Hgati (https://hgati.com/)
 * @license    https://hgati.com/terms
 */
namespace Hgati\ProductReviews\Model;

use Hgati\ProductReviews\Api\Data\ReplySearchResultInterface;
use Magento\Framework\Api\SearchResults;

/**
 * @inheritdoc
 */
class ReplySearchResult extends SearchResults implements ReplySearchResultInterface
{
}
