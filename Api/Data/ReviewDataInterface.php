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

namespace Hgati\ProductReviews\Api\Data;

/**
 * Represents a Review Data object
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ReviewDataInterface
{
    /**
     * Custom extra fields
     */
    const TOTAL_RECORDS = 'total_records';
    const RATING_SUMMARY = 'rating_summary';
    const RATING_SUMMARY_VALUE = 'rating_summary_value';
    const RECOMMEND_PERCENT = 'recomended_percent';
    const DETAILED_SUMMARY = 'detailed_summary';
    const ITEMS = 'items';
    const PAGE_SIZE = 'page_size';
    const CUR_PAGE = 'cur_page';
    const TOTAL_FOUND = 'total_found';

    /**
     * Get page_size
     *
     * @return float|int
     */
    public function getPageSize();

    /**
     * Set page_size
     *
     * @param int $page_size
     *
     * @return $this
     */
    public function setPageSize(int $page_size);

    /**
     * Get total_found
     *
     * @return float|int
     */
    public function getTotalFound();

    /**
     * Set total_found
     *
     * @param int $total_found
     *
     * @return $this
     */
    public function setTotalFound(int $total_found);

    /**
     * Get cur_page
     *
     * @return float|int
     */
    public function getCurPage();

    /**
     * Set cur_page
     *
     * @param int $cur_page
     *
     * @return $this
     */
    public function setCurPage(int $cur_page = 1);

    /**
     * Get total_records
     *
     * @return float|int
     */
    public function getTotalRecords();

    /**
     * Set total_records
     *
     * @param int $total_records
     *
     * @return $this
     */
    public function setTotalRecords(int $total_records);

    /**
     * Get rating_summary
     *
     * @return float|int
     */
    public function getRatingSummary();

    /**
     * Set rating_summary
     *
     * @param float|int $rating_summary
     *
     * @return $this
     */
    public function setRatingSummary($rating_summary);

    /**
     * Get rating_summary_value
     *
     * @return float|int
     */
    public function getRatingSummaryValue();

    /**
     * Set rating_summary_value
     *
     * @param float|int $rating_summary_value
     *
     * @return $this
     */
    public function setRatingSummaryValue($rating_summary_value);

    /**
     * Get recomended_percent
     *
     * @return float|int
     */
    public function getRecomendedPercent();

    /**
     * Set recomended_percent
     *
     * @param float|int $recomended_percent
     *
     * @return $this
     */
    public function setRecomendedPercent($recomended_percent);

    /**
     * Get detail summary
     *
     * @return \Hgati\ProductReviews\Api\Data\DetailedSummaryInterface|null
     */
    public function getDetailedSummary();

    /**
     * Set detail summary
     *
     * @param \Hgati\ProductReviews\Api\Data\DetailedSummaryInterface|null $detailed_summary
     *
     * @return $this
     */
    public function setDetailedSummary($detailed_summary);

    /**
     * Get attributes list.
     *
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Hgati\ProductReviews\Api\Data\ReviewInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
