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

namespace Lof\ProductReviews\Model\Converter;

use Lof\ProductReviews\Api\Data\RatingVoteInterface as RatingDataInterface;
use Lof\ProductReviews\Api\Data\RatingVoteInterfaceFactory as RatingDataFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class RatingVote
 */
class RatingVote
{
    /**
     * @var RatingDataFactory
     */
    private $ratingDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * ToDataModel constructor.
     *
     * @param DataObjectHelper $dataObjectHelper
     * @param RatingDataFactory $ratingDataFactory
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        RatingDataFactory $ratingDataFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->ratingDataFactory = $ratingDataFactory;
    }

    /**
     * Retrieve Rating
     *
     * @param array $data
     *
     * @return \Lof\ProductReviews\Api\Data\RatingVoteInterface
     */
    public function arrayToDataModel(array $data): RatingDataInterface
    {
        /** @var RatingDataInterface $rating */
        $rating = $this->ratingDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $rating,
            $data,
            RatingDataInterface::class
        );

        return $rating;
    }
}
