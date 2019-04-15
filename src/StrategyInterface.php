<?php

namespace CoiSA\Monolog;

use Monolog\Handler;

/**
 * Interface StrategyInterface
 *
 * @package CoiSA\Monolog
 */
interface StrategyInterface
{
    /** @const string Eager log entry write strategy */
    const EAGER = Handler\GroupHandler::class;

    /** @const string Waiting for an error log entry write strategy */
    const OPTIMISTIC = Handler\FingersCrossedHandler::class;

    /** @const string Lazy log entry write strategy. Writes only in the end of execution */
    const LAZY = Handler\BufferHandler::class;

    /** @const string Lazy log entry write strategy. Deduplicate log entries then write in the end of execution */
    const DEDUPLICATED = Handler\DeduplicationHandler::class;

    /** @const string NOT log entry at ALL! */
    const DISABLED = Handler\NullHandler::class;
}
