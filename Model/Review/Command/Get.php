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
 * @copyright  Copyright (c) 2022 Hgati (https://hgati.com/)
 * @license    https://hgati.com/terms
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Model\Review\Command;

use Hgati\ProductReviews\Api\Data\ReviewInterface;
use Hgati\ProductReviews\Model\Converter\Review\ToDataModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Magento\Review\Model\ReviewFactory;
use Magento\Review\Model\Review;
use Hgati\ProductReviews\Helper\Data as HelperData;
use Hgati\ProductReviews\Api\Data\GalleryInterfaceFactory;
use Hgati\ProductReviews\Api\Data\CustomizeInterfaceFactory;
use Hgati\ProductReviews\Api\Data\ReplyInterfaceFactory;
use Hgati\ProductReviews\Api\Data\ImageInterfaceFactory;
use Hgati\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollectionFactory;
use Hgati\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollectionFactory;
use Hgati\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory as ReviewReplyCollectionFactory;

/**
 * @inheritdoc
 */
class Get implements GetInterface
{
    /**
     * @var ToDataModel
     */
    private $toDataModelConverter;

    /**
     * @var ReviewResource
     */
    private $reviewResource;

    /**
     * @var ReviewFactory
     */
    private $reviewFactory;

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
     * Get constructor.
     *
     * @param ReviewFactory $reviewFactory
     * @param ToDataModel $toDataModelConvert
     * @param ReviewResource $reviewResource,
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
        ReviewFactory $reviewFactory,
        ToDataModel $toDataModelConvert,
        ReviewResource $reviewResource,
        GalleryInterfaceFactory $dataGalleryFactory,
        GalleryCollectionFactory $galleryCollectionFactory,
        CustomizeInterfaceFactory $dataCustomizeFactory,
        CustomReviewCollectionFactory $customizeCollectionFactory,
        ReplyInterfaceFactory $dataReplyFactory,
        ReviewReplyCollectionFactory $replyCollectionFactory,
        HelperData $helperData,
        ImageInterfaceFactory $dataImageFactory
    ) {
        $this->reviewFactory = $reviewFactory;
        $this->toDataModelConverter = $toDataModelConvert;
        $this->reviewResource = $reviewResource;
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
     * @param int $reviewId
     * @param bool $moreInfo
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $reviewId, bool $moreInfo = true): ReviewInterface
    {
        /** @var Review $reviewModel */
        $reviewModel = $this->reviewFactory->create();
        $this->reviewResource->load($reviewModel, $reviewId);

        if (null === $reviewModel->getId()) {
            throw new NoSuchEntityException(
                __('Review with id "%value" does not exist.', ['value' => $reviewId])
            );
        }

        $reviewModel = $this->toDataModelConverter->toDataModel($reviewModel);

        if ($moreInfo) {
            $reviewModel = $this->addCustomize($reviewModel);
            $reviewModel = $this->addGalleries($reviewModel);
            $reviewModel = $this->addReply($reviewModel);
            $reviewModel = $this->helperData->mappingReviewData($reviewModel);
        }

        return $reviewModel;
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     * @param bool $moreInfo
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface
     * @throws NoSuchEntityException
     */
    public function executeByCustomer(int $customerId, int $reviewId, bool $moreInfo = true): ReviewInterface
    {
        return $this->getReviewByCustomer($customerId, $reviewId, $moreInfo, true);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     * @param bool $moreInfo
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface
     * @throws NoSuchEntityException
     */
    public function executeByCustomerId(int $customerId, int $reviewId, bool $moreInfo = true): ReviewInterface
    {
       return $this->getReviewByCustomer($customerId, $reviewId, $moreInfo, false);
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $reviewId
     * @param bool $moreInfo
     * @param bool $checkCustomer
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface
     * @throws NoSuchEntityException
     */
    protected function getReviewByCustomer(int $customerId, int $reviewId, bool $moreInfo = true, bool $checkCustomer = true): ReviewInterface
    {
        /** @var Review $reviewModel */
        $reviewModel = $this->reviewFactory->create();
        $this->reviewResource->load($reviewModel, $reviewId);

        if (null === $reviewModel->getId()) {
            throw new NoSuchEntityException(
                __('Review with id "%value" does not exist.', ['value' => $reviewId])
            );
        }

        if ($checkCustomer && (int)$customerId !== (int)$reviewModel->getCustomerId()) {
            throw new NoSuchEntityException(
                __('Review with id "%value" does not exist for this customer.', ['value' => $reviewId])
            );
        }

        $reviewModel = $this->toDataModelConverter->toDataModel($reviewModel);

        if ($moreInfo) {
            $reviewModel = $this->addCustomize($reviewModel);
            $reviewModel = $this->addGalleries($reviewModel);
            $reviewModel = $this->addReply($reviewModel);
            $reviewModel = $this->helperData->mappingReviewData($reviewModel);
        }
        return $reviewModel;
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
