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

/**
 * Represents a ReviewVote object
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface DetailedSummaryInterface
{
    const ONE = 'one';
    const TWO = 'two';
    const THREE = 'three';
    const FOUR = 'four';
    const FIVE = 'five';
    const RATING_SUMMARY = 'rating_summary';
    const REVIEWS_COUNT = 'reviews_count';


    /**
     * Get rating one.
     *
     * @return int
     */
    public function getOne();

    /**
     * Get rating two
     *
     * @return int
     */
    public function getTwo();

    /**
     * Get rating three
     *
     * @return int
     */
    public function getThree();

    /**
     * Get rating four
     *
     * @return int
     */
    public function getFour();

    /**
     * rating five
     *
     * @return int
     */
    public function getFive();

    /**
     * Set Review one
     *
     * @param int $one
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setOne($one);

    /**
     * Set vote id.
     *
     * @param int $id
     *
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setTwo($two);

    /**
     * Set vote id.
     *
     * @param int $two
     *
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setThree($two);

    /**
     * Set vote id.
     *
     * @param int $four
     *
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setFour($four);

    /**
     * Set vote id.
     *
     * @param int $five
     *
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setFive($five);

    /**
     * rating rating_summary
     *
     * @return float|int
     */
    public function getRatingSummary();

    /**
     * Set rating_summary
     *
     * @param float|int $rating_summary
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setRatingSummary($rating_summary);

    /**
     * rating reviews_count
     *
     * @return int
     */
    public function getReviewsCount();

    /**
     * Set reviews_count
     *
     * @param int $reviews_count
     * @return \Lof\ProductReviews\Api\Data\DetailedSummaryInterface
     */
    public function setReviewsCount($reviews_count);
}
