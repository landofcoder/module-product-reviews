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

use Lof\ProductReviews\Api\Data\ReviewInterface;
use Lof\ProductReviews\Model\Converter\Review\ToModel;
use Lof\ProductReviews\Model\Converter\Review\ToDataModel;
use Lof\ProductReviews\Model\Review\Rating\SaveHandler;
use Lof\ProductReviews\Validation\ValidationException;
use Lof\ProductReviews\Model\ReviewValidatorInterface;
use Lof\ProductReviews\Model\CustomReviewFactory;
use Lof\ProductReviews\Model\GalleryFactory;
use Lof\ProductReviews\Helper\Data as HelperData;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Magento\Review\Model\Review;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @inheritdoc
 */
class Save implements SaveInterface
{
    /**
     * @var ToModel
     */
    private $toModelConverter;

    /**
     * @var ToDataModel
     */
    private $toDataModelConverter;

    /**
     * @var ReviewResource
     */
    private $reviewResource;

    /**
     * @var ReviewValidatorInterface
     */
    private $reviewValidator;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SaveHandler
     */
    private $ratingSaveHandler;

    /**
     * @var SummaryRateInterface
     */
    private $summaryRateCommand;

    /**
     * @var CustomReviewFactory
     */
    private $customReviewFactory;

    /**
     * @var GalleryFactory
     */
    private $galleryFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var GetInterface
     */
    protected $getReviewCommand;

    /**
     * Save constructor.
     *
     * @param ReviewValidatorInterface $reviewValidator
     * @param ToModel $toModelConverter
     * @param ToDataModel $toDataModelConvert
     * @param StoreManagerInterface $storeManager
     * @param ReviewResource $reviewResource
     * @param SaveHandler $ratingSaveHandler
     * @param SummaryRateInterface $summaryRateCommand
     * @param CustomReviewFactory $customReviewFactory
     * @param GalleryFactory $galleryFactory
     * @param HelperData $helperData
     * @param GetInterface $getReviewCommand
     */
    public function __construct(
        ReviewValidatorInterface $reviewValidator,
        ToModel $toModelConverter,
        ToDataModel $toDataModelConvert,
        StoreManagerInterface $storeManager,
        ReviewResource $reviewResource,
        SaveHandler $ratingSaveHandler,
        SummaryRateInterface $summaryRateCommand,
        CustomReviewFactory $customReviewFactory,
        GalleryFactory $galleryFactory,
        HelperData $helperData,
        GetInterface $getReviewCommand
    ) {
        $this->reviewValidator = $reviewValidator;
        $this->toDataModelConverter = $toDataModelConvert;
        $this->toModelConverter = $toModelConverter;
        $this->reviewResource = $reviewResource;
        $this->storeManager = $storeManager;
        $this->ratingSaveHandler = $ratingSaveHandler;
        $this->summaryRateCommand = $summaryRateCommand;
        $this->galleryFactory = $galleryFactory;
        $this->customReviewFactory = $customReviewFactory;
        $this->helperData = $helperData;
        $this->getReviewCommand = $getReviewCommand;
    }

    /**
     * Save Review
     *
     * @param ReviewInterface $dataModel
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     * @throws ValidationException
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     */
    public function execute(ReviewInterface $dataModel): ReviewInterface
    {
        $this->updateStores($dataModel);
        $validationResult = $this->reviewValidator->validate($dataModel);

        if (!$validationResult->isValid()) {
            $msg = implode(' ', $validationResult->getErrors());
            throw new ValidationException(__($msg), null, 0, $validationResult);
        }

        $model = $this->saveReview($dataModel);
        $this->reviewResource->aggregate($model);

        $this->saveExtraData($dataModel, $model);

        $product_id = $model->getEntityPkValue();
        $sku = "";
        $this->summaryRateCommand->execute($sku, (int)$product_id);

        return $this->getReviewCommand->executeByCustomerId((int)$model->getCustomerId(), (int)$model->getId());
    }

    /**
     * Save extra data like customize, gallery images
     *
     * @param ReviewInterface $dataModel
     * @param mixed|object $model
     * @return mixed|array
     */
    public function saveExtraData($dataModel, $model)
    {
        $defaultStatus = (int)$this->helperData->getConfig("lof_review_settings/default_status", 2);
        $defaultStatus = $defaultStatus ? (int)$defaultStatus : 2;
        $limitImages = $this->helperData->getConfig("lof_review_settings/limit_upload_image", 1);
        $limitImages = $limitImages ? (int)$limitImages : 1;

        $id = (int)$model->getId();
        $images = $dataModel->getImages();
        $average = $this->customReviewFactory->create()->addCountRating($id);
        $helpful = (int) $dataModel->getPlusReview();
        $unhelpful = (int) $dataModel->getMinusReview();
        $total = $helpful + $unhelpful;

        /** save custom review */
        $customizeData = $this->customReviewFactory->create()
            ->setAverage($average)
            ->setReviewId($id)
            ->setCountHelpful($helpful)
            ->setCountUnhelpful($unhelpful)
            ->setTotalHelpful($total)
            ->setEmailAddress($dataModel->getGuestEmail())
            ->setVerifiedBuyer($dataModel->getVerifiedBuyer())
            ->setCountry($dataModel->getCountry())
            ->setAdvantages($dataModel->getLikeAbout())
            ->setDisadvantages($dataModel->getNotLikeAbout())
            ->save();

        /** save gallery */
        $galleryImages = [];
        if ($images && count($images) > 0) {
            foreach ($images as $_image) {
                $galleryImages[] = $this->helperData->formatUploadImage($_image->getFullPath());
            }
        }
        $galleryData = $this->galleryFactory->create()
            ->setReviewId($id)
            ->setLabel('Gallery of Review '.$id)
            ->setValue(json_encode($galleryImages))
            ->setStatus($defaultStatus)
            ->save();

        return [
            "customize" => $customizeData,
            "gallery" => $galleryData
        ];
    }

    /**
     * Save Review
     *
     * @param ReviewInterface $dataModel
     *
     * @return Review
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     */
    private function saveReview(ReviewInterface $dataModel): Review
    {
        $model = $this->toModelConverter->toModel($dataModel);
        $this->reviewResource->save($model);
        $this->reviewResource->load($model, $model->getId());

        if ($dataModel->getStoreId() === null) {
            $dataModel->setStoreId($model->getStoreId());
        }

        $dataModel->setId($model->getId());
        $this->ratingSaveHandler->execute($dataModel);

        return $model;
    }

    /**
     * Update Review Stores
     *
     * @param ReviewInterface $dataModel
     *
     * @return void
     *
     * @throws NoSuchEntityException
     */
    private function updateStores(ReviewInterface $dataModel): void
    {
        $stores = $dataModel->getStores();

        if ($stores === null || empty($stores)) {
            $dataModel->setStores([$this->storeManager->getStore()->getId()]);
        }

        if (($dataModel->getId() === null) && ($dataModel->getStoreId() === null)) {
            $dataModel->setStoreId($this->storeManager->getStore()->getId());
        }
    }

}
