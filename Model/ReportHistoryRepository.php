<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\Data\ReportHistoryInterface;
use Lof\ProductReviews\Api\Data\ReportHistoryInterfaceFactory;
use Lof\ProductReviews\Api\Data\ReportHistorySearchResultsInterfaceFactory;
use Lof\ProductReviews\Api\ReportHistoryRepositoryInterface;
use Lof\ProductReviews\Model\ResourceModel\RateReport as ResourceReportHistory;
use Lof\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory as ReportHistoryCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ReportHistoryRepository implements ReportHistoryRepositoryInterface
{

    /**
     * @var ReportHistorySearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var ReportHistoryInterfaceFactory
     */
    protected $reportHistoryFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ReportHistoryCollectionFactory
     */
    protected $reportHistoryCollectionFactory;

    /**
     * @var ResourceReportHistory
     */
    protected $resource;


    /**
     * @param ResourceReportHistory $resource
     * @param ReportHistoryInterfaceFactory $reportHistoryFactory
     * @param ReportHistoryCollectionFactory $reportHistoryCollectionFactory
     * @param ReportHistorySearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceReportHistory $resource,
        ReportHistoryInterfaceFactory $reportHistoryFactory,
        ReportHistoryCollectionFactory $reportHistoryCollectionFactory,
        ReportHistorySearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->reportHistoryFactory = $reportHistoryFactory;
        $this->reportHistoryCollectionFactory = $reportHistoryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ReportHistoryInterface $reportHistory)
    {
        try {
            $this->resource->save($reportHistory);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the reportHistory: %1',
                $exception->getMessage()
            ));
        }
        return $reportHistory;
    }

    /**
     * @inheritDoc
     */
    public function get($reportHistoryId)
    {
        $reportHistory = $this->reportHistoryFactory->create();
        $this->resource->load($reportHistory, $reportHistoryId);
        if (!$reportHistory->getId()) {
            throw new NoSuchEntityException(__('ReportHistory with id "%1" does not exist.', $reportHistoryId));
        }
        return $reportHistory;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->reportHistoryCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(ReportHistoryInterface $reportHistory)
    {
        try {
            $reportHistoryModel = $this->reportHistoryFactory->create();
            $this->resource->load($reportHistoryModel, $reportHistory->getReporthistoryId());
            $this->resource->delete($reportHistoryModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ReportHistory: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($reportHistoryId)
    {
        return $this->delete($this->get($reportHistoryId));
    }
}

