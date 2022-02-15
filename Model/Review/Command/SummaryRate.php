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

use Magento\Review\Model\ResourceModel\Review\Product\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory as ReviewCollectionFactory;
use Lof\ProductReviews\Model\Converter\Review\ToDataModel as ReviewConverter;
use Lof\ProductReviews\Model\ResourceModel\CustomReview as CustomReviewResource;
use Lof\ProductReviews\Api\Data\DetailedSummaryInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Save review rating summary data command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial GetList call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \Lof\ProductReviews\Api\ReviewRepositoryInterface
 * @api
 */
class SummaryRate implements SummaryRateInterface
{
    /**
     * @var ReviewCollectionFactory
     */
    private $reviewCollectionFactory;

    /**
     * @var ReviewConverter
     */
    private $reviewConverter;

    /**
     * @var CustomReviewResource
     */
    private $customerReviewResource;

    /**
     * @var DetailedSummaryInterfaceFactory
     */
    private $dataDetailedSummaryFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * GetProductReviews constructor.
     *
     * @param ReviewConverter $reviewConverter
     * @param ReviewCollectionFactory $collectionFactory
     * @param CustomReviewResource $customerReviewResource
     * @param DetailedSummaryInterfaceFactory $dataDetailedSummaryFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ReviewConverter $reviewConverter,
        ReviewCollectionFactory $collectionFactory,
        CustomReviewResource $customerReviewResource,
        DetailedSummaryInterfaceFactory $dataDetailedSummaryFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->reviewCollectionFactory = $collectionFactory;
        $this->reviewConverter = $reviewConverter;
        $this->customerReviewResource = $customerReviewResource;
        $this->dataDetailedSummaryFactory = $dataDetailedSummaryFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * Save summary rate
     *
     * @param string $sku
     *
     * @return void
     *
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function execute(string $sku, int $product_id = 0)
    {
        if (!$sku && $product_id) {
            $sku = $this->getProductSkyById($product_id);
        }
        /** @var ReviewCollection $collection */
        $collection = $this->reviewCollectionFactory->create();
        $collection->addStoreData();
        $collection->addFieldToFilter('sku', $sku);
        $collection->addRateVotes();

        $ratings = [];
        $count_rate_one = 0;
        $count_rate_two = 0;
        $count_rate_three = 0;
        $count_rate_four = 0;
        $count_rate_five = 0;
        $entity_pk_value = 0;

        /** @var \Magento\Catalog\Model\Product $productReview */
        foreach ($collection as $productReview) {
            $productReview->setCreatedAt($productReview->getReviewCreatedAt());
            $reviewDataObject = $this->reviewConverter->toDataModel($productReview);
            $ratings[] = $reviewDataObject->getRatings();
            if (!$entity_pk_value) {
                $entity_pk_value = $reviewDataObject->getEntityPkValue();
                $entity_pk_value = (!$product_id || ($product_id && $entity_pk_value != $product_id)) ? $entity_pk_value : $product_id;
            }
        }
        if ($ratings && $entity_pk_value) {
            foreach ($ratings as $_rating) {
                switch($_rating->getValue()) {
                    case 1 :
                        $count_rate_one++;
                        break;
                    case 2 :
                        $count_rate_two++;
                        break;
                    case 3 :
                        $count_rate_three++;
                        break;
                    case 4 :
                        $count_rate_four++;
                        break;
                    case 5 :
                        $count_rate_five++;
                        break;
                }
            }
            $detailed_summary = $this->dataDetailedSummaryFactory->create();
            $detailed_summary->setOne($count_rate_one);
            $detailed_summary->setTwo($count_rate_two);
            $detailed_summary->setThree($count_rate_three);
            $detailed_summary->setFour($count_rate_four);
            $detailed_summary->setFive($count_rate_five);

            $this->customerReviewResource->saveDetailedSummaryForProduct($detailed_summary, $entity_pk_value);
        }
    }

    /**
     * get product sku by id
     *
     * @param int $productId
     * @return string
     */
    protected function getProductSkyById(int $productId)
    {
        $product = $this->productRepository->getById($productId);
        return $product ? $product->getSku() : "";
    }
}
