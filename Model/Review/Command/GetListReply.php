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

namespace Lof\ProductReviews\Model\Review\Command;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply\Collection;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory;
use Lof\ProductReviews\Api\Data\ReplySearchResultInterface;
use Lof\ProductReviews\Api\Data\ReplySearchResultInterfaceFactory;

/**
 * @inheritdoc
 */
class GetListReply implements GetListReplyInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private $reviewReplyCollectionFactory;

    /**
     * @var ReplySearchResultInterfaceFactory
     */
    private $replySearchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * GetList constructor.
     *
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $sourceCollectionFactory
     * @param ReplySearchResultInterfaceFactory $replySearchResultInterfaceFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $sourceCollectionFactory,
        ReplySearchResultInterfaceFactory $replySearchResultInterfaceFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->reviewReplyCollectionFactory = $sourceCollectionFactory;
        $this->replySearchResultInterfaceFactory = $replySearchResultInterfaceFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return ReplySearchResultInterface
     */
    public function execute(int $reviewId, SearchCriteriaInterface $searchCriteria = null): ReplySearchResultInterface
    {
        /** @var Collection $collection */
        $collection = $this->reviewReplyCollectionFactory->create();

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $collection->addFieldToFilter("review_id", $reviewId);

        $collection->load();

        /** @var ReviewSearchResultInterface $searchResult */
        $searchResult = $this->reviewSearchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
