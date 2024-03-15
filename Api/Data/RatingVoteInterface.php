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
 * Represents a ReviewVote object
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface RatingVoteInterface
{
    const KEY_VOTE_ID = 'vote_id';
    const KEY_RATING_ID = 'rating_id';
    const KEY_VALUE = 'value';
    const KEY_PERCENT = 'percent';
    const KEY_RATING_NAME = 'rating_name';
    const KEY_RATING_CODE = 'rating_code';
    const KEY_REVIEW_ID = 'review_id';
    const KEY_OPTION_ID = 'option_id';

    /**
     * Get rating vote id.
     *
     * @return int
     */
    public function getVoteId();

    /**
     * Get rating id
     *
     * @return int
     */
    public function getRatingId();

    /**
     * Get rating code.
     *
     * @return string
     */
    public function getRatingName();

    /**
     * Retrieve Review Vote in percent
     *
     * @return int
     */
    public function getPercent();

    /**
     * Get rating value.
     * 1 - 20%, 2 - 40%..5 - 100%
     *
     * @return int
     */
    public function getValue();

    /**
     * Set Review Percent
     *
     * @param int $percent
     * @return $this
     */
    public function setPercent($percent);

    /**
     * Set vote id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setVoteId($id);

    /**
     * Set Rating Id
     *
     * @param int $ratingRatingId
     *
     * @return $this
     */
    public function setRatingId($ratingRatingId);

    /**
     * Set rating code.
     *
     * @param string $ratingCode
     *
     * @return $this
     */
    public function setRatingName($ratingCode);

    /**
     * Set rating value.
     *
     * @param int $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Get rating code.
     *
     * @return string
     */
    public function getRatingCode();

    /**
     * Set rating code.
     *
     * @param string $rating_code
     * @return $this
     */
    public function setRatingCode($rating_code);

    /**
     * Get rating review_id.
     *
     * @return int
     */
    public function getReviewId();

    /**
     * Set rating review_id.
     *
     * @param int $review_id
     * @return $this
     */
    public function setReviewId($review_id);

    /**
     * Get rating option_id.
     *
     * @return int
     */
    public function getOptionId();

    /**
     * Set rating option_id.
     *
     * @param int $option_id
     * @return $this
     */
    public function setOptionId($option_id);
}
