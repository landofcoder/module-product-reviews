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

namespace Hgati\ProductReviews\Model;

use Hgati\ProductReviews\Api\Data\VerifyInterface;
use Hgati\ProductReviews\Api\VerifyRepositoryInterface;
use Hgati\ProductReviews\Helper\Data as HelperData;
use Hgati\ProductReviews\Model\Review\Command\VerifyBuyerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class VerifyRepository unlike a review
 */
class VerifyRepository implements VerifyRepositoryInterface
{
    /**
     * @var VerifyBuyerInterface
     */
    protected $commandVerifyBuyer;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * constructor.
     * @param HelperData $helperData
     * @param VerifyBuyerInterface $commandVerifyBuyer
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        HelperData $helperData,
        VerifyBuyerInterface $commandVerifyBuyer,
        ProductRepositoryInterface $productRepository
    ) {
        $this->helperData = $helperData;
        $this->commandVerifyBuyer = $commandVerifyBuyer;
        $this->productRepository = $productRepository;
    }

    /**
     * @var inheritdoc
     */
    public function execute(int $customerId, VerifyInterface $data): bool
    {
        $verify_purchased_code = $this->helperData->getConfig("hgati_review_settings/verify_purchased_code");
        $required_verify_purchased = $this->helperData->getConfig("hgati_review_settings/required_verify_purchased");
        if ($verify_purchased_code && $required_verify_purchased) {
            $productId = $this->getProductIdBySku($data->getSku());
            if (!$productId || !$data->getOrderId()) {
                return false;
            } else {
                return $this->commandVerifyBuyer->execute($customerId, $data->getEmail(), $productId, $data->getOrderId());
            }
        }
        return true;
    }

    /**
     * get product id by sku
     *
     * @param string $sku
     * @return int
     */
    protected function getProductIdBySku(string $sku)
    {
        if (!$sku) {
            return 0;
        }
        $product = $this->productRepository->get($sku);
        return $product ? $product->getId() : 0;
    }
}
