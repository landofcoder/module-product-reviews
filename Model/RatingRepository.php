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

namespace Lof\ProductReviews\Model;

use Magento\Review\Model\Rating;
use Magento\Review\Model\Rating\Option\Vote;
use Lof\ProductReviews\Model\Converter\RatingVote;
use Magento\Review\Model\ResourceModel\Rating\Collection as RatingCollection;
use Magento\Review\Model\ResourceModel\Rating\CollectionFactory as RatingsCollectionFactory;
use Magento\Review\Model\ResourceModel\Rating\Option\Vote\Collection as VoteCollection;
use Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory as VoteCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Lof\ProductReviews\Api\RatingRepositoryInterface;
use Lof\ProductReviews\Api\Data\RatingVoteInterface;
use Lof\ProductReviews\Api\Data\RatingVoteInterfaceFactory;

/**
 * @inheritdoc
 */
class RatingRepository implements RatingRepositoryInterface
{

    /**
     * @var RatingVote
     */
    private $ratingConverter;

    /**
     * Rating resource model
     *
     * @var RatingsCollectionFactory
     */
    private $ratingsCollectionFactory;

    /**
     * @var RatingCollection
     */
    private $ratingCollection;

    /**
     * @var VoteCollectionFactory
     */
    private $voteCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * RatingLoaderHandler constructor.
     *
     * @param RatingVote $ratingConverter
     * @param VoteCollectionFactory $voteCollectionFactory
     * @param RatingsCollectionFactory $ratingsFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RatingVote $ratingConverter,
        VoteCollectionFactory $voteCollectionFactory,
        RatingsCollectionFactory $ratingsFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->ratingsCollectionFactory = $ratingsFactory;
        $this->ratingConverter = $ratingConverter;
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     *
     */
    public function getList($storeId = null)
    {
        if ($storeId) {
            if (!is_numeric($storeId)) {
                $store = $this->storeManager->getStore($storeId);
                $storeId = $store ? $store->getId() : 0;
            }
        }
        $ratingCollection = $this->getRatingCollection((int)$storeId);
        $items = [];
        foreach ($ratingCollection as $_rating) {
            $data = [
                "rating_id" => $_rating->getRatingId(),
                "rating_name" => $_rating->getRatingcode(),
                'rating_code' => $_rating->getRatingcode()
            ];
            $items[] = $this->ratingConverter->arrayToDataModel($data);
        }
        return $items;
    }

    /**
     * Get Rating Collection
     *
     * @param int $storeId
     *
     * @return RatingCollection
     */
    private function getRatingCollection(int $storeId): RatingCollection
    {
        if ($this->ratingCollection === null) {
            /** @var RatingCollection $ratingCollection */
            $ratingCollection = $this->ratingsCollectionFactory->create()
                ->addEntityFilter('product')
                ->setStoreFilter($storeId)
                ->addRatingPerStoreName($storeId)
                ->setPositionOrder()
                ->load();

            $this->ratingCollection = $ratingCollection;
        }

        return $this->ratingCollection;
    }
}
