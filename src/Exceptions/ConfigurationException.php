<?php
/**
 * Configuration exception
 */

namespace Mito\Exceptions;

final class ConfigurationException extends \Exception
{
    /**
     * @param string          $option
     * @param int             $code
     * @param null|\Exception $previous
     */
    public function __construct(
        string $option,
        int $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct(
            $option,
            $code,
            $previous
        );
    }
}
