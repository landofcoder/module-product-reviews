<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Model;

use Hgati\ProductReviews\Api\CustomizeRepositoryInterface;
use Hgati\ProductReviews\Api\Data\CustomizeInterface;
use Hgati\ProductReviews\Api\Data\CustomizeInterfaceFactory;
use Hgati\ProductReviews\Api\Data\CustomizeSearchResultsInterfaceFactory;
use Hgati\ProductReviews\Model\ResourceModel\CustomReview as ResourceCustomize;
use Hgati\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomizeCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomizeRepository implements CustomizeRepositoryInterface
{

    /**
     * @var CustomizeInterfaceFactory
     */
    protected $customizeFactory;

    /**
     * @var CustomizeCollectionFactory
     */
    protected $customizeCollectionFactory;

    /**
     * @var ResourceCustomize
     */
    protected $resource;

    /**
     * @var CustomizeSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceCustomize $resource
     * @param CustomizeInterfaceFactory $customizeFactory
     * @param CustomizeCollectionFactory $customizeCollectionFactory
     * @param CustomizeSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceCustomize $resource,
        CustomizeInterfaceFactory $customizeFactory,
        CustomizeCollectionFactory $customizeCollectionFactory,
        CustomizeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->customizeFactory = $customizeFactory;
        $this->customizeCollectionFactory = $customizeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(CustomizeInterface $customize)
    {
        try {
            $this->resource->save($customize);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the customize: %1',
                $exception->getMessage()
            ));
        }
        return $customize;
    }

    /**
     * @inheritDoc
     */
    public function get($customizeId)
    {
        $customize = $this->customizeFactory->create();
        $this->resource->load($customize, $customizeId);
        if (!$customize->getId()) {
            throw new NoSuchEntityException(__('Customize with id "%1" does not exist.', $customizeId));
        }
        return $customize;
    }

    /**
     * @inheritDoc
     */
    public function getByReview(int $reviewId)
    {
        $collection = $this->customizeCollectionFactory->create();
        $collection->addFieldToFilter("review_id", $reviewId);
        $found = $collection->getFirstItem();
        if ($found && $found->getId()) {
            return $this->get($found->getId());
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->customizeCollectionFactory->create();

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
    public function delete(CustomizeInterface $customize)
    {
        try {
            $customizeModel = $this->customizeFactory->create();
            $this->resource->load($customizeModel, $customize->getCustomizeId());
            $this->resource->delete($customizeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Customize: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($customizeId)
    {
        return $this->delete($this->get($customizeId));
    }
}

