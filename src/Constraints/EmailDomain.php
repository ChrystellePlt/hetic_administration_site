<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailDomain extends Constraint
{
    public $domains;
    public $message = 'The email "%email%" has not a valid domain.';
}
