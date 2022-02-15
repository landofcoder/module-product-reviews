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
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Lof\ProductReviews\Validation\ValidationException;
use Lof\ProductReviews\Model\ReviewValidatorInterface;
use Magento\Review\Model\Review;
use Magento\Store\Model\StoreManagerInterface;
use Lof\ProductReviews\Model\Review\Rating\SaveHandler;

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
     * Save constructor.
     *
     * @param ReviewValidatorInterface $reviewValidator
     * @param ToModel $toModelConverter
     * @param ToDataModel $toDataModelConvert
     * @param StoreManagerInterface $storeManager
     * @param ReviewResource $reviewResource
     * @param SaveHandler $ratingSaveHandler
     * @param SummaryRateInterface $summaryRateCommand
     */
    public function __construct(
        ReviewValidatorInterface $reviewValidator,
        ToModel $toModelConverter,
        ToDataModel $toDataModelConvert,
        StoreManagerInterface $storeManager,
        ReviewResource $reviewResource,
        SaveHandler $ratingSaveHandler,
        SummaryRateInterface $summaryRateCommand
    ) {
        $this->reviewValidator = $reviewValidator;
        $this->toDataModelConverter = $toDataModelConvert;
        $this->toModelConverter = $toModelConverter;
        $this->reviewResource = $reviewResource;
        $this->storeManager = $storeManager;
        $this->ratingSaveHandler = $ratingSaveHandler;
        $this->summaryRateCommand = $summaryRateCommand;
    }

    /**
     * Save Review
     *
     * @param ReviewInterface $dataModel
     *
     * @return ReviewInterface
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

        $product_id = $model->getEntityPkValue();
        $sku = "";
        $this->summaryRateCommand->execute($sku, $product_id);

        return $this->toDataModelConverter->toDataModel($model);
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
