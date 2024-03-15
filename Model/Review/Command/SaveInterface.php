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

use Hgati\ProductReviews\Api\Data\ReviewInterface;
use Hgati\ProductReviews\Validation\ValidationException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Save review data command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial GetList call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \Hgati\ProductReviews\Api\ReviewRepositoryInterface
 * @api
 */
interface SaveInterface
{
    /**
     * Save Review
     *
     * @param ReviewInterface $dataModel
     *
     * @return \Hgati\ProductReviews\Api\Data\ReviewInterface
     *
     * @throws ValidationException
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function execute(ReviewInterface $dataModel): ReviewInterface;
}
