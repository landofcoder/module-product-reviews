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

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\GetProductReviewsInterface;
use Lof\ProductReviews\Api\Data\GalleryInterfaceFactory;
use Lof\ProductReviews\Api\Data\CustomizeInterfaceFactory;
use Lof\ProductReviews\Api\Data\ReplyInterfaceFactory;
use Lof\ProductReviews\Api\Data\ReviewDataInterfaceFactory;
use Lof\ProductReviews\Api\Data\ImageInterfaceFactory;
use Magento\Review\Model\ResourceModel\Review\Product\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory as ReviewCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollectionFactory;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory as ReviewReplyCollectionFactory;
use Lof\ProductReviews\Model\Converter\Review\ToDataModel as ReviewConverter;
use Lof\ProductReviews\Helper\Data as HelperData;
use Lof\ProductReviews\Model\Review\Command\GetSummaryInterface;
use Magento\Store\Model\StoreManagerInterface;

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
     * @var ReviewDataInterfaceFactory
     */
    protected $dataReviewDataFactory;

    /**
     * @var ImageInterfaceFactory
     */
    protected $dataImageFactory;

    /**
     * @var GetSummaryInterface
     */
    protected $getSummaryCommand;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

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
     * @param ReviewDataInterfaceFactory $dataReviewDataFactory
     * @param GetSummaryInterface $getSummaryCommand
     * @param ImageInterfaceFactory $dataImageFactory
     * @param StoreManagerInterface $storeManager
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
        HelperData $helperData,
        ReviewDataInterfaceFactory $dataReviewDataFactory,
        GetSummaryInterface $getSummaryCommand,
        ImageInterfaceFactory $dataImageFactory,
        StoreManagerInterface $storeManager
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
        $this->dataReviewDataFactory = $dataReviewDataFactory;
        $this->getSummaryCommand = $getSummaryCommand;
        $this->dataImageFactory = $dataImageFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     *
     * @param string $sku
     * @param int $limit
     * @param int $page
     *
     * @return mixed|array|\Lof\ProductReviews\Api\Data\ReviewDataInterface
     */
    public function execute(string $sku, int $limit = 0, int $page = 0)
    {
        /** @var ReviewCollection $collection */
        $collection = $this->reviewCollectionFactory->create();
        $collection->addStoreData();
        $collection->addFieldToFilter('sku', $sku);
        $collection->addRateVotes();

        if ($limit) {
            $collection->setPageSize($limit);
        }
        if ($page) {
            $collection->setCurPage($page);
        }

        $reviews = [];
        $recommended_count = 0;
        $product_id = 0;

        /** @var \Magento\Catalog\Model\Product $productReview */
        foreach ($collection as $productReview) {
            $productReview->setCreatedAt($productReview->getReviewCreatedAt());
            $reviewDataObject = $this->reviewConverter->toDataModel($productReview);
            $reviewDataObject = $this->addCustomize($reviewDataObject);
            $reviewDataObject = $this->addGalleries($reviewDataObject);
            $reviewDataObject = $this->addReply($reviewDataObject);

            if (!$product_id) {
                $product_id = $reviewDataObject->getEntityPkValue();
            }
            if ($reviewDataObject->getCustomize()->getIsRecommended()) {
                $recommended_count++;
            }
            $reviews[] = $reviewDataObject;
        }

        $storeId = $this->storeManager->getStore()->getId();
        $detailedSummary = $this->getSummaryCommand->execute($product_id, $storeId);

        $reviews_count = $detailedSummary->getReviewsCount();
        $rating_summary = $detailedSummary->getRatingSummary();
        $recomended_percent = $reviews_count ? (($recommended_count / $reviews_count) * 100 ): 0;
        $rating_summary_value = $rating_summary * 5 / 100;
        $rating_summary_value = round ($rating_summary_value, 1);

        $responseReviewData = $this->dataReviewDataFactory->create();
        $responseReviewData->setTotalRecords($reviews_count);
        $responseReviewData->setRatingSummary($rating_summary);
        $responseReviewData->setRatingSummaryValue($rating_summary_value);
        $responseReviewData->setRecomendedPercent($recomended_percent);
        $responseReviewData->setDetailedSummary($detailedSummary);
        $responseReviewData->setItems($reviews);

        return $responseReviewData;
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
            $imagesObjectArray = [];
            foreach ($images as $_image) {
                $imagesObjectArray[] = $this->dataImageaFactory->create()
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
            $reviewDataObject->setComments($replyCollection->getItems());
            $reviewDataObject->setReplyTotal($replyCollection->count());
        }
        return $reviewDataObject;
    }
}
