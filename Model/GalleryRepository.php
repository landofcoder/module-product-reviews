<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\GalleryRepositoryInterface;
use Lof\ProductReviews\Api\Data;
use Lof\ProductReviews\Model\ResourceModel\Gallery as ResourceGallery;
use Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class GalleryRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GalleryRepository implements GalleryRepositoryInterface
{
    /**
     * @var ResourceGallery
     */
    protected $resource;

    /**
     * @var GalleryFactory
     */
    protected $galleryFactory;

    /**
     * @var GalleryCollectionFactory
     */
    protected $galleryCollectionFactory;

    /**
     * @var Data\GallerySearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Lof\ProductReviews\Api\Data\GalleryInterfaceFactory
     */
    protected $dataGalleryFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourceGallery $resource
     * @param GalleryFactory $galleryFactory
     * @param Data\GalleryInterfaceFactory $dataGalleryFactory
     * @param GalleryCollectionFactory $galleryCollectionFactory
     * @param Data\GallerySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceGallery $resource,
        GalleryFactory $galleryFactory,
        \Lof\ProductReviews\Api\Data\GalleryInterfaceFactory $dataGalleryFactory,
        GalleryCollectionFactory $galleryCollectionFactory,
        Data\GallerySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->galleryFactory = $galleryFactory;
        $this->galleryCollectionFactory = $galleryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataGalleryFactory = $dataGalleryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Save Gallery data
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface $gallery
     * @return Gallery
     * @throws CouldNotSaveException
     */
    public function save(Data\GalleryInterface $gallery)
    {
        try {
            $this->resource->save($gallery);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $gallery;
    }

    /**
     * Load Gallery data by given Gallery Identity
     *
     * @param string $galleryId
     * @return Gallery
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($galleryId)
    {
        $gallery = $this->galleryFactory->create();
        $this->resource->load($gallery, $galleryId);
        if (!$gallery->getId()) {
            throw new NoSuchEntityException(__('Gallery with id "%1" does not exist.', $galleryId));
        }
        return $gallery;
    }

    /**
     * Load Gallery data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Lof\ProductReviews\Api\Data\GallerySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Lof\ProductReviews\Model\ResourceModel\Gallery\Collection $collection */
        $collection = $this->galleryCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\GallerySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Gallery
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface $gallery
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\GalleryInterface $gallery)
    {
        try {
            $this->resource->delete($gallery);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Gallery by given Gallery Identity
     *
     * @param string $galleryId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($galleryId)
    {
        return $this->delete($this->getById($galleryId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 101.1.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Lof\ProductReviews\Model\Api\SearchCriteria\GalleryCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
