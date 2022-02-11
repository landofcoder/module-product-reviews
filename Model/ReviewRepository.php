<?php
/**
 * Copyright Divante Sp. z o.o.
 * See LICENSE_DIVANTE.txt for license details.
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
use Magento\Framework\Api\SearchCriteria;

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
     * @return ReviewInterface
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
        $reply->setReplyId(0);
        $reply->setCustomerId($foundReview->getCustomerId());
        $reply->setCreatedAt(null);
        return $this->commandSaveReply->executeByGuest($reply);
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
        $reply->setReplyId(0);
        $reply->setCustomerId($foundReview->getCustomerId());
        $reply->setCreatedAt(null);
        $reply->setReplyCustomerId($customerId);

        return $this->commandSaveReply->executeByCustomer($customerId, $reply);
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return ReviewInterface
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
     * @param SearchCriteria|null $searchCriteria
     * @return ReviewSearchResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getListReply(int $reviewId, SearchCriteria $searchCriteria = null): ReplySearchResultInterface
    {
        return $this->commandGetListReply->execute($reviewId, $searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param SearchCriteria|null $searchCriteria
     *
     * @return ReviewSearchResultInterface
     */
    public function getList(SearchCriteria $searchCriteria = null): ReviewSearchResultInterface
    {
        return $this->commandGetList->execute($searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param SearchCriteria|null $searchCriteria
     *
     * @return ReviewSearchResultInterface
     */
    public function getMyList(int $customerId, SearchCriteria $searchCriteria = null): ReviewSearchResultInterface
    {
        return $this->commandGetList->executeByCustomer($customerId, $searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     *
     * @return ReviewInterface
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
     * @param SearchCriteria|null $searchCriteria
     * @return ReviewSearchResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMyListReply(int $customerId, int $reviewId, SearchCriteria $searchCriteria = null): ReplySearchResultInterface
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
