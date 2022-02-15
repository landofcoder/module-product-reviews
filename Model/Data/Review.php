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
     * @return ReviewInterface
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
     * @return ReviewInterface
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
     * @return ReviewInterface
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
     * @param \Lof\ProductReviews\Api\Data\RatingVoteInterface $customize
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
}
