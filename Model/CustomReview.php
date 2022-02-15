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
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\Data\CustomizeInterface;

class CustomReview extends \Magento\Framework\Model\AbstractModel implements CustomizeInterface
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('Lof\ProductReviews\Model\ResourceModel\CustomReview');
    }

    /**
     * @param $reviewId
     * @return mixed
     */
    public function addCountRating($reviewId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $getConnection = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);

        $query = 'SELECT *, SUM(rate_vote.percent) AS sum, COUNT(*) AS count, SUM(rate_vote.percent)/COUNT(*) AS average';
        $query .= ' FROM rating_option_vote as rate_vote';
        $query .= ' WHERE review_id = ' . (int)$reviewId;

        $item = $getConnection->getConnection()->fetchAll($query);
        return $item[0]['average'];
    }

    /**
     * @inheritDoc
     */
    public function getReviewCustomizeId()
    {
        return $this->getData(self::REVIEW_CUSTOMIZE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setReviewCustomizeId($review_customize_id)
    {
        return $this->setData(self::REVIEW_CUSTOMIZE_ID, $review_customize_id);
    }

    /**
     * @inheritDoc
     */
    public function getAdvantages()
    {
        return $this->getData(self::ADVANTAGES);
    }

    /**
     * @inheritDoc
     */
    public function setAdvantages($advantages)
    {
        return $this->setData(self::ADVANTAGES, $advantages);
    }

    /**
     * @inheritDoc
     */
    public function getDisadvantages()
    {
        return $this->getData(self::DISADVANTAGES);
    }

    /**
     * @inheritDoc
     */
    public function setDisadvantages($disadvantages)
    {
        return $this->setData(self::DISADVANTAGES, $disadvantages);
    }

    /**
     * @inheritDoc
     */
    public function getAverage()
    {
        return $this->getData(self::AVERAGE);
    }

    /**
     * @inheritDoc
     */
    public function setAverage($average)
    {
        return $this->setData(self::AVERAGE, $average);
    }

    /**
     * @inheritDoc
     */
    public function getCountHelpful()
    {
        return $this->getData(self::COUNT_HELPFUL);
    }

    /**
     * @inheritDoc
     */
    public function setCountHelpful($countHelpful)
    {
        return $this->setData(self::COUNT_HELPFUL, $countHelpful);
    }

    /**
     * @inheritDoc
     */
    public function getCountUnhelpful()
    {
        return $this->getData(self::COUNT_UNHELPFUL);
    }

    /**
     * @inheritDoc
     */
    public function setCountUnhelpful($countUnhelpful)
    {
        return $this->setData(self::COUNT_UNHELPFUL, $countUnhelpful);
    }

    /**
     * @inheritDoc
     */
    public function getTotalHelpful()
    {
        return $this->getData(self::TOTAL_HELPFUL);
    }

    /**
     * @inheritDoc
     */
    public function setTotalHelpful($totalHelpful)
    {
        return $this->setData(self::TOTAL_HELPFUL, $totalHelpful);
    }

    /**
     * @inheritDoc
     */
    public function getReportAbuse()
    {
        return $this->getData(self::REPORT_ABUSE);
    }

    /**
     * @inheritDoc
     */
    public function setReportAbuse($reportAbuse)
    {
        return $this->setData(self::REPORT_ABUSE, $reportAbuse);
    }

    /**
     * @inheritDoc
     */
    public function getReviewId()
    {
        return $this->getData(self::REVIEW_ID);
    }

    /**
     * @inheritDoc
     */
    public function setReviewId($reviewId)
    {
        return $this->setData(self::REVIEW_ID, $reviewId);
    }

    /**
     * @inheritDoc
     */
    public function getEmailAddress()
    {
        return $this->getData(self::EMAIL_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setEmailAddress($emailAddress)
    {
        return $this->setData(self::EMAIL_ADDRESS, $emailAddress);
    }

    /**
     * @inheritDoc
     */
    public function getAvatarImage()
    {
        return $this->getData(self::AVATAR_IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setAvatarImage($avatarImage)
    {
        return $this->setData(self::AVATAR_IMAGE, $avatarImage);
    }

    /**
     * @inheritDoc
     */
    public function getAvatarUrl()
    {
        return $this->getData(self::AVATAR_URL);
    }

    /**
     * @inheritDoc
     */
    public function setAvatarUrl($avatarUrl)
    {
        return $this->setData(self::AVATAR_URL, $avatarUrl);
    }

    /**
     * @inheritDoc
     */
    public function getAnswer()
    {
        return $this->getData(self::ANSWER);
    }

    /**
     * @inheritDoc
     */
    public function setAnswer($answer)
    {
        return $this->setData(self::ANSWER, $answer);
    }

    /**
     * @inheritDoc
     */
    public function getIsRecommended()
    {
        return $this->getData(self::IS_RECOMMENDED);
    }

    /**
     * @inheritDoc
     */
    public function setIsRecommended($is_recommended)
    {
        return $this->setData(self::IS_RECOMMENDED, $is_recommended);
    }

    /**
     * @inheritDoc
     */
    public function getVerifiedBuyer()
    {
        return $this->getData(self::VERIFIED_BUYER);
    }

    /**
     * @inheritDoc
     */
    public function setVerifiedBuyer($verified_buyer)
    {
        return $this->setData(self::VERIFIED_BUYER, $verified_buyer);
    }
}
