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

namespace Hgati\ProductReviews\Api\Data;

/**
 * Represents a ReviewVote object
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ImageInterface
{
    const FULL_PATH = 'full_path';
    const RESIZED_PATH = 'resized_path';

    /**
     * Get full_path.
     *
     * @return string
     */
    public function getFullPath();

    /**
     * set full_path
     *
     * @return \Hgati\ProductReviews\Api\Data\ImageInterface
     */
    public function setFullPath($full_path);

    /**
     * Get resized_path.
     *
     * @return string
     */
    public function getResizedPath();

    /**
     * set resized_path
     *
     * @return \Hgati\ProductReviews\Api\Data\ImageInterface
     */
    public function setResizedPath($resized_path);

}
