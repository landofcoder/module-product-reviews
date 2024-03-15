<?php
/**
 * Hgati
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Hgati.com license that is
 * available through the world-wide-web at this URL:
 * https://hgati.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Hgati
 * @package    Hgati_ProductReviews
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */

namespace Hgati\ProductReviews\Model;

use Hgati\ProductReviews\Api\GalleryRepositoryInterface;
use Hgati\ProductReviews\Api\Data;
use Hgati\ProductReviews\Helper\Data as HelperData;
use Hgati\ProductReviews\Model\ResourceModel\Gallery as ResourceGallery;
use Hgati\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollectionFactory;
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
     * @var Data\GalleryInterfaceFactory
     */
    protected $dataGalleryFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface|null
     */
    private $collectionProcessor;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * GalleryRepository constructor.
     * @param ResourceGallery $resource
     * @param GalleryFactory $galleryFactory
     * @param Data\GalleryInterfaceFactory $dataGalleryFactory
     * @param GalleryCollectionFactory $galleryCollectionFactory
     * @param Data\GallerySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        ResourceGallery $resource,
        GalleryFactory $galleryFactory,
        \Hgati\ProductReviews\Api\Data\GalleryInterfaceFactory $dataGalleryFactory,
        GalleryCollectionFactory $galleryCollectionFactory,
        Data\GallerySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        HelperData $helperData,
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
        $this->helperData = $helperData;
    }

    /**
     * Save Gallery data
     *
     * @param \Hgati\ProductReviews\Api\Data\GalleryInterface $gallery
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
        $images = $this->helperData->getGalleryImages($gallery);
        $gallery->setImages($images);
        return $gallery;
    }

    /**
     * Load Gallery data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Hgati\ProductReviews\Api\Data\GallerySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Hgati\ProductReviews\Model\ResourceModel\Gallery\Collection $collection */
        $collection = $this->galleryCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $items = [];
        foreach ($collection->getItems() as $_item) {
            $images = $this->helperData->getGalleryImages($_item);
            $_item->setImages($images);
            $items[] = $_item;
        }

        /** @var Data\GallerySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function getListByReview($reviewId, \Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Hgati\ProductReviews\Model\ResourceModel\Gallery\Collection $collection */
        $collection = $this->galleryCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $collection->addFieldToFilter("review_id", $reviewId);

        $items = [];
        foreach ($collection->getItems() as $_item) {
            $images = $this->helperData->getGalleryImages($_item);
            $_item->setImages($images);
            $items[] = $_item;
        }
        /** @var Data\GallerySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Gallery
     *
     * @param \Hgati\ProductReviews\Api\Data\GalleryInterface $gallery
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
     * @return CollectionProcessorInterface
     * @deprecated 101.1.0
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Hgati\ProductReviews\Model\Api\SearchCriteria\GalleryCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
