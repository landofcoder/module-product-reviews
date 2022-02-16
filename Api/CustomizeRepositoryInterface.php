<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomizeRepositoryInterface
{

    /**
     * Save Customize
     * @param \Lof\ProductReviews\Api\Data\CustomizeInterface $customize
     * @return \Lof\ProductReviews\Api\Data\CustomizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Lof\ProductReviews\Api\Data\CustomizeInterface $customize
    );

    /**
     * Retrieve Customize
     * @param int $customizeId
     * @return \Lof\ProductReviews\Api\Data\CustomizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($customizeId);

    /**
     * Retrieve Customize by reviewId
     * @param int $reviewId
     * @return \Lof\ProductReviews\Api\Data\CustomizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByReview(int $reviewId);

    /**
     * Retrieve Customize matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Lof\ProductReviews\Api\Data\CustomizeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Customize
     * @param \Lof\ProductReviews\Api\Data\CustomizeInterface $customize
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Lof\ProductReviews\Api\Data\CustomizeInterface $customize
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

