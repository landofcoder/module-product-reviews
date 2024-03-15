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
declare(strict_types=1);

namespace Hgati\ProductReviews\Model\Data;

use Hgati\ProductReviews\Api\Data\ReviewDataInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * @inheritdoc
 */
class ReviewData extends AbstractSimpleObject implements ReviewDataInterface
{

    /**
     * Get detail summary
     *
     * @return \Hgati\ProductReviews\Api\Data\DetailedSummaryInterface|null
     */
    public function getDetailedSummary()
    {
        return $this->_get(self::DETAILED_SUMMARY);
    }

    /**
     * Set detail summary
     *
     * @param \Hgati\ProductReviews\Api\Data\DetailedSummaryInterface|null $detailed_summary
     *
     * @return $this
     */
    public function setDetailedSummary($detailed_summary)
    {
        return $this->setData(self::DETAILED_SUMMARY, $detailed_summary);
    }

    /**
     * Get total_records
     *
     * @return int
     */
    public function getTotalRecords()
    {
        return $this->_get(self::TOTAL_RECORDS);
    }

    /**
     * Set total_records
     *
     * @param int $total_records
     *
     * @return $this
     */
    public function setTotalRecords(int $total_records)
    {
        return $this->setData(self::TOTAL_RECORDS, $total_records);
    }

    /**
     * Get total_records
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->_get(self::PAGE_SIZE);
    }

    /**
     * Set page_size
     *
     * @param int $page_size
     *
     * @return $this
     */
    public function setPageSize(int $page_size)
    {
        return $this->setData(self::PAGE_SIZE, $page_size);
    }

    /**
     * Get cur_page
     *
     * @return int
     */
    public function getCurPage()
    {
        return $this->_get(self::CUR_PAGE);
    }

    /**
     * Set cur_page
     *
     * @param int $cur_page
     *
     * @return $this
     */
    public function setCurPage(int $cur_page = 1)
    {
        return $this->setData(self::CUR_PAGE, $cur_page);
    }

    /**
     * Get total_found
     *
     * @return int
     */
    public function getTotalFound()
    {
        return $this->_get(self::TOTAL_FOUND);
    }

    /**
     * Set total_found
     *
     * @param int $total_found
     *
     * @return $this
     */
    public function setTotalFound(int $total_found)
    {
        return $this->setData(self::TOTAL_FOUND, $total_found);
    }

    /**
     * Get rating_summary
     *
     * @return float|int
     */
    public function getRatingSummary()
    {
        return $this->_get(self::RATING_SUMMARY);
    }

    /**
     * Set rating_summary
     *
     * @param float|int $rating_summary
     *
     * @return $this
     */
    public function setRatingSummary($rating_summary)
    {
        return $this->setData(self::RATING_SUMMARY, $rating_summary);
    }

    /**
     * Get rating_summary_value
     *
     * @return float|int
     */
    public function getRatingSummaryValue()
    {
        return $this->_get(self::RATING_SUMMARY_VALUE);
    }

    /**
     * Set rating_summary_value
     *
     * @param float|int $rating_summary_value
     *
     * @return $this
     */
    public function setRatingSummaryValue($rating_summary_value)
    {
        return $this->setData(self::RATING_SUMMARY_VALUE, $rating_summary_value);
    }

    /**
     * Get recomended_percent
     *
     * @return float|int
     */
    public function getRecomendedPercent()
    {
        return $this->_get(self::RECOMMEND_PERCENT);
    }

    /**
     * Set recomended_percent
     *
     * @param float|int $recomended_percent
     *
     * @return $this
     */
    public function setRecomendedPercent($recomended_percent)
    {
        return $this->setData(self::RECOMMEND_PERCENT, $recomended_percent);
    }

    /**
     * @inheritdoc
     */
    public function getItems()
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items)
    {
        return $this->setData(self::ITEMS, $items);
    }
}
