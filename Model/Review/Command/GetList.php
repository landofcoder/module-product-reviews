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
use Lof\ProductReviews\Api\Data\ReviewInterface;
use Magento\Store\Model\StoreManagerInterface;
use Lof\ProductReviews\Helper\Data as HelperData;
use Lof\ProductReviews\Api\Data\GalleryInterfaceFactory;
use Lof\ProductReviews\Api\Data\CustomizeInterfaceFactory;
use Lof\ProductReviews\Api\Data\ReplyInterfaceFactory;
use Lof\ProductReviews\Api\Data\ImageInterfaceFactory;
use Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory as ReviewReplyCollectionFactory;

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
     * @var GalleryInterfaceFactory
     */
    protected $dataGalleryFactory;

    /**
     * @var GalleryCollectionFactory
     */
    protected $galleryCollectionFactory;

    /**
     * @var CustomizeInterfaceFactory
     */
    protected $dataCustomizeFactory;

    /**
     * @var CustomReviewCollectionFactory
     */
    protected $customizeCollectionFactory;

    /**
     * @var ReplyInterfaceFactory
     */
    protected $dataReplyFactory;

    /**
     * @var ReviewReplyCollectionFactory
     */
    protected $replyCollectionFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ImageInterfaceFactory
     */
    protected $dataImageFactory;

    /**
     * GetList constructor.
     *
     * @param ToDataModel $toDataModelConvert
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $sourceCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param ReviewSearchResultInterfaceFactory $reviewSearchResultInterfaceFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param GalleryInterfaceFactory $dataGalleryFactory
     * @param GalleryCollectionFactory $galleryCollectionFactory
     * @param CustomizeInterfaceFactory $dataCustomizeFactory
     * @param CustomReviewCollectionFactory $customizeCollectionFactory
     * @param ReplyInterfaceFactory $dataReplyFactory
     * @param ReviewReplyCollectionFactory $replyCollectionFactory
     * @param HelperData $helperData
     * @param ImageInterfaceFactory $dataImageFactory
     */
    public function __construct(
        ToDataModel $toDataModelConvert,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $sourceCollectionFactory,
        StoreManagerInterface $storeManager,
        ReviewSearchResultInterfaceFactory $reviewSearchResultInterfaceFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GalleryInterfaceFactory $dataGalleryFactory,
        GalleryCollectionFactory $galleryCollectionFactory,
        CustomizeInterfaceFactory $dataCustomizeFactory,
        CustomReviewCollectionFactory $customizeCollectionFactory,
        ReplyInterfaceFactory $dataReplyFactory,
        ReviewReplyCollectionFactory $replyCollectionFactory,
        HelperData $helperData,
        ImageInterfaceFactory $dataImageFactory
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->reviewCollectionFactory = $sourceCollectionFactory;
        $this->reviewSearchResultsFactory = $reviewSearchResultInterfaceFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->toDataModelConverter = $toDataModelConvert;
        $this->storeManager = $storeManager;
        $this->dataGalleryFactory = $dataGalleryFactory;
        $this->galleryCollectionFactory = $galleryCollectionFactory;
        $this->dataCustomizeFactory = $dataCustomizeFactory;
        $this->customizeCollectionFactory = $customizeCollectionFactory;
        $this->dataReplyFactory = $dataReplyFactory;
        $this->replyCollectionFactory = $replyCollectionFactory;
        $this->helperData = $helperData;
        $this->dataImageFactory = $dataImageFactory;
    }

    /**
     * @inheritdoc
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @param bool $moreInfo
     *
     * @return ReviewSearchResultInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria = null,  bool $moreInfo = true): ReviewSearchResultInterface
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

        $items = $this->convertItemsToDataModel($collection->getItems());
        $reviews = [];

        if ($moreInfo) {
            foreach ($items as $reviewDataObject) {
                $reviewDataObject = $this->addCustomize($reviewDataObject);
                $reviewDataObject = $this->addGalleries($reviewDataObject);
                $reviewDataObject = $this->addReply($reviewDataObject);
                $reviewDataObject = $this->helperData->mappingReviewData($reviewDataObject);

                $reviews[] = $reviewDataObject;
            }
        } else {
            $reviews = $items;
        }

        /** @var ReviewSearchResultInterface $searchResult */
        $searchResult = $this->reviewSearchResultsFactory->create();
        $searchResult->setItems($items);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param SearchCriteriaInterface|null $searchCriteria
     * @param bool $moreInfo
     *
     * @return ReviewSearchResultInterface
     */
    public function executeByCustomer(int $customerId, SearchCriteriaInterface $searchCriteria = null,  bool $moreInfo = true): ReviewSearchResultInterface
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

        $collection->addFieldToFilter("customer_id", $customerId);

        $collection->load();
        $collection->addRateVotes();

        $items = $this->convertItemsToDataModel($collection->getItems());
        $reviews = [];

        if ($moreInfo) {
            foreach ($items as $reviewDataObject) {
                $reviewDataObject = $this->addCustomize($reviewDataObject);
                $reviewDataObject = $this->addGalleries($reviewDataObject);
                $reviewDataObject = $this->addReply($reviewDataObject);
                $reviewDataObject = $this->helperData->mappingReviewData($reviewDataObject);

                $reviews[] = $reviewDataObject;
            }
        } else {
            $reviews = $items;
        }

        /** @var ReviewSearchResultInterface $searchResult */
        $searchResult = $this->reviewSearchResultsFactory->create();
        $searchResult->setItems($items);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * Convert Review Models to Data Models
     *
     * @param array $items
     *
     * @return mixed|array
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

     /**
     * add customize review
     *
     * @param mixed|array|ReviewInterface
     * @return mixed|array|ReviewInterface
     */
    protected function addCustomize($reviewDataObject)
    {
        $customizeFound = $this->customizeCollectionFactory->create()
                        ->addFieldToFilter("review_id", $reviewDataObject->getId())
                        ->getFirstItem();
        if ($customizeFound) {
            $reviewDataObject->setCustomize($customizeFound);
        }
        return $reviewDataObject;
    }

    /**
     * add galleries review
     *
     * @param mixed|array|ReviewInterface
     * @return mixed|array|ReviewInterface
     */
    protected function addGalleries($reviewDataObject)
    {
        $galleriesFound = $this->galleryCollectionFactory->create()
                    ->addFieldToFilter("review_id", $reviewDataObject->getId())
                    ->getFirstItem();
        if ($galleriesFound) {
            $images = $this->helperData->getGalleryImages($galleriesFound);
            $galleriesFound->setImages($images);

            $imagesObjectArray = [];
            foreach ($images as $_image) {
                $imagesObjectArray[] = $this->dataImageFactory->create()
                                        ->setFullPath($_image)
                                        ->setResizedPath($_image);
            }
            //$reviewDataObject->setGalleries($galleriesFound);
            $reviewDataObject->setImages($imagesObjectArray);
        }
        return $reviewDataObject;
    }

    /**
     * add replies review
     *
     * @param mixed|array|ReviewInterface
     * @return mixed|array|ReviewInterface
     */
    protected function addReply($reviewDataObject)
    {
        $replyCollection = $this->replyCollectionFactory->create()
                    ->addFieldToFilter("review_id", $reviewDataObject->getId())
                    ->addOrder("created_at", "DESC")
                    ->setPageSize(10)
                    ->setCurPage(1);

        if ($replyCollection->count()) {
            $reviewDataObject->setComments($replyCollection->getItems());
            $reviewDataObject->setReplyTotal($replyCollection->count());
        }
        return $reviewDataObject;
    }
}
