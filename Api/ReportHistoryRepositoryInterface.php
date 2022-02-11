<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ReportHistoryRepositoryInterface
{

    /**
     * Save ReportHistory
     * @param \Lof\ProductReviews\Api\Data\ReportHistoryInterface $reportHistory
     * @return \Lof\ProductReviews\Api\Data\ReportHistoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Lof\ProductReviews\Api\Data\ReportHistoryInterface $reportHistory
    );

    /**
     * Retrieve ReportHistory
     * @param int $id
     * @return \Lof\ProductReviews\Api\Data\ReportHistoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($id);

    /**
     * Retrieve ReportHistory matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Lof\ProductReviews\Api\Data\ReportHistorySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ReportHistory
     * @param \Lof\ProductReviews\Api\Data\ReportHistoryInterface $reportHistory
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Lof\ProductReviews\Api\Data\ReportHistoryInterface $reportHistory
    );

    /**
     * Delete ReportHistory by ID
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}

