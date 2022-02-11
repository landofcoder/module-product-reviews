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

use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Magento\Review\Model\ReviewFactory;
use Magento\Review\Model\Review;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * @inheritdoc
 */
class DeleteById implements DeleteByIdInterface
{
    /**
     * @var ReviewResource
     */
    private $reviewResource;

    /**
     * @var ReviewFactory
     */
    private $reviewFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Get constructor.
     *
     * @param LoggerInterface $logger
     * @param ReviewFactory $reviewFactory
     * @param ReviewResource $reviewResource
     */
    public function __construct(
        LoggerInterface $logger,
        ReviewFactory $reviewFactory,
        ReviewResource $reviewResource
    ) {
        $this->logger = $logger;
        $this->reviewFactory = $reviewFactory;
        $this->reviewResource = $reviewResource;
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return void
     *
     * @throws CouldNotDeleteException
     */
    public function execute(int $reviewId): void
    {
        /** @var Review $reviewModel */
        $reviewModel = $this->reviewFactory->create();
        $this->reviewResource->load($reviewModel, $reviewId);

        if (null === $reviewModel->getId()) {
            return;
        }

        try {
            $this->reviewResource->delete($reviewModel);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new CouldNotDeleteException(__('Could not delete Review'), $exception);
        }
    }
}
