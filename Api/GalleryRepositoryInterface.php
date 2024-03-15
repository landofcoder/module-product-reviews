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
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */
namespace Hgati\ProductReviews\Api;

interface GalleryRepositoryInterface
{
    /**
     * Save gallery.
     *
     * @param \Hgati\ProductReviews\Api\Data\GalleryInterface $block
     * @return \Hgati\ProductReviews\Api\Data\GalleryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\GalleryInterface $gallery);

    /**
     * Retrieve gallery.
     *
     * @param int $galleryId
     * @return \Hgati\ProductReviews\Api\Data\GalleryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($galleryId);

    /**
     * Retrieve galleries matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Hgati\ProductReviews\Api\Data\GallerySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Retrieve galleries matching the specified criteria.
     *
     * @param int $reviewId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Hgati\ProductReviews\Api\Data\GallerySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListByReview($reviewId, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete gallery.
     *
     * @param \Hgati\ProductReviews\Api\Data\GalleryInterface $gallery
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
