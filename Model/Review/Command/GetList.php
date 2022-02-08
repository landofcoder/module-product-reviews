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
use Magento\Review\Model\ResourceModel\Review\Collection;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory;
use Lof\ProductReviews\Api\Data\ReviewSearchResultInterface;
use Lof\ProductReviews\Api\Data\ReviewSearchResultInterfaceFactory;
use Lof\ProductReviews\Model\Converter\Review\ToDataModel;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @inheritdoc
 */
class GetList implements GetListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private $reviewCollectionFactory;

    /**
     * @var ReviewSearchResultInterfaceFactory
     */
    private $reviewSearchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ToDataModel
     */
    private $toDataModelConverter;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * GetList constructor.
     *
     * @param ToDataModel $toDataModelConvert
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $sourceCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param ReviewSearchResultInterfaceFactory $reviewSearchResultInterfaceFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ToDataModel $toDataModelConvert,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $sourceCollectionFactory,
        StoreManagerInterface $storeManager,
        ReviewSearchResultInterfaceFactory $reviewSearchResultInterfaceFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->reviewCollectionFactory = $sourceCollectionFactory;
        $this->reviewSearchResultsFactory = $reviewSearchResultInterfaceFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->toDataModelConverter = $toDataModelConvert;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return ReviewSearchResultInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria = null): ReviewSearchResultInterface
    {
        /** @var Collection $collection */
        $collection = $this->reviewCollectionFactory->create();
        $collection->addStoreData();
        $collection->addStoreFilter($this->getStoreId());

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $collection->load();
        $collection->addRateVotes();

        /** @var ReviewSearchResultInterface $searchResult */
        $searchResult = $this->reviewSearchResultsFactory->create();
        $searchResult->setItems($this->convertItemsToDataModel($collection->getItems()));
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * Convert Review Models to Data Models
     *
     * @param array $items
     *
     * @return array
     */
    private function convertItemsToDataModel(array $items): array
    {
        $data = [];

        foreach ($items as $item) {
            $dataModel = $this->toDataModelConverter->toDataModel($item);
            $dataModel->setStoreId($this->getStoreId());
            $data[] = $dataModel;
        }

        return $data;
    }

    /**
     * Retrive Store Id
     *
     * @return int
     */
    private function getStoreId(): int
    {
        return (int) $this->storeManager->getStore()->getId();
    }
}
