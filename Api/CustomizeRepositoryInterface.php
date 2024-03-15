<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomizeRepositoryInterface
{

    /**
     * Save Customize
     * @param \Hgati\ProductReviews\Api\Data\CustomizeInterface $customize
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Hgati\ProductReviews\Api\Data\CustomizeInterface $customize
    );

    /**
     * Retrieve Customize
     * @param int $customizeId
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($customizeId);

    /**
     * Retrieve Customize by reviewId
     * @param int $reviewId
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByReview(int $reviewId);

    /**
     * Retrieve Customize matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Hgati\ProductReviews\Api\Data\CustomizeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Customize
     * @param \Hgati\ProductReviews\Api\Data\CustomizeInterface $customize
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Hgati\ProductReviews\Api\Data\CustomizeInterface $customize
    );

    /**
     * Delete Customize by ID
     * @param int $customizeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($customizeId);
}

