<?php

/*  
 Transform a date from a form in string format to date format and reverse
 Author : Frederic Parmentier
 Created At 05/08/2020
*/


namespace App\Form\DataTransformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateStringToDateTransformer implements DataTransformerInterface
{
    public function transform($dateDate) {
        if (null === $dateDate) {
            return '';
        }
        if (!is_object($dateDate)) {
            throw new TransformationFailedException("expected a datetime");
        }

        return $dateDate -> format("d-m-Y");
    }
    
    #
    public function reverseTransform($dateString) {
        if (null === $dateString) {
            $privateErrorMessage = sprintf('date cannot be null');
            $publicErrorMessage = "user.date.error_null"; /* message in validator file translation */
            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage);
            throw $failure;
        }

        if (!is_string($dateString)) {
            $privateErrorMessage = sprintf('Bad format for date (string expected)');
            $publicErrorMessage = "user.date.error_format"; /* message in validator file translation */
            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage);
            throw $failure;
        }

        $dateConvert = \DateTime::createFromFormat("d-m-Y", $dateString);

        if ($dateConvert === false) {
            $privateErrorMessage = sprintf('Bad format for date (string expected)');
            $publicErrorMessage = "user.date.error_format"; /* message in validator file translation */
            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage);
            throw $failure;
        }
        return $dateConvert;
    }
}