<?php

namespace app\exceptions;

use yii\web\HttpException;

/**
 * Description of NotAllowedHttpException
 *
 * @author ilya
 */
class NotAllowedHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(403, $message, $code, $previous);
    }
}
