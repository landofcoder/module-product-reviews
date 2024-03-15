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
interface ReplyInterface
{
    const REPLY_ID = 'reply_id';
    const PARENT_REPLY_ID = 'parent_reply_id';
    const REVIEW_ID = 'review_id';
    const CUSTOMER_ID = 'customer_id';
    const REPLY_TITLE = 'reply_title';
    const REPLY_COMMENT = 'reply_comment';
    const USER_NAME = 'user_name';
    const WEBSITE = 'website';
    const STATUS = 'status';
    const EMAIL_ADDRESS = 'email_address';
    const AVATAR_URL = 'avatar_url';
    const ADMIN_USER_ID = 'admin_user_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const REPLY_CUSTOMER_ID = 'reply_customer_id';

    /**
     * Get rating reply id.
     *
     * @return int|null
     */
    public function getReplyId();

    /**
     * Set rating reply_id.
     *
     * @param int|null $reply_id
     * @return ReplyInterface
     */
    public function setReplyId($reply_id);

    /**
     * Get rating reply_customer_id id.
     *
     * @return int|null
     */
    public function getReplyCustomerId();

    /**
     * Set rating reply_customer_id.
     *
     * @param int|null $reply_customer_id
     * @return ReplyInterface
     */
    public function setReplyCustomerId($reply_customer_id);

    /**
     * Get rating parent_reply_id.
     *
     * @return int|null
     */
    public function getParentReplyId();

    /**
     * Set rating parent_reply_id.
     *
     * @param int|null $parent_reply_id
     * @return ReplyInterface
     */
    public function setParentReplyId($parent_reply_id);

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
     * @return ReplyInterface
     */
    public function setReviewId($review_id);

    /**
     * Get rating customer_id.
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set rating customer_id.
     *
     * @param int|null $customer_id
     * @return ReplyInterface
     */
    public function setCustomerId($customer_id);

    /**
     * Get rating admin_user_id.
     *
     * @return int|null
     */
    public function getAdminUserId();

    /**
     * Set rating admin_user_id.
     *
     * @param int|null $admin_user_id
     * @return ReplyInterface
     */
    public function setAdminUserId($admin_user_id);

    /**
     * Get rating status.
     *
     * @return int
     */
    public function getStatus();

    /**
     * Set rating status.
     *
     * @param int $status
     * @return ReplyInterface
     */
    public function setStatus($status);

    /**
     * Get rating reply_title.
     *
     * @return string
     */
    public function getReplyTitle();

    /**
     * Set rating reply_title.
     *
     * @param string $reply_title
     * @return ReplyInterface
     */
    public function setReplyTitle($reply_title);

    /**
     * Get rating reply_comment.
     *
     * @return string
     */
    public function getReplyComment();

    /**
     * Set rating reply_comment.
     *
     * @param string $reply_comment
     * @return ReplyInterface
     */
    public function setReplyComment($reply_comment);

    /**
     * Get rating user_name.
     *
     * @return string
     */
    public function getUserName();

    /**
     * Set rating user_name.
     *
     * @param string $user_name
     * @return ReplyInterface
     */
    public function setUserName($user_name);

    /**
     * Get rating website.
     *
     * @return string
     */
    public function getWebsite();

    /**
     * Set rating website.
     *
     * @param string $website
     * @return ReplyInterface
     */
    public function setWebsite($website);

    /**
     * Get rating email_address.
     *
     * @return string
     */
    public function getEmailAddress();

    /**
     * Set rating email_address.
     *
     * @param string $email_address
     * @return ReplyInterface
     */
    public function setEmailAddress($email_address);

    /**
     * Get rating avatar_url.
     *
     * @return string
     */
    public function getAvatarUrl();

    /**
     * Set rating avatar_url.
     *
     * @param string $avatar_url
     * @return ReplyInterface
     */
    public function setAvatarUrl($avatar_url);

    /**
     * Get rating created_at.
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set rating created_at.
     *
     * @param string|null $created_at
     * @return ReplyInterface
     */
    public function setCreatedAt($created_at);

    /**
     * Get rating updated_at.
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set rating updated_at.
     *
     * @param string|null $updated_at
     * @return ReplyInterface
     */
    public function setUpdatedAt($updated_at);
}
