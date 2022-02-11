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

use Lof\ProductReviews\Model\ResourceModel\ReviewReply as ReviewReplyResource;
use Magento\Review\Model\ReviewReplyFactory;
use Magento\Review\Model\ReviewReply;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * @inheritdoc
 */
class DeleteReplyById implements DeleteReplyByIdInterface
{
    /**
     * @var ReviewReplyResource
     */
    private $reviewReplyResource;

    /**
     * @var ReviewReplyFactory
     */
    private $reviewReplyFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Get constructor.
     *
     * @param LoggerInterface $logger
     * @param ReviewReplyFactory $reviewReplyFactory
     * @param ReviewReplyResource $reviewReplyResource
     */
    public function __construct(
        LoggerInterface $logger,
        ReviewReplyFactory $reviewReplyFactory,
        ReviewReplyResource $reviewReplyResource
    ) {
        $this->logger = $logger;
        $this->reviewReplyFactory = $reviewReplyFactory;
        $this->reviewReplyResource = $reviewReplyResource;
    }

    /**
     * @inheritdoc
     *
     * @param int $replyId
     *
     * @return void
     *
     * @throws CouldNotDeleteException
     */
    public function execute(int $replyId): void
    {
        /** @var ReviewReply $reviewReplyModel */
        $reviewReplyModel = $this->reviewReplyFactory->create();
        $this->reviewReplyResource->load($reviewReplyModel, $replyId);

        if (null === $reviewReplyModel->getId()) {
            return;
        }

        try {
            $this->reviewReplyResource->delete($reviewReplyModel);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new CouldNotDeleteException(__('Could not delete Review Reply'), $exception);
        }
    }
}
