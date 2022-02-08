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
use Lof\ProductReviews\Model\Converter\Review\ToDataModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Magento\Review\Model\ReviewFactory;
use Magento\Review\Model\Review;

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
     * Get constructor.
     *
     * @param ReviewFactory $reviewFactory
     * @param ToDataModel $toDataModelConvert
     * @param ReviewResource $reviewResource
     */
    public function __construct(
        ReviewFactory $reviewFactory,
        ToDataModel $toDataModelConvert,
        ReviewResource $reviewResource
    ) {
        $this->reviewFactory = $reviewFactory;
        $this->toDataModelConverter = $toDataModelConvert;
        $this->reviewResource = $reviewResource;
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return ReviewInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $reviewId): ReviewInterface
    {
        /** @var Review $reviewModel */
        $reviewModel = $this->reviewFactory->create();
        $this->reviewResource->load($reviewModel, $reviewId);

        if (null === $reviewModel->getId()) {
            throw new NoSuchEntityException(
                __('Review with id "%value" does not exist.', ['value' => $reviewId])
            );
        }

        return $this->toDataModelConverter->toDataModel($reviewModel);
    }
}
