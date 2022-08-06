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

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\Data\ReviewSearchResultInterface;
use Lof\ProductReviews\Model\Review\Command\DeleteByIdInterface;
use Lof\ProductReviews\Model\Review\Command\GetInterface;
use Lof\ProductReviews\Model\Review\Command\GetListInterface;
use Lof\ProductReviews\Model\Review\Command\GetListReplyInterface;
use Lof\ProductReviews\Model\Review\Command\SaveInterface;
use Lof\ProductReviews\Model\Review\Command\ReviewReplyInterface;
use Lof\ProductReviews\Model\Review\Command\DeleteReplyByIdInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Lof\ProductReviews\Api\ReviewRepositoryInterface;
use Lof\ProductReviews\Api\Data\ReviewInterface;
use Lof\ProductReviews\Api\Data\ReplyInterface;
use Lof\ProductReviews\Api\Data\ReplySearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @inheritdoc
 */
class ReviewRepository implements ReviewRepositoryInterface
{
    /**
     * @var SaveInterface
     */
    private $commandSave;

    /**
     * @var GetInterface
     */
    private $commandGet;

    /**
     * @var GetListInterface
     */
    private $commandGetList;

    /**
     * @var DeleteByIdInterface
     */
    private $commandDeleteById;

    /**
     * @var GetListReplyInterface
     */
    private $commandGetListReply;

    /**
     * @var ReviewReplyInterface
     */
    private $commandSaveReply;

    /**
     * @var DeleteReplyByIdInterface
     */
    private $commandDeleteReplyById;

    /**
     * ReviewRepository constructor.
     *
     * @param GetInterface $commandGet
     * @param SaveInterface $commandSave
     * @param GetListInterface $commandGetList
     * @param DeleteByIdInterface $commandDeleteById
     * @param GetListReplyInterface $commandGetListReply
     * @param ReviewReplyInterface $commandSaveReply
     * @param DeleteReplyByIdInterface $commandDeleteReplyById
     */
    public function __construct(
        GetInterface $commandGet,
        SaveInterface $commandSave,
        GetListInterface $commandGetList,
        DeleteByIdInterface $commandDeleteById,
        GetListReplyInterface $commandGetListReply,
        ReviewReplyInterface $commandSaveReply,
        DeleteReplyByIdInterface $commandDeleteReplyById
    ) {
        $this->commandGet = $commandGet;
        $this->commandSave = $commandSave;
        $this->commandGetList = $commandGetList;
        $this->commandDeleteById = $commandDeleteById;
        $this->commandGetListReply = $commandGetListReply;
        $this->commandSaveReply = $commandSaveReply;
        $this->commandDeleteReplyById = $commandDeleteReplyById;
    }

    /**
     * @inheritdoc
     *
     * @param ReviewInterface $review
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     * @throws \Lof\ProductReviews\Validation\ValidationException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save(ReviewInterface $review): ReviewInterface
    {
        return $this->commandSave->execute($review);
    }

    /**
     * @inheritdoc
     *
     * @param ReplyInterface $reply
     *
     * @return ReplyInterface
     * @throws \Lof\ProductReviews\Validation\ValidationException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function reply(ReplyInterface $reply): ReplyInterface
    {
        if (!$reply->getReviewId()) {
            throw new NoSuchEntityException(__('Review Reply is missing review_id field.'));
        }
        $foundReview = $this->commandGet->execute($reply->getReviewId(), false);
        if (!$foundReview) {
            throw new NoSuchEntityException(__('Not found review ID %1 to reply.', $reply->getReviewId()));
        }
        $reply->setCustomerId($foundReview->getCustomerId());
        $reply->setCreatedAt(null);
        return $this->commandSaveReply->execute($reply);
    }

    /**
     * @inheritdoc
     *
     * @param ReplyInterface $reply
     *
     * @return ReplyInterface
     * @throws \Lof\ProductReviews\Validation\ValidationException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function replyByGuest(ReplyInterface $reply): ReplyInterface
    {
        if (!$reply->getReviewId() || !$reply->getEmailAddress()) {
            throw new NoSuchEntityException(__('Review Reply is missing review_id or email_address field.'));
        }
        $foundReview = $this->commandGet->execute($reply->getReviewId(), false);
        if (!$foundReview) {
            throw new NoSuchEntityException(__('Not found review ID %1 to reply.', $reply->getReviewId()));
        }
        $newData = [
            "customer_id" => $foundReview->getCustomerId(),
            "review_id" => $reply->getReviewId(),
            "email_address" => $reply->getEmailAddress(),
            "reply_title" => $reply->getReplyTitle(),
            "reply_comment" => $reply->getReplyComment(),
            "user_name" => $reply->getUserName(),
            "website" => $reply->getWebsite(),
            "parent_reply_id" => $reply->getParentReplyId()
        ];
        $reply->setData($newData);
        return $this->commandSaveReply->execute($reply);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param ReplyInterface $reply
     *
     * @return ReplyInterface
     * @throws \Lof\ProductReviews\Validation\ValidationException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function replyByCustomer(int $customerId, ReplyInterface $reply): ReplyInterface
    {
        if (!$reply->getReviewId() || !$reply->getEmailAddress()) {
            throw new NoSuchEntityException(__('Review Reply is missing review_id or email_address field.'));
        }
        $foundReview = $this->commandGet->execute($reply->getReviewId(), false);
        if (!$foundReview) {
            throw new NoSuchEntityException(__('Not found review ID %1 to reply.', $reply->getReviewId()));
        }
        $newData = [
            "customer_id" => $foundReview->getCustomerId(),
            "reply_customer_id" => $customerId,
            "review_id" => $reply->getReviewId(),
            "email_address" => $reply->getEmailAddress(),
            "reply_title" => $reply->getReplyTitle(),
            "reply_comment" => $reply->getReplyComment(),
            "user_name" => $reply->getUserName(),
            "website" => $reply->getWebsite(),
            "parent_reply_id" => $reply->getParentReplyId()
        ];
        $reply->setData($newData);

        return $this->commandSaveReply->execute($reply);
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $reviewId): ReviewInterface
    {
        return $this->commandGet->execute($reviewId);
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return ReviewSearchResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getListReply(int $reviewId, SearchCriteriaInterface $searchCriteria = null): ReplySearchResultInterface
    {
        return $this->commandGetListReply->execute($reviewId, $searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return ReviewSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): ReviewSearchResultInterface
    {
        return $this->commandGetList->execute($searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return ReviewSearchResultInterface
     */
    public function getMyList(int $customerId, SearchCriteriaInterface $searchCriteria = null): ReviewSearchResultInterface
    {
        return $this->commandGetList->executeByCustomer($customerId, $searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMyReview(int $customerId, int $reviewId): ReviewInterface
    {
        return $this->commandGet->executeByCustomer($customerId, $reviewId);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getReviewByCustomer(int $customerId, int $reviewId): ReviewInterface
    {
        return $this->commandGet->executeByCustomerId($customerId, $reviewId);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return ReviewSearchResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMyListReply(int $customerId, int $reviewId, SearchCriteriaInterface $searchCriteria = null): ReplySearchResultInterface
    {
        return $this->commandGetListReply->executeByCustomer($customerId, $reviewId, $searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $reviewId): void
    {
        $this->commandDeleteById->execute($reviewId);
    }

    /**
     * @inheritdoc
     *
     * @param int $replyId
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteReplyById(int $replyId): void
    {
        $this->commandDeleteReplyById->execute($replyId);
    }
}
