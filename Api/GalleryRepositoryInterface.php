<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Api;

//use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Product Review Gallery CRUD interface.
 * @api
 * @since 100.0.2
 */
interface GalleryRepositoryInterface
{
    /**
     * Save gallery.
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface $block
     * @return \Lof\ProductReviews\Api\Data\GalleryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\GalleryInterface $gallery);

    /**
     * Retrieve gallery.
     *
     * @param int $galleryId
     * @return \Lof\ProductReviews\Api\Data\GalleryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($galleryId);

    /**
     * Retrieve galleries matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Lof\ProductReviews\Api\Data\GallerySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete gallery.
     *
     * @param \Lof\ProductReviews\Api\Data\GalleryInterface $gallery
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\GalleryInterface $gallery);

    /**
     * Delete gallery by ID.
     *
     * @param int $galleryId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($galleryId);
}
