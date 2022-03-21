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
 * Represents a Review object
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ReviewInterface
{
    const ID = 'id';
    const STATUS_ID = 'status_id';
    const TITLE = 'title';
    const DETAIL = 'detail';
    const REVIEW_ID = 'review_id';

    /**
     * Product id
     */
    const ENTITY_PK_VALUE = 'entity_pk_value';
    const STORES = 'stores';
    const STORE_ID = 'store_id';

    const CUSTOMER_ID = 'customer_id';
    const NICKNAME = 'nickname';

    const REVIEW_ENTITY = 'review_entity';
    const REVIEW_TYPE = 'review_type';
    const REVIEW_STATUS = 'review_status';
    const CREATED_AT = 'created_at';
    const RATINGS = 'ratings';

    /**
     * Custom extra fields
     */
    const GALLERIES = 'galleries';
    const IMAGES = 'images';
    const CUSTOMIZE = 'customize';
    const COMMENTS = 'comments';
    const REPLY_TOTAL = 'reply_total';

    const VERIFIED_BUYER = 'verified_buyer';
    const IS_RECOMMENDED = 'is_recommended';
    const ANSWER = 'answer';
    const LIVE_ABOUT = 'like_about';
    const NOT_LIKE_ABOUT = 'not_like_about';
    const GUEST_EMAIL = 'guest_email';
    const PLUS_REVIEW = 'plus_review';
    const MINUS_REVIEW = 'minus_review';
    const REPORT_ABUSE = 'report_abuse';
    const COUNTRY = 'country';

    const REVIEW_TYPE_CUSTOMER = 1;
    const REVIEW_TYPE_GUEST = 2;
    const REVIEW_TYPE_ADMIN = 3;

    /**
     * Get review id
     *
     * @return int
     */
    public function getId();

    /**
     * Get review title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get Review detail.
     *
     * @return string
     */
    public function getDetail();

    /**
     * Get author nickname.
     *
     * @return string
     */
    public function getNickname();

    /**
     * Get customer id.
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Get review ratings.
     *
     * @return \Lof\ProductReviews\Api\Data\RatingVoteInterface[]
     */
    public function getRatings();

    /**
     * Get review comments.
     *
     * @return \Lof\ProductReviews\Api\Data\ReplyInterface[]
     */
    public function getComments();

    /**
     * Get review galleries.
     *
     * @return \Lof\ProductReviews\Api\Data\GalleryInterface
     */
    public function getGalleries();

    /**
     * Get review images.
     *
     * @return \Lof\ProductReviews\Api\Data\ImageInterface[]
     */
    public function getImages();

    /**
     * Get review customize.
     *
     * @return \Lof\ProductReviews\Api\Data\CustomizeInterface
     */
    public function getCustomize();

    /**
     * Get review entity type.
     *
     * @return string
     */
    public function getReviewEntity();

    /**
     * Get reviewer type. Possible values: 1 - Customer, 2 - Guest, 3 - Administrator.
     *
     * @return int
     */
    public function getReviewType();

    /**
     * Get review status. Possible values: 1 - Approved, 2 - Pending, 3 - Not Approved.
     *
     * @return int
     */
    public function getReviewStatus();

    /**
     * Set review id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Set Review Ttle
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set Review detail.
     *
     * @param string $detail
     *
     * @return $this
     */
    public function setDetail($detail);

    /**
     * Set author nickname.
     *
     * @param string $nickName
     *
     * @return $this
     */
    public function setNickname($nickName);

    /**
     * Set customer id.
     *
     * @param int|null $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Set review ratings.
     *
     * @param \Lof\ProductReviews\Api\Data\RatingVoteInterface[] $ratings
     *
     * @return void
     */
    public function setRatings($ratings);

    /**
     * Set review comments.
     *
     * @param \Lof\ProductReviews\Api\Data\ReplyInterface[] $comments
     *
     * @return void
     */
    public function setComments($comments);

    /**
     * Set review galleries.
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface $galleries
     *
     * @return void
     */
    public function setGalleries($galleries);

    /**
     * Set review images.
     *
     * @param \Lof\ProductReviews\Api\Data\ImageInterface[] $images
     *
     * @return void
     */
    public function setImages(array $images);

    /**
     * Set review customize.
     *
     * @param \Lof\ProductReviews\Api\Data\CustomizeInterface $customize
     *
     * @return void
     */
    public function setCustomize($customize);

    /**
     * Set review entity type.
     *
     * @param string $entity
     *
     * @return $this
     */
    public function setReviewEntity($entity);

    /**
     * Set review status. Possible values: 1 - Approved, 2 - Pending, 3 - Not Approved.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setReviewStatus($status);

    /**
     * Set reviewer type. Possible values: 1 - Customer, 2 - Guest, 3 - Administrator.
     *
     * @param int $type
     *
     * @return $this
     */
    public function setReviewType(int $type);

    /**
     * Get posted date
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set Posted date
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get total replies
     *
     * @return int
     */
    public function getReplyTotal();

    /**
     * Set total replies
     *
     * @param int $reply_total
     *
     * @return $this
     */
    public function setReplyTotal($reply_total);

    /**
     * Set Entity Id
     *
     * @param int $id
     *
     * @return $this
     */
    public function setEntityPkValue($id);

    /**
     * Get Entity ID
     *
     * @return int
     */
    public function getEntityPkValue();

    /**
     * Get Store id in which review was added
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set primary Store Id
     *
     * @param int $storeId
     *
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Get stores in which review is visible
     *
     * @return int[]
     */
    public function getStores();

    /**
     * Set Stores Ids
     *
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores);

    /**
     * Get verified_buyer
     *
     * @return int
     */
    public function getVerifiedBuyer();

    /**
     * Set verified_buyer
     *
     * @param int $verified_buyer
     *
     * @return $this
     */
    public function setVerifiedBuyer($verified_buyer);

    /**
     * Get is_recommended
     *
     * @return bool
     */
    public function getIsRecommended();

    /**
     * Set is_recommended
     *
     * @param bool $is_recommended
     *
     * @return $this
     */
    public function setIsRecommended($is_recommended);

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer();

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return $this
     */
    public function setAnswer($answer);

    /**
     * Get like_about
     *
     * @return string
     */
    public function getLikeAbout();

    /**
     * Set like_about
     *
     * @param string $like_about
     *
     * @return $this
     */
    public function setLikeAbout($like_about);

    /**
     * Get not_like_about
     *
     * @return string
     */
    public function getNotLikeAbout();

    /**
     * Set not_like_about
     *
     * @param string $not_like_about
     *
     * @return $this
     */
    public function setNotLikeAbout($not_like_about);

    /**
     * Get guest_email
     *
     * @return string
     */
    public function getGuestEmail();

    /**
     * Set guest_email
     *
     * @param string $guest_email
     *
     * @return $this
     */
    public function setGuestEmail($guest_email);

    /**
     * Get plus_review
     *
     * @return int
     */
    public function getPlusReview();

    /**
     * Set plus_review
     *
     * @param int $plus_review
     *
     * @return $this
     */
    public function setPlusReview($plus_review);

    /**
     * Get minus_review
     *
     * @return int
     */
    public function getMinusReview();

    /**
     * Set minus_review
     *
     * @param int $minus_review
     *
     * @return $this
     */
    public function setMinusReview($minus_review);

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry();

    /**
     * Set country
     *
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country);

    /**
     * Get report_abuse
     * @return int|null
     */
    public function getReportAbuse();

    /**
     * Set report_abuse
     * @param int $reportAbuse
     * @return $this
     */
    public function setReportAbuse($reportAbuse);
}
