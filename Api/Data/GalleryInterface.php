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
namespace Lof\ProductReviews\Api\Data;

interface GalleryInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const GALLERY_ID    = 'id';
    const REVIEW_ID     = 'review_id';
    const LABEL         = 'label';
    const VALUE         = 'value';
    const STATUS        = 'status';
    const IMAGES        = 'images';

    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();


    /**
     * Get Review Id
     *
     * @return int|null
     */
    public function getReviewId();

    /**
     * Get label
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Get value
     *
     * @return mixed|null
     */
    public function getValue();

    /**
     * Get images
     *
     * @return mixed|null
     */
    public function getImages();

    /**
     * Status
     *
     * @return bool|null
     */
    public function getStatus();

    /**
     * Set ID
     *
     * @param int $id
     * @return GalleryInterface
     */
    public function setId($id);

    /**
     * Set review id
     *
     * @param string $reviewId
     * @return GalleryInterface
     */
    public function setReviewId($reviewId);

    /**
     * Set label
     *
     * @param string $label
     * @return GalleryInterface
     */
    public function setLabel($label);

    /**
     * Set value
     *
     * @param array|string $value
     * @return GalleryInterface
     */
    public function setValue($value);

    /**
     * Set images
     *
     * @param array|mixed $images
     * @return GalleryInterface
     */
    public function setImages($images);

    /**
     * Set Status
     *
     * @param bool|int $status
     * @return GalleryInterface
     */
    public function setStatus($status);
}
