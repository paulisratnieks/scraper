<?php

declare(strict_types=1);

if (!function_exists('string_or_fail')) {
    /**
     * Throw an error if input is not a string. Useful for type narrowing to string.
     *
     * @throws Throwable
     */
    function string_or_fail(mixed $value): string
    {
        throw_unless(
            is_string($value),
            new RuntimeException('value is not a string')
        );

        return $value;
    }
}
