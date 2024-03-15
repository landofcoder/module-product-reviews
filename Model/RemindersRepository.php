<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Model;

use Hgati\ProductReviews\Api\Data\RemindersInterface;
use Hgati\ProductReviews\Api\Data\RemindersInterfaceFactory;
use Hgati\ProductReviews\Api\Data\RemindersSearchResultsInterfaceFactory;
use Hgati\ProductReviews\Api\RemindersRepositoryInterface;
use Hgati\ProductReviews\Model\ResourceModel\Reminders as ResourceReminders;
use Hgati\ProductReviews\Model\ResourceModel\Reminders\CollectionFactory as RemindersCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class RemindersRepository implements RemindersRepositoryInterface
{

    /**
     * @var ResourceReminders
     */
    protected $resource;

    /**
     * @var RemindersInterfaceFactory
     */
    protected $remindersFactory;

    /**
     * @var RemindersSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var RemindersCollectionFactory
     */
    protected $remindersCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceReminders $resource
     * @param RemindersInterfaceFactory $remindersFactory
     * @param RemindersCollectionFactory $remindersCollectionFactory
     * @param RemindersSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceReminders $resource,
        RemindersInterfaceFactory $remindersFactory,
        RemindersCollectionFactory $remindersCollectionFactory,
        RemindersSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->remindersFactory = $remindersFactory;
        $this->remindersCollectionFactory = $remindersCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(RemindersInterface $reminders)
    {
        try {
            $this->resource->save($reminders);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the reminders: %1',
                $exception->getMessage()
            ));
        }
        return $reminders;
    }

    /**
     * @inheritDoc
     */
    public function get($remindersId)
    {
        $reminders = $this->remindersFactory->create();
        $this->resource->load($reminders, $remindersId);
        if (!$reminders->getId()) {
            throw new NoSuchEntityException(__('Reminders with id "%1" does not exist.', $remindersId));
        }
        return $reminders;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->remindersCollectionFactory->create();

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
    public function delete(RemindersInterface $reminders)
    {
        try {
            $remindersModel = $this->remindersFactory->create();
            $this->resource->load($remindersModel, $reminders->getRemindersId());
            $this->resource->delete($remindersModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Reminders: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($remindersId)
    {
        return $this->delete($this->get($remindersId));
    }
}

