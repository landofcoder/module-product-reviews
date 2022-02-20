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

namespace Lof\ProductReviews\Model\Data;

use Lof\ProductReviews\Api\Data\RatingVoteInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * @inheritdoc
 */
class RatingVote extends AbstractSimpleObject implements RatingVoteInterface
{
    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getVoteId()
    {
        return (int)$this->_get(self::KEY_VOTE_ID);
    }

    /**
     * @inheritdoc
     *
     * @param int $id
     *
     * @return $this
     */
    public function setVoteId($id)
    {
        return $this->setData(self::KEY_VOTE_ID, $id);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getRatingId()
    {
        return (int) $this->_get(self::KEY_RATING_ID);
    }

    /**
     * @inheritdoc
     *
     * @param int $ratingId
     *
     * @return $this
     */
    public function setRatingId($ratingId)
    {
        return $this->setData(self::KEY_RATING_ID, $ratingId);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getRatingName()
    {
        return $this->_get(self::KEY_RATING_NAME);
    }

    /**
     * @inheritdoc
     *
     * @param string $ratingCode
     *
     * @return $this
     */
    public function setRatingName($ratingCode)
    {
        return $this->setData(self::KEY_RATING_NAME, $ratingCode);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getValue()
    {
        return $this->_get(self::KEY_VALUE);
    }

    /**
     * @inheritdoc
     *
     * @param int $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        return $this->setData(self::KEY_VALUE, $value);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getPercent()
    {
        return (int) $this->_get(self::KEY_PERCENT);
    }

    /**
     * @inheritdoc
     *
     * @param int $percent
     *
     * @return $this
     */
    public function setPercent($percent)
    {
        return $this->setData(self::KEY_PERCENT, $percent);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getRatingCode()
    {
        return (int) $this->_get(self::KEY_RATING_CODE);
    }

    /**
     * @inheritdoc
     *
     * @param string $rating_code
     *
     * @return $this
     */
    public function setRatingCode($rating_code)
    {
        return $this->setData(self::KEY_RATING_CODE, $rating_code);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getReviewId()
    {
        return (int) $this->_get(self::KEY_REVIEW_ID);
    }

    /**
     * @inheritdoc
     *
     * @param int $review_id
     *
     * @return $this
     */
    public function setReviewId($review_id)
    {
        return $this->setData(self::KEY_REVIEW_ID, $review_id);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getOptionId()
    {
        return (int) $this->_get(self::KEY_OPTION_ID);
    }

    /**
     * @inheritdoc
     *
     * @param int $option_id
     *
     * @return $this
     */
    public function setOptionId($option_id)
    {
        return $this->setData(self::KEY_OPTION_ID, $option_id);
    }
}
