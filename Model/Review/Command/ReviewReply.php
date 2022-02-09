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

use Lof\ProductReviews\Api\Data\ReplyInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply as ReplyResource;
use Lof\ProductReviews\Validation\ValidationException;

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
     * Save Review Reply constructor.
     *
     * @param ReplyResource $replyResource
     */
    public function __construct(
        ReplyResource $replyResource
    ) {
        $this->replyResource = $replyResource;
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
        $this->replyResource->save($dataModel);
        $this->replyResource->load($dataModel, $dataModel->getId());

        return $dataModel;
    }

}
