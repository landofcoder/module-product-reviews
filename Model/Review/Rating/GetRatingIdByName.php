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

namespace Hgati\ProductReviews\Model\Review\Rating;

use Magento\Review\Model\ResourceModel\Rating\Collection as RatingCollection;
use Magento\Review\Model\ResourceModel\Rating\CollectionFactory as RatingsFactory;

/**
 * Class GetRatingIdByName retrieve Rating Id by rating code
 */
class GetRatingIdByName
{
    /**
     * Rating resource model
     *
     * @var RatingsFactory
     */
    private $ratingsCollectionFactory;

    /**
     * @var RatingCollection
     */
    private $ratings = [];

    /**
     * GetRatingIdByName constructor.
     *
     * @param RatingsFactory $ratingsFactory
     */
    public function __construct(RatingsFactory $ratingsFactory)
    {
        $this->ratingsCollectionFactory = $ratingsFactory;
    }

    /**
     * Get Rating Id by Rating Code
     *
     * @param string $ratingName
     * @param int $storeId
     *
     * @return int|null
     */
    public function execute(string $ratingName, int $storeId): ?int
    {
        if (!isset($this->ratings[$storeId])) {
            /** @var RatingCollection $collection */
            $collection = $this->ratingsCollectionFactory->create();
            $collection->setStoreFilter($storeId);
            $collection->addRatingPerStoreName($storeId);
            $this->ratings[$storeId] = $this->toOptionHash($collection, 'rating_code');
        }

        $ratingId = array_search($ratingName, $this->ratings[$storeId]);

        return $ratingId !== false ? (int) $ratingId : null;
    }

    /**
     * Group rating code by id
     *
     * @param RatingCollection $collection
     * @param string $labelField
     *
     * @return mixed|array
     */
    private function toOptionHash(RatingCollection $collection, string $labelField): array
    {
        $valueField = $collection->getResource()->getIdFieldName();
        $res = [];

        foreach ($collection as $item) {
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }

        return $res;
    }
}
