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

use Hgati\ProductReviews\Api\Data\DetailedSummaryInterface;
use Hgati\ProductReviews\Api\Data\DetailedSummaryInterfaceFactory;
use Hgati\ProductReviews\Model\ResourceModel\CustomReview as CustomReviewResource;

/**
 * @inheritdoc
 */
class GetSummary implements GetSummaryInterface
{

    /**
     * @var CustomReviewResource
     */
    private $customerReviewResource;

    /**
     * @var DetailedSummaryInterfaceFactory
     */
    protected $detailedSummaryFactory;

    /**
     * Get constructor.
     *
     * @param CustomReviewResource $customerReviewResource
     * @param DetailedSummaryInterfaceFactory $detailedSummaryFactory
     */
    public function __construct(
        CustomReviewResource $customerReviewResource,
        DetailedSummaryInterfaceFactory $detailedSummaryFactory
    ) {
        $this->customerReviewResource = $customerReviewResource;
        $this->detailedSummaryFactory = $detailedSummaryFactory;
    }

    /**
     * @inheritdoc
     *
     * @param int $productId
     * @param int $storeId
     * @return DetailedSummaryInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $productId, int $storeId = 0): DetailedSummaryInterface
    {
        $detailedSummary = $this->detailedSummaryFactory->create();
        $foundRecords = $this->customerReviewResource->getReviewSummary($productId, $storeId);
        if ($foundRecords) {
            $detailedSummary->setOne((int)$foundRecords["rate_one"]);
            $detailedSummary->setTwo((int)$foundRecords["rate_two"]);
            $detailedSummary->setThree((int)$foundRecords["rate_three"]);
            $detailedSummary->setFour((int)$foundRecords["rate_four"]);
            $detailedSummary->setFive((int)$foundRecords["rate_five"]);
            $detailedSummary->setRatingSummary($foundRecords["rating_summary"]);
            $detailedSummary->setReviewsCount((int)$foundRecords["reviews_count"]);
        }
        return $detailedSummary;
    }

}
