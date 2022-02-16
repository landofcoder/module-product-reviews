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
use Lof\ProductReviews\Validation\ValidationException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Save review data command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial GetList call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \Lof\ProductReviews\Api\ReviewRepositoryInterface
 * @api
 */
interface SaveInterface
{
    /**
     * Save Review
     *
     * @param ReviewInterface $dataModel
     *
     * @return \Lof\ProductReviews\Api\Data\ReviewInterface
     *
     * @throws ValidationException
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function execute(ReviewInterface $dataModel): ReviewInterface;
}
