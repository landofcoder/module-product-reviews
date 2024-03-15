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

use Hgati\ProductReviews\Api\Data\ReplyInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Hgati\ProductReviews\Model\ResourceModel\ReviewReply as ReplyResource;
use Hgati\ProductReviews\Model\ReviewReplyFactory;
use Hgati\ProductReviews\Validation\ValidationException;

/**
 * @inheritdoc
 */
class ReviewReply implements ReviewReplyInterface
{
    /**
     * @var ReplyResource
     */
    private $replyResource;

    /**
     * @var ReviewReplyFactory
     */
    private $replyFactory;

    /**
     * Save Review Reply constructor.
     *
     * @param ReplyResource $replyResource
     * @param ReviewReplyFactory $replyFactory
     */
    public function __construct(
        ReplyResource $replyResource,
        ReviewReplyFactory $replyFactory
    ) {
        $this->replyResource = $replyResource;
        $this->replyFactory = $replyFactory;
    }

    /**
     * Save Review
     *
     * @param ReplyInterface $dataModel
     *
     * @return ReplyInterface
     * @throws ValidationException
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     */
    public function execute(ReplyInterface $dataModel): ReplyInterface
    {
        if ($parentId = $dataModel->getParentReplyId()) {
            $parentModel = $this->replyFactory->create();
            $this->replyResource->load($parentModel, $parentId);
            if (!$parentModel->getId() || ($parentModel->getReviewId() !== $dataModel->getReviewId())) {
                $dataModel->setParentReplyId(null);
            }
        }
        $this->replyResource->save($dataModel);
        $this->replyResource->load($dataModel, $dataModel->getId());

        return $dataModel;
    }

}
