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

use Hgati\ProductReviews\Api\PostProductReviewsInterface;
use Hgati\ProductReviews\Helper\Data as HelperData;
use Hgati\ProductReviews\Api\ReviewRepositoryInterface;
use Hgati\ProductReviews\Api\CustomizeRepositoryInterface;
use Hgati\ProductReviews\Api\Data\ReviewInterface;
use Hgati\ProductReviews\Model\Review\Command\VerifyBuyerInterface;
use Hgati\ProductReviews\Model\Review\Command\SaveInterface;
use Hgati\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory as ReportHistoryCollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class PostProductReviews post product reviews by product sku
 */
class PostProductReviews implements PostProductReviewsInterface
{
    const STATUS_PENDING = 2;
   /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var CustomizeRepositoryInterface
     */
    protected $customizeRepository;

    /**
     * @var ReviewRepositoryInterface
     */
    protected $reviewRepository;

    /**
     * @var ReportHistoryCollectionFactory
     */
    protected $reportCollectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var VerifyBuyerInterface
     */
    protected $commandVerifyBuyer;

    /**
     * @var SaveInterface
     */
    protected $commandSaveReview;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param HelperData $helperData
     * @param CustomizeRepositoryInterface $customizeRepository
     * @param ReviewRepositoryInterface $reviewRepository
     * @param ReportHistoryCollectionFactory $reportCollectionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param VerifyBuyerInterface $commandVerifyBuyer
     * @param SaveInterface $commandSaveReview
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        HelperData $helperData,
        CustomizeRepositoryInterface $customizeRepository,
        ReviewRepositoryInterface $reviewRepository,
        ReportHistoryCollectionFactory $reportCollectionFactory,
        ProductRepositoryInterface $productRepository,
        VerifyBuyerInterface $commandVerifyBuyer,
        SaveInterface $commandSaveReview,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->helperData = $helperData;
        $this->customizeRepository = $customizeRepository;
        $this->reviewRepository = $reviewRepository;
        $this->reportCollectionFactory = $reportCollectionFactory;
        $this->productRepository = $productRepository;
        $this->commandVerifyBuyer = $commandVerifyBuyer;
        $this->commandSaveReview = $commandSaveReview;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @var inheritdoc
     */
    public function execute(int $customerId, string $sku, ReviewInterface $review): ReviewInterface
    {
        $productId = $this->getProductIdBySku($sku);
        if (!$productId) {
            throw new NoSuchEntityException(__('Product with SKU "%1" does not exist.', $sku));
        }
        $verify_purchased_code = $this->helperData->getConfig("hgati_review_settings/verify_purchased_code");
        $required_verify_purchased = $this->helperData->getConfig("hgati_review_settings/required_verify_purchased");
        $isVerified = false;
        if ($verify_purchased_code && $required_verify_purchased && $this->helperData->getAutoVerifyConfig() && $customerId) {
            $isVerified = $this->commandVerifyBuyer->execute($customerId, "", $productId, "");
            if (!$isVerified) {
                throw new NoSuchEntityException(__('You should purchased the product with SKU "%1" before review.', $sku));
            }
        }
        $countryCode = "";
        if ($customerId) {
            $customerData = $this->customerRepository->getById($customerId);
            $defaultAddressId = $customerData->getDefaultBilling();
            $addresses = $customerData->getAddresses();
            if ($addresses && $defaultAddressId) {
                $foundAddress = null;
                foreach ($addresses as $_address) {
                    if ($_address->getId() == (int)$defaultAddressId) {
                        $foundAddress = $_address;
                        break;
                    }
                }
                $countryCode = $foundAddress ? $foundAddress->getCountryId() : "";
            }
            $review->setCustomerId($customerId);
            $review->setGuestEmail($customerData->getEmail());
        }
        if (!$review->getGuestEmail()) {
            throw new NoSuchEntityException(__('Missing required Field Guest Email Address.'));
        }
        $review->setReviewEntity("product");
        $review->setCountry($countryCode);
        $review->setId(0);
        $review->setReviewStatus(self::STATUS_PENDING);
        $review->setIsRecommended(false);
        $review->setAnswer("");
        $review->setPlusReview(0);
        $review->setMinusReview(0);
        $review->setVerifiedBuyer( $isVerified ? 1 : 0);
        $review->setEntityPkValue((int)$productId);

        return $this->commandSaveReview->execute($review);
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
