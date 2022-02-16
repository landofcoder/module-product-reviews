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

namespace Lof\ProductReviews\Model\Converter\Review;

use Lof\ProductReviews\Api\Data\ReviewInterface;
use Lof\ProductReviews\Api\Data\ReviewInterfaceFactory;
use Lof\ProductReviews\Model\Review\ReviewTypeResolverInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject\Copy as ObjectCopyService;
use Magento\Review\Model\Review;
use Lof\ProductReviews\Model\Review\Rating\LoadHandler as RatingLoadHandler;

/**
 * Class ToDataModel convert Review Model to Review Data Object
 */
class ToDataModel
{
    /**
     * @var RatingLoadHandler
     */
    private $ratingLoadHandler;

    /**
     * @var ReviewInterfaceFactory
     */
    private $reviewFactory;

    /**
     * @var ObjectCopyService
     */
    private $objectCopyService;

    /**
     * @var ReviewTypeResolverInterface
     */
    private $reviewTypeResolver;

    /**
     * ToDataModel constructor.
     *
     * @param RatingLoadHandler $ratingLoadHandler
     * @param ReviewInterfaceFactory $reviewInterfaceFactory
     * @param ReviewTypeResolverInterface $reviewTypeResolver
     * @param ObjectCopyService $objectCopyService
     */
    public function __construct(
        RatingLoadHandler $ratingLoadHandler,
        ReviewInterfaceFactory $reviewInterfaceFactory,
        ReviewTypeResolverInterface $reviewTypeResolver,
        ObjectCopyService $objectCopyService
    ) {
        $this->reviewFactory = $reviewInterfaceFactory;
        $this->objectCopyService = $objectCopyService;
        $this->reviewTypeResolver = $reviewTypeResolver;
        $this->ratingLoadHandler = $ratingLoadHandler;
    }

    /**
     * Convert Review to Data Object
     *
     * @param Product|Review $productReview
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     */
    public function toDataModel($productReview): ReviewInterface
    {
        $reviewDataObject = $this->createReviewDataObject($productReview);
        $ratings = $this->ratingLoadHandler->execute($productReview);
        $reviewDataObject->setRatings($ratings);

        return $reviewDataObject;
    }

    /**
     * Create Review Data Object
     *
     * @param Product|Review $productReview
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     */
    private function createReviewDataObject($productReview): ReviewInterface
    {
        /** @var ReviewInterface $reviewDataObject */
        $reviewDataObject = $this->reviewFactory->create();
        $this->objectCopyService->copyFieldsetToTarget(
            'review_api_convert_review',
            'to_review_data_object',
            $productReview,
            $reviewDataObject
        );
        $reviewDataObject->setReviewType($this->reviewTypeResolver->getReviewType($productReview));
        $reviewDataObject->setReviewEntity(Review::ENTITY_PRODUCT_CODE);

        return $reviewDataObject;
    }
}
