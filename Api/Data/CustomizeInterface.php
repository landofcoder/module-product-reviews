<?php
/**
 * Copyright © hgati All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Api\Data;

interface CustomizeInterface
{

    const COUNT_HELPFUL = 'count_helpful';
    const AVATAR_IMAGE = 'avatar_image';
    const REVIEW_ID = 'review_id';
    const EMAIL_ADDRESS = 'email_address';
    const ADVANTAGES = 'advantages';
    const REPORT_ABUSE = 'report_abuse';
    const DISADVANTAGES = 'disadvantages';
    const AVERAGE = 'average';
    const COUNT_UNHELPFUL = 'count_unhelpful';
    const TOTAL_HELPFUL = 'total_helpful';
    const REVIEW_CUSTOMIZE_ID = 'review_customize_id ';
    const AVATAR_URL = 'avatar_url';
    const ANSWER = 'answer';
    const IS_RECOMMENDED = 'is_recommended';
    const VERIFIED_BUYER = 'verified_buyer';
    const COUNTRY = 'country';

    /**
     * Get review_customize_id
     * @return int|null
     */
    public function getReviewCustomizeId();

    /**
     * Set review_customize_id
     * @param int $review_customize_id
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setReviewCustomizeId($review_customize_id);

    /**
     * Get is_recommended
     * @return int|null
     */
    public function getIsRecommended();

    /**
     * Set is_recommended
     * @param int $is_recommended
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setIsRecommended($is_recommended);

    /**
     * Get verified_buyer
     * @return int|null
     */
    public function getVerifiedBuyer();

    /**
     * Set verified_buyer
     * @param int $verified_buyer
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setVerifiedBuyer($verified_buyer);

    /**
     * Get answer
     * @return string|null
     */
    public function getAnswer();

    /**
     * Set answer
     * @param string $answer
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setAnswer($answer);


    /**
     * Get advantages
     * @return string|null
     */
    public function getAdvantages();

    /**
     * Set advantages
     * @param string $advantages
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setAdvantages($advantages);

    /**
     * Get disadvantages
     * @return string|null
     */
    public function getDisadvantages();

    /**
     * Set disadvantages
     * @param string $disadvantages
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setDisadvantages($disadvantages);

    /**
     * Get average
     * @return string|null
     */
    public function getAverage();

    /**
     * Set average
     * @param string $average
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setAverage($average);

    /**
     * Get count_helpful
     * @return int|null
     */
    public function getCountHelpful();

    /**
     * Set count_helpful
     * @param int $countHelpful
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setCountHelpful($countHelpful);

    /**
     * Get count_unhelpful
     * @return int|null
     */
    public function getCountUnhelpful();

    /**
     * Set count_unhelpful
     * @param int $countUnhelpful
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setCountUnhelpful($countUnhelpful);

    /**
     * Get total_helpful
     * @return int|null
     */
    public function getTotalHelpful();

    /**
     * Set total_helpful
     * @param int $totalHelpful
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setTotalHelpful($totalHelpful);

    /**
     * Get report_abuse
     * @return int|null
     */
    public function getReportAbuse();

    /**
     * Set report_abuse
     * @param int $reportAbuse
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setReportAbuse($reportAbuse);

    /**
     * Get review_id
     * @return int|null
     */
    public function getReviewId();

    /**
     * Set review_id
     * @param int $reviewId
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setReviewId($reviewId);

    /**
     * Get email_address
     * @return string|null
     */
    public function getEmailAddress();

    /**
     * Set email_address
     * @param string $emailAddress
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setEmailAddress($emailAddress);

    /**
     * Get avatar_image
     * @return string|null
     */
    public function getAvatarImage();

    /**
     * Set avatar_image
     * @param string $avatarImage
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setAvatarImage($avatarImage);

    /**
     * Get avatar_url
     * @return string|null
     */
    public function getAvatarUrl();

    /**
     * Set avatar_url
     * @param string $avatarUrl
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setAvatarUrl($avatarUrl);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     * @param string $country
     * @return \Hgati\ProductReviews\Api\Data\CustomizeInterface
     */
    public function setCountry($country);
}

