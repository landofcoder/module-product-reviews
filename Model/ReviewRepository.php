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
     * ReviewRepository constructor.
     *
     * @param GetInterface $commandGet
     * @param SaveInterface $commandSave
     * @param GetListInterface $commandGetList
     * @param DeleteByIdInterface $commandDeleteById
     * @param GetListReplyInterface $commandGetListReply
     * @param ReviewReplyInterface $commandSaveReply
     */
    public function __construct(
        GetInterface $commandGet,
        SaveInterface $commandSave,
        GetListInterface $commandGetList,
        DeleteByIdInterface $commandDeleteById,
        GetListReplyInterface $commandGetListReply,
        ReviewReplyInterface $commandSaveReply
    ) {
        $this->commandGet = $commandGet;
        $this->commandSave = $commandSave;
        $this->commandGetList = $commandGetList;
        $this->commandDeleteById = $commandDeleteById;
        $this->commandGetListReply = $commandGetListReply;
        $this->commandSaveReply = $commandSaveReply;
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
        return $this->commandSaveReply->execute($reply);
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
}
