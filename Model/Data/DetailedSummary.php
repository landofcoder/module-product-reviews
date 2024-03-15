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

use Hgati\ProductReviews\Api\Data\DetailedSummaryInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * @inheritdoc
 */
class DetailedSummary extends AbstractSimpleObject implements DetailedSummaryInterface
{
    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getOne()
    {
        return (int)$this->_get(self::ONE);
    }

    /**
     * @inheritdoc
     *
     * @param int $one
     *
     * @return $this
     */
    public function setOne($one)
    {
        return $this->setData(self::ONE, $one);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getTwo()
    {
        return (int)$this->_get(self::TWO);
    }

    /**
     * @inheritdoc
     *
     * @param int $two
     *
     * @return $this
     */
    public function setTwo($two)
    {
        return $this->setData(self::TWO, $two);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getThree()
    {
        return (int)$this->_get(self::THREE);
    }

    /**
     * @inheritdoc
     *
     * @param int $three
     *
     * @return $this
     */
    public function setThree($three)
    {
        return $this->setData(self::THREE, $three);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getFour()
    {
        return (int)$this->_get(self::FOUR);
    }

    /**
     * @inheritdoc
     *
     * @param int $four
     *
     * @return $this
     */
    public function setFour($four)
    {
        return $this->setData(self::FOUR, $four);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getFive()
    {
        return (int)$this->_get(self::FIVE);
    }

    /**
     * @inheritdoc
     *
     * @param int $five
     *
     * @return $this
     */
    public function setFive($five)
    {
        return $this->setData(self::FIVE, $five);
    }

    /**
     * @inheritdoc
     *
     * @return float|int
     */
    public function getRatingSummary()
    {
        return (int)$this->_get(self::RATING_SUMMARY);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     *
     * @return int
     */
    public function getReviewsCount()
    {
        return (int)$this->_get(self::REVIEWS_COUNT);
    }

    /**
     * @inheritdoc
     *
     * @param int $rating_summary
     *
     * @return $this
     */
    public function setReviewsCount($reviews_count)
    {
        return $this->setData(self::REVIEWS_COUNT, $reviews_count);
    }

}
