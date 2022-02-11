<?php
/**
 * Copyright © landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface RemindersRepositoryInterface
{

    /**
     * Save Reminders
     * @param \Lof\ProductReviews\Api\Data\RemindersInterface $reminders
     * @return \Lof\ProductReviews\Api\Data\RemindersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Lof\ProductReviews\Api\Data\RemindersInterface $reminders
    );

    /**
     * Retrieve Reminders
     * @param int $id
     * @return \Lof\ProductReviews\Api\Data\RemindersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($id);

    /**
     * Retrieve Reminders matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Lof\ProductReviews\Api\Data\RemindersSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Reminders
     * @param \Lof\ProductReviews\Api\Data\RemindersInterface $reminders
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Lof\ProductReviews\Api\Data\RemindersInterface $reminders
    );

    /**
     * Delete Reminders by ID
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}

