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
use Lof\ProductReviews\Api\Data\ReviewInterface;
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
     * @var bool
     */
    protected $_flag_joined = false;

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
     */
    public function execute(string $sku, string $keyword = "", int $limit = 0, int $page = 0, string $sort_by = "")
    {
        /** @var ReviewCollection $collection */
        $collection = $this->reviewCollectionFactory->create();
        $collection->addStoreData();
        $collection->addFieldToFilter('sku', $sku);

        $reviews_count = $collection->getSize();

        /** Filter by keyword */
        $collection = $this->buildFilterKeyword($collection, $keyword);
        if ($limit) {
            $collection->setPageSize($limit);
        }
        if ($page) {
            $collection->setCurPage($page);
        }
        /** Sort By */
        $collection = $this->addSortByToCollection($collection, $sort_by);
        $foundTotal = $collection->getSize();

        /** Add rate votes for collection */
        $collection->addRateVotes();

        $reviews = [];
        $recommended_count = 0;
        $product_id = 0;
        /** @var \Magento\Catalog\Model\Product $productReview */
        foreach ($collection->getItems() as $productReview) {
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
            $reviewDataObject = $this->helperData->mappingReviewData($reviewDataObject);

            $reviews[] = $reviewDataObject;
        }

        $storeId = $this->storeManager->getStore()->getId();
        $detailedSummary = $this->getSummaryCommand->execute((int)$product_id, (int)$storeId);

        //$reviews_count = $detailedSummary->getReviewsCount();
        $rating_summary = $detailedSummary->getRatingSummary();
        $recomended_percent = $reviews_count ? (($recommended_count / $reviews_count) * 100 ): 0;
        $recomended_percent = round ($recomended_percent, 1);
        $rating_summary_value = $rating_summary * 5 / 100;
        $rating_summary_value = round ($rating_summary_value, 1);

        $responseReviewData = $this->dataReviewDataFactory->create();
        $responseReviewData->setTotalRecords($reviews_count);
        $responseReviewData->setRatingSummary($rating_summary);
        $responseReviewData->setRatingSummaryValue($rating_summary_value);
        $responseReviewData->setRecomendedPercent($recomended_percent);
        $responseReviewData->setDetailedSummary($detailedSummary);
        $responseReviewData->setPageSize($limit);
        $responseReviewData->setTotalFound($foundTotal);
        $responseReviewData->setCurPage($page);
        $responseReviewData->setItems($reviews);

        return $responseReviewData;
    }

    /**
     * build search keyword for collection
     *
     * @param mixed|object|array $collection
     * @param string $sort_keywordby
     * @return mixed|object|array
     */
    public function buildFilterKeyword($collection, $keyword = "")
    {
        $keyword = trim($keyword);
        if ($keyword && strlen($keyword) >= 3) {
            $testKeyword = strtolower($keyword);
            $testKeyword = $testKeyword == "recommended" ? "is_recommended": $testKeyword;
            $testKeyword = $testKeyword == "verified" ? "verified_buyer": $testKeyword;

            if ($testKeyword == "is_recommended" || $testKeyword == "verified_buyer") {
                $collection = $this->joinCustomizeTable($collection);
                $collection->getSelect()
                            ->where("lc." . $testKeyword . " = 1");
            } else {
                $keyword = "%".$keyword."%";
                $collection->addAttributeToFilter("rdt.detail", [ "like" => $keyword]);//support rdt.detail or rdt.title
            }
        }
        return $collection;
    }

    /**
     * Add sort by for collection
     *
     * @param mixed|object|array $collection
     * @param string $sort_by
     * @return mixed|object|array
     */
    public function addSortByToCollection($collection, $sort_by = "")
    {
        $sort_by = $sort_by ? trim($sort_by) : "default";
        $sort_by = strtolower($sort_by);
        switch ($sort_by) {
            case "helpful":
                $collection = $this->joinCustomizeTable($collection);
                $collection->setOrder('count_helpful', 'DESC');
            break;
            case "rating":
                $collection = $this->joinCustomizeTable($collection);
                $collection->setOrder('average', 'DESC');
            break;
            case "recommended":
                $collection = $this->joinCustomizeTable($collection);
                $collection->setOrder('is_recommended', 'DESC');
                $collection->setDateOrder();
            break;
            case "verified":
                $collection = $this->joinCustomizeTable($collection);
                $collection->setOrder('verified_buyer', 'DESC');
                $collection->setDateOrder();
            break;
            case "latest":
                $collection->setDateOrder();
            break;
            case "oldest":
                $collection->setDateOrder('ASC');
            break;
            case "default":
            default:
            break;
        }
        return $collection;
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

    /**
     * join customize table
     *
     * @param mixed|object|array $collection
     * @return mixed|object|array
     */
    protected function joinCustomizeTable($collection)
    {
        if (!$this->_flag_joined) {
            $collection->getSelect()->joinLeft(
                ['lc' => $collection->getTable('lof_review_customize')],
                'rt.review_id = lc.review_id',
                [
                    'count_helpful',
                    'average',
                    'is_recommended',
                    'verified_buyer'
                ]
            );
            $this->_flag_joined = true;
        }
        return $collection;
    }
}
