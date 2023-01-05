<?php

declare(strict_types=1);

namespace Support;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

final class Transaction
{
    /**
     * @throws Throwable
     */
    public static function run(
        Closure $callback,
        Closure $finished = null,
        Closure $onError = null,
    ) {
        try {
            DB::beginTransaction();

            return tap($callback(), static function ($result) use ($finished) {
                if (!is_null($finished)) {
                    $finished($result);
                }
                DB::commit();
            });

            /* $result = $callback();
            if (!is_null($finished)) {
                $finished($result);
            }
            DB::commit();
            return $result; */
        } catch (Throwable $e) {
            DB::rollBack();

            if (!is_null($onError)) {
                $onError($e);
            }
            throw $e;
        }
    }
}