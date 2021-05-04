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
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */
namespace Lof\ProductReviews\Api;

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
