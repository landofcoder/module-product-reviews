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

use Lof\ProductReviews\Api\Data\DetailedSummaryInterface;
use Lof\ProductReviews\Api\Data\DetailedSummaryInterfaceFactory;
use Lof\ProductReviews\Model\ResourceModel\CustomReview as CustomReviewResource;

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
