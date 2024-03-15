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

namespace Hgati\ProductReviews\Validation;

use Magento\Framework\Exception\LocalizedException;
use Hgati\ProductReviews\Exception\AggregateExceptionInterface;
use Magento\Framework\Phrase;

/**
 * Validation exception with possibility to set several error messages
 *
 * ValidationException exists to be compatible with the Web-API (SOAP and REST) implementation which currently
 * uses Magento\Framework\Exception\AggregateExceptionInterface returned as a result of ServiceContracts call
 * to support Multi-Error response.
 *
 * @see \Magento\Framework\Validation\ValidationException in Magento 2.3
 */
class ValidationException extends LocalizedException implements AggregateExceptionInterface
{
    /**
     * @var ValidationResult|null
     */
    private $validationResult;

    /**
     * ValidationException constructor.
     *
     * @param Phrase $phrase
     * @param \Exception|null $cause
     * @param int $code
     * @param ValidationResult|null $validationResult
     */
    public function __construct(
        Phrase $phrase,
        \Exception $cause = null,
        $code = 0,
        ValidationResult $validationResult = null
    ) {
        parent::__construct($phrase, $cause, $code);
        $this->validationResult = $validationResult;
    }

    /**
     * Retrieve errors list
     *
     * @return mixed|array
     */
    public function getErrors(): array
    {
        $localizedErrors = [];

        if (null !== $this->validationResult) {
            foreach ($this->validationResult->getErrors() as $error) {
                $localizedErrors[] = new LocalizedException($error);
            }
        }

        return $localizedErrors;
    }
}
