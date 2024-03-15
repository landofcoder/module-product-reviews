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

namespace Hgati\ProductReviews\Model;

use Hgati\ProductReviews\Api\UnLikeRepositoryInterface;
use Hgati\ProductReviews\Api\ReviewRepositoryInterface;
use Hgati\ProductReviews\Api\CustomizeRepositoryInterface;
use Hgati\ProductReviews\Helper\Data as HelperData;
use Hgati\ProductReviews\Api\Data\ReviewInterface;
use Hgati\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory as ReportHistoryCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class UnLikeRepository unlike a review
 */
class UnLikeRepository implements UnLikeRepositoryInterface
{

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var CustomizeRepositoryInterface
     */
    protected $customizeRepository;

    /**
     * @var ReviewRepositoryInterface
     */
    protected $reviewRepository;

    /**
     * @var ReportHistoryCollectionFactory
     */
    protected $reportCollectionFactory;

    /**
     * @param HelperData $helperData
     * @param CustomizeRepositoryInterface $customizeRepository
     * @param ReviewRepositoryInterface $reviewRepository
     * @param ReportHistoryCollectionFactory $reportCollectionFactory
     */
    public function __construct(
        HelperData $helperData,
        CustomizeRepositoryInterface $customizeRepository,
        ReviewRepositoryInterface $reviewRepository,
        ReportHistoryCollectionFactory $reportCollectionFactory
    ) {
        $this->helperData = $helperData;
        $this->customizeRepository = $customizeRepository;
        $this->reviewRepository = $reviewRepository;
        $this->reportCollectionFactory = $reportCollectionFactory;
    }

    /**
     * @var inheritdoc
     */
    public function execute(int $customerId, int $reviewId): ReviewInterface
    {
        $review = $this->reviewRepository->getReviewByCustomer($customerId, $reviewId);
        if (!$review || !$review->getId()) {
            throw new NoSuchEntityException(__('Review with id "%1" does not exist.', $reviewId));
        }
        if (!$this->checkExistReport($customerId, $reviewId)) {
            $customize = $review->getCustomize();

            $total = (int)$customize->getTotalHelpful() + 1;
            $unhelpful = (int)$customize->getCountUnhelpful() + 1;

            $customize->setCountUnhelpful($unhelpful);
            $customize->setTotalHelpful($total);

            $newCustomize = $this->customizeRepository->save($customize);

            $review->setCustomize($newCustomize);
            $review = $this->helperData->mappingReviewData($review);
        } else {
            throw new CouldNotSaveException(__(
                'You marked unlike the review id %1 before.', $reviewId
            ));
        }
        return $review;
    }

    /**
     * Check exists report
     *
     * @param int $customerId
     * @param int $reviewId
     * @return bool
     */
    public function checkExistReport(int $customerId, int $reviewId): bool
    {
        $collection = $this->reportCollectionFactory->create()
                        ->addFieldToFilter("review_id", $reviewId)
                        ->addFieldToFilter("customer_id", $customerId)
                        ->addFieldToFilter("rate_type", "unhelpful");

        if ($collection->getSize()) {
            return true;
        }
        return false;
    }

}
