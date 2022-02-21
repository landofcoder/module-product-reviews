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

use Lof\ProductReviews\Api\Data\ReviewInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * @inheritdoc
 */
class Review extends AbstractSimpleObject implements ReviewInterface
{
    /**
     * @inheritdoc
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritdoc
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->_get(self::DETAIL);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->_get(self::NICKNAME);
    }

    /**
     * @inheritdoc
     *
     * @return \Lof\ProductReviews\Api\Data\RatingVoteInterface[]
     */
    public function getRatings()
    {
        return $this->_get(self::RATINGS);
    }

    /**
     * @inheritdoc
     *
     * @return \Lof\ProductReviews\Api\Data\ReplyInterface[]
     */
    public function getComments()
    {
        return $this->_get(self::COMMENTS);
    }

    /**
     * @inheritdoc
     *
     * @return \Lof\ProductReviews\Api\Data\GalleryInterface
     */
    public function getGalleries()
    {
        return $this->_get(self::GALLERIES);
    }

    /**
     * @inheritdoc
     *
     * @return \Lof\ProductReviews\Api\Data\ImageInterface[]
     */
    public function getImages()
    {
        return $this->_get(self::IMAGES);
    }

    /**
     * @inheritdoc
     *
     * @return \Lof\ProductReviews\Api\Data\CustomizeInterface
     */
    public function getCustomize()
    {
        return $this->_get(self::CUSTOMIZE);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getReviewEntity()
    {
        return $this->_get(self::REVIEW_ENTITY);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getReviewType()
    {
        return $this->_get(self::REVIEW_TYPE);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getReviewStatus()
    {
        return $this->_get(self::REVIEW_STATUS);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * @inheritdoc
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritdoc
     *
     * @param string $detail
     *
     * @return $this
     */
    public function setDetail($detail)
    {
        return $this->setData(self::DETAIL, $detail);
    }

    /**
     * @inheritdoc
     *
     * @param int|null $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritdoc
     *
     * @param string $nickName
     *
     * @return $this
     */
    public function setNickname($nickName)
    {
        return $this->setData(self::NICKNAME, $nickName);
    }

    /**
     * @inheritdoc
     *
     * @param \Lof\ProductReviews\Api\Data\RatingVoteInterface[] $ratings
     *
     * @return Review|void
     */
    public function setRatings($ratings)
    {
        return $this->setData(self::RATINGS, $ratings);
    }

    /**
     * @inheritdoc
     *
     * @param \Lof\ProductReviews\Api\Data\ReplyInterface[] $comments
     *
     * @return Review|void
     */
    public function setComments($comments)
    {
        return $this->setData(self::COMMENTS, $comments);
    }

    /**
     * @inheritdoc
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface $galleries
     *
     * @return Review|void
     */
    public function setGalleries($galleries)
    {
        return $this->setData(self::GALLERIES, $galleries);
    }

    /**
     * @inheritdoc
     *
     * @param \Lof\ProductReviews\Api\Data\ImageInterface[] $images
     *
     * @return Review|void
     */
    public function setImages(array $images)
    {
        return $this->setData(self::IMAGES, $images);
    }

    /**
     * @inheritdoc
     *
     * @param \Lof\ProductReviews\Api\Data\CustomizeInterface $customize
     *
     * @return Review|void
     */
    public function setCustomize($customize)
    {
        return $this->setData(self::CUSTOMIZE, $customize);
    }

    /**
     * @inheritdoc
     *
     * @param string $entity
     *
     * @return $this
     */
    public function setReviewEntity($entity)
    {
        return $this->setData(self::REVIEW_ENTITY, $entity);
    }

    /**
     * @inheritdoc
     *
     * @param int $type
     *
     * @return $this
     */
    public function setReviewType(int $type)
    {
        return $this->setData(self::REVIEW_TYPE, $type);
    }

    /**
     * @inheritdoc
     *
     * @param int $status
     *
     * @return $this
     */
    public function setReviewStatus($status)
    {
        return $this->setData(self::REVIEW_STATUS, $status);
    }

    /**
     * @inheritdoc
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getEntityPkValue()
    {
        return $this->_get(self::ENTITY_PK_VALUE);
    }

    /**
     * @inheritdoc
     *
     * @param int $id
     *
     * @return $this
     */
    public function setEntityPkValue($id)
    {
        return $this->setData(self::ENTITY_PK_VALUE, $id);
    }

    /**
     * @inheritdoc
     *
     * @return mixed|array
     */
    public function getStores()
    {
        return $this->_get(self::STORES);
    }

    /**
     * @inheritdoc
     *
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores)
    {
        return $this->setData(self::STORES, $stores);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * @inheritdoc
     *
     * @param int $storeId
     *
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getReplyTotal()
    {
        return $this->_get(self::REPLY_TOTAL);
    }

    /**
     * @inheritdoc
     *
     * @param int $reply_total
     *
     * @return $this
     */
    public function setReplyTotal($reply_total)
    {
        return $this->setData(self::REPLY_TOTAL, $reply_total);
    }

    /**
     * Get verified_buyer
     *
     * @return int
     */
    public function getVerifiedBuyer()
    {
        return $this->_get(self::VERIFIED_BUYER);
    }

    /**
     * Set verified_buyer
     *
     * @param int $verified_buyer
     *
     * @return $this
     */
    public function setVerifiedBuyer($verified_buyer)
    {
        return $this->setData(self::VERIFIED_BUYER, $verified_buyer);
    }

    /**
     * Get is_recommended
     *
     * @return bool
     */
    public function getIsRecommended()
    {
        return $this->_get(self::IS_RECOMMENDED);
    }

    /**
     * Set is_recommended
     *
     * @param bool $is_recommended
     *
     * @return $this
     */
    public function setIsRecommended($is_recommended)
    {
        return $this->setData(self::IS_RECOMMENDED, $is_recommended);
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->_get(self::ANSWER);
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return $this
     */
    public function setAnswer($answer)
    {
        return $this->setData(self::ANSWER, $answer);
    }

    /**
     * Get like_about
     *
     * @return string
     */
    public function getLikeAbout()
    {
        return $this->_get(self::LIVE_ABOUT);
    }

    /**
     * Set like_about
     *
     * @param string $like_about
     *
     * @return $this
     */
    public function setLikeAbout($like_about)
    {
        return $this->setData(self::LIVE_ABOUT, $like_about);
    }

    /**
     * Get not_like_about
     *
     * @return string
     */
    public function getNotLikeAbout()
    {
        return $this->_get(self::NOT_LIKE_ABOUT);
    }

    /**
     * Set not_like_about
     *
     * @param string $not_like_about
     *
     * @return $this
     */
    public function setNotLikeAbout($not_like_about)
    {
        return $this->setData(self::NOT_LIKE_ABOUT, $not_like_about);
    }

    /**
     * Get guest_email
     *
     * @return string
     */
    public function getGuestEmail()
    {
        return $this->_get(self::GUEST_EMAIL);
    }

    /**
     * Set guest_email
     *
     * @param string $guest_email
     *
     * @return $this
     */
    public function setGuestEmail($guest_email)
    {
        return $this->setData(self::GUEST_EMAIL, $guest_email);
    }

    /**
     * Get plus_review
     *
     * @return int
     */
    public function getPlusReview()
    {
        return $this->_get(self::PLUS_REVIEW);
    }

    /**
     * Set plus_review
     *
     * @param int $plus_review
     *
     * @return $this
     */
    public function setPlusReview($plus_review)
    {
        return $this->setData(self::PLUS_REVIEW, $plus_review);
    }

    /**
     * Get minus_review
     *
     * @return int
     */
    public function getMinusReview()
    {
        return $this->_get(self::MINUS_REVIEW);
    }

    /**
     * Set minus_review
     *
     * @param int $minus_review
     *
     * @return $this
     */
    public function setMinusReview($minus_review)
    {
        return $this->setData(self::MINUS_REVIEW, $minus_review);
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->_get(self::COUNTRY);
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     */
    public function getReportAbuse()
    {
        return $this->_get(self::REPORT_ABUSE);
    }

    /**
     * @inheritDoc
     *
     * @return $this
     */
    public function setReportAbuse($reportAbuse)
    {
        return $this->setData(self::REPORT_ABUSE, $reportAbuse);
    }
}
