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

namespace Hgati\ProductReviews\Model\Converter\Review;
;
use Hgati\ProductReviews\Api\Data\ReviewInterface as ReviewData;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Magento\Review\Model\Review;
use Magento\Review\Model\ReviewFactory;

/**
 * Class ToModel convert Review Data Object to Review Model
 */
class ToModel
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var ReviewFactory
     */
    private $reviewFactory;

    /**
     * @var ReviewResource
     */
    private $resourceModel;

    /**
     * ToModel constructor.
     *
     * @param ReviewResource $reviewResource
     * @param ReviewFactory $reviewFactory
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        ReviewResource $reviewResource,
        ReviewFactory $reviewFactory,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->reviewFactory = $reviewFactory;
        $this->resourceModel = $reviewResource;
    }

    /**
     * Convert Review Data Object to Review Model
     *
     * @param ReviewData $dataModel
     *
     * @return Review
     *
     * @throws NoSuchEntityException
     */
    public function toModel(ReviewData $dataModel): Review
    {
        $reviewId = (int) $dataModel->getId();
        $reviewModel = $this->getReviewModel($reviewId);
        $mergedData = $this->mergeReviewData($reviewModel, $dataModel);
        $reviewModel->setData($mergedData);

        $this->mapFields($reviewModel, $dataModel);

        return $reviewModel;
    }

    /**
     * Merge Review data from current Review and review data object
     *
     * @param Review $reviewModel
     * @param ReviewData $reviewData
     *
     * @return mixed|array
     */
    private function mergeReviewData(Review $reviewModel, ReviewData $reviewData): array
    {
        $data = $this->dataObjectProcessor->buildOutputDataArray(
            $reviewData,
            ReviewData::class
        );

        $modelData = $reviewModel->getData();

        return array_merge($modelData, $data);
    }

    /**
     * Get Review Model
     *
     * @param int $reviewId
     *
     * @return Review
     *
     * @throws NoSuchEntityException
     */
    private function getReviewModel(int $reviewId): Review
    {
        if ($reviewId) {
            /** @var Review $reviewModel */
            $reviewModel = $this->reviewFactory->create();
            $this->resourceModel->load($reviewModel, $reviewId);

            if ($reviewModel->getId() === null) {
                throw new NoSuchEntityException(
                    __('Review with id "%value" does not exist.', ['value' => $reviewId])
                );
            }

            return $reviewModel;
        }

        return $this->reviewFactory->create();
    }

    /**
     * Map fields from Data Object to Review Model
     *
     * @param Review $reviewModel
     * @param ReviewData $reviewData
     *
     * @return void
     */
    private function mapFields(Review $reviewModel, ReviewData $reviewData): void
    {
        $reviewModel->setEntityId($reviewModel->getEntityIdByCode($reviewData->getReviewEntity()));
        $reviewModel->setStatusId($reviewData->getReviewStatus());
        $reviewModel->setStores($reviewData->getStores());

        if (! $reviewModel->getStatusId()) {
            $reviewModel->setStatusId(Review::STATUS_PENDING);
        }
    }
}
