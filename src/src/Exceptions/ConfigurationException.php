<?php
namespace Balazsbencs\Exceptions;

final class ConfigurationException extends \Exception
{
    /**
     * @param string $option
     * @param int $code
     * @param null|\Exception $previous
     */
    public function __construct(
        string $option,
        int $code = null,
        \Exception $previous = null
    ) {
        parent::__construct(
            $option,
            $code,
            $previous
        );
    }
}
