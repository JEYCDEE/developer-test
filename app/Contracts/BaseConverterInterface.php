<?php

namespace App\Contracts;

/**
 * Interface BaseConverterInterface
 * @package App\Contracts
 */
interface BaseConverterInterface
{
    /**
     * Converts data into a file and returns a path to this file.
     *
     * @param mixed|null $additionalParams
     * @return string
     */
    public function convert($additionalParams = null): string;
}
