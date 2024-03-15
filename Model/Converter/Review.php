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

namespace Hgati\ProductReviews\Model\Converter;

use Hgati\ProductReviews\Api\Data\ReviewInterface as ReviewDataInterface;
use Hgati\ProductReviews\Api\Data\ReviewInterfaceFactory as RreviewDataFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Review
 */
class Review
{
    /**
     * @var RreviewDataFactory
     */
    private $reviewDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * ToDataModel constructor.
     *
     * @param DataObjectHelper $dataObjectHelper
     * @param RreviewDataFactory $reviewDataFactory
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        RreviewDataFactory $reviewDataFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->reviewDataFactory = $reviewDataFactory;
    }

    /**
     * Retrieve Review object model
     *
     * @param array $data
     *
     * @return ReviewDataInterface
     */
    public function arrayToDataModel(array $data): ReviewDataInterface
    {
        /** @var ReviewDataInterface $review */
        $review = $this->reviewDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $review,
            $data,
            ReviewDataInterface::class
        );

        return $review;
    }
}
