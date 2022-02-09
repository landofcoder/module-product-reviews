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

use Lof\ProductReviews\Api\Data\ReplyInterface;

class ReviewReply extends \Magento\Framework\Model\AbstractModel implements ReplyInterface
{
    /**#@+
     * Gallery Status values
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('Lof\ProductReviews\Model\ResourceModel\ReviewReply');
    }

    /**
     * Get rating reply id.
     *
     * @return int
     */
    public function getReplyId()
    {
        return $this->getData(self::REPLY_ID);
    }

    /**
     * Set rating reply_id.
     *
     * @param int $reply_id
     * @return ReplyInterface
     */
    public function setReplyId($reply_id)
    {
        return $this->setData(self::REPLY_ID, $reply_id);
    }

    /**
     * Get rating parent_reply_id.
     *
     * @return int
     */
    public function getParentReplyId()
    {
        return $this->getData(self::PARENT_REPLY_ID);
    }

    /**
     * Set rating parent_reply_id.
     *
     * @param int $parent_reply_id
     * @return ReplyInterface
     */
    public function setParentReplyId($parent_reply_id)
    {
        return $this->setData(self::PARENT_REPLY_ID, $parent_reply_id);
    }

    /**
     * Get rating review_id.
     *
     * @return int
     */
    public function getReviewId()
    {
        return $this->getData(self::REVIEW_ID);
    }

    /**
     * Set rating review_id.
     *
     * @param int $review_id
     * @return ReplyInterface
     */
    public function setReviewId($review_id)
    {
        return $this->setData(self::REVIEW_ID, $review_id);
    }

    /**
     * Get rating customer_id.
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set rating customer_id.
     *
     * @param int $customer_id
     * @return ReplyInterface
     */
    public function setCustomerId($customer_id)
    {
        return $this->setData(self::CUSTOMER_ID, $customer_id);
    }

    /**
     * Get rating admin_user_id.
     *
     * @return int
     */
    public function getAdminUserId()
    {
        return $this->getData(self::ADMIN_USER_ID);
    }

    /**
     * Set rating admin_user_id.
     *
     * @param int $admin_user_id
     * @return ReplyInterface
     */
    public function setAdminUserId($admin_user_id)
    {
        return $this->setData(self::ADMIN_USER_ID, $admin_user_id);
    }

    /**
     * Get rating status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set rating status.
     *
     * @param int $status
     * @return ReplyInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get rating reply_title.
     *
     * @return string
     */
    public function getReplyTitle()
    {
        return $this->getData(self::REPLY_TITLE);
    }

    /**
     * Set rating reply_title.
     *
     * @param string $reply_title
     * @return ReplyInterface
     */
    public function setReplyTitle($reply_title)
    {
        return $this->setData(self::REPLY_TITLE, $reply_title);
    }

    /**
     * Get rating reply_comment.
     *
     * @return string
     */
    public function getReplyComment()
    {
        return $this->getData(self::REPLY_COMMENT);
    }

    /**
     * Set rating reply_comment.
     *
     * @param string $reply_comment
     * @return ReplyInterface
     */
    public function setReplyComment($reply_comment)
    {
        return $this->setData(self::REPLY_COMMENT, $reply_comment);
    }

    /**
     * Get rating user_name.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->getData(self::USER_NAME);
    }

    /**
     * Set rating user_name.
     *
     * @param string $user_name
     * @return ReplyInterface
     */
    public function setUserName($user_name)
    {
        return $this->setData(self::USER_NAME, $user_name);
    }

    /**
     * Get rating website.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->getData(self::WEBSITE);
    }

    /**
     * Set rating website.
     *
     * @param string $website
     * @return ReplyInterface
     */
    public function setWebsite($website)
    {
        return $this->setData(self::WEBSITE, $website);
    }

    /**
     * Get rating email_address.
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->getData(self::EMAIL_ADDRESS);
    }

    /**
     * Set rating email_address.
     *
     * @param string $email_address
     * @return ReplyInterface
     */
    public function setEmailAddress($email_address)
    {
        return $this->setData(self::EMAIL_ADDRESS, $email_address);
    }

    /**
     * Get rating avatar_url.
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->getData(self::AVATAR_URL);
    }

    /**
     * Set rating avatar_url.
     *
     * @param string $avatar_url
     * @return ReplyInterface
     */
    public function setAvatarUrl($avatar_url)
    {
        return $this->setData(self::AVATAR_URL, $avatar_url);
    }

    /**
     * Get rating created_at.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set rating created_at.
     *
     * @param string $created_at
     * @return ReplyInterface
     */
    public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }
}
