<?php
/**
 * Copyright Divante Sp. z o.o.
 * See LICENSE_DIVANTE.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\GetProductReviewsInterface;
use Lof\ProductReviews\Api\Data\GalleryInterfaceFactory;
use Lof\ProductReviews\Api\Data\CustomizeInterfaceFactory;
use Lof\ProductReviews\Api\Data\ReplyInterfaceFactory;
use Magento\Review\Model\ResourceModel\Review\Product\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory as ReviewCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory as ReviewReplyCollectionFactory;
use Lof\ProductReviews\Model\Converter\Review\ToDataModel as ReviewConverter;
use Lof\ProductReviews\Helper\Data as HelperData;

/**
 * Class GetProductReviews load product reviews by product sku
 */
class GetProductReviews implements GetProductReviewsInterface
{
    /**
     * @var ReviewConverter
     */
    private $reviewConverter;

    /**
     * @var ReviewCollectionFactory
     */
    private $reviewCollectionFactory;

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
     * GetProductReviews constructor.
     *
     * @param ReviewConverter $reviewConverter
     * @param ReviewCollectionFactory $collectionFactory
     * @param GalleryInterfaceFactory $dataGalleryFactory
     * @param GalleryCollectionFactory $galleryCollectionFactory
     * @param CustomizeInterfaceFactory $dataCustomizeFactory
     * @param CustomReviewCollectionFactory $customizeCollectionFactory
     * @param ReplyInterfaceFactory $dataReplyFactory
     * @param ReviewReplyCollectionFactory $replyCollectionFactory
     * @param HelperData $helperData
     */
    public function __construct(
        ReviewConverter $reviewConverter,
        ReviewCollectionFactory $collectionFactory,
        GalleryInterfaceFactory $dataGalleryFactory,
        GalleryCollectionFactory $galleryCollectionFactory,
        CustomizeInterfaceFactory $dataCustomizeFactory,
        CustomReviewCollectionFactory $customizeCollectionFactory,
        ReplyInterfaceFactory $dataReplyFactory,
        ReviewReplyCollectionFactory $replyCollectionFactory,
        HelperData $helperData
    ) {
        $this->reviewConverter = $reviewConverter;
        $this->reviewCollectionFactory = $collectionFactory;
        $this->dataGalleryFactory = $dataGalleryFactory;
        $this->galleryCollectionFactory = $galleryCollectionFactory;
        $this->dataCustomizeFactory = $dataCustomizeFactory;
        $this->customizeCollectionFactory = $customizeCollectionFactory;
        $this->dataReplyFactory = $dataReplyFactory;
        $this->replyCollectionFactory = $replyCollectionFactory;
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     *
     * @param string $sku
     *
     * @return mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface[]
     */
    public function execute(string $sku)
    {
        /** @var ReviewCollection $collection */
        $collection = $this->reviewCollectionFactory->create();
        $collection->addStoreData();
        $collection->addFieldToFilter('sku', $sku);
        $collection->addRateVotes();

        $reviews = [];

        /** @var \Magento\Catalog\Model\Product $productReview */
        foreach ($collection as $productReview) {
            $productReview->setCreatedAt($productReview->getReviewCreatedAt());
            $reviewDataObject = $this->reviewConverter->toDataModel($productReview);
            $reviewDataObject = $this->addCustomize($reviewDataObject);
            $reviewDataObject = $this->addGalleries($reviewDataObject);
            $reviewDataObject = $this->addReply($reviewDataObject);
            $reviews[] = $reviewDataObject;
        }

        return $reviews;
    }

    /**
     * add customize review
     *
     * @param mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface
     * @return mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface
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
     * @param mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface
     * @return mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface
     */
    protected function addGalleries($reviewDataObject)
    {
        $galleriesFound = $this->galleryCollectionFactory->create()
                    ->addFieldToFilter("review_id", $reviewDataObject->getId())
                    ->getFirstItem();
        if ($galleriesFound) {
            $images = $this->helperData->getGalleryImages($galleriesFound);
            $galleriesFound->setImages($images);
            $reviewDataObject->setGalleries($galleriesFound);
        }
        return $reviewDataObject;
    }

    /**
     * add replies review
     *
     * @param mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface
     * @return mixed|array|\Lof\ProductReviews\Api\Data\ReviewInterface
     */
    protected function addReply($reviewDataObject)
    {
        $replyCollection = $this->replyCollectionFactory->create()
                    ->addFieldToFilter("review_id", $reviewDataObject->getId())
                    ->addOrder("created_at", "DESC")
                    ->setPageSize(10)
                    ->setCurPage(1);

        if ($replyCollection->count()) {
            $reviewDataObject->setReply($replyCollection->getItems());
            $reviewDataObject->setReplyTotal($replyCollection->count());
        }
        return $reviewDataObject;
    }
}
