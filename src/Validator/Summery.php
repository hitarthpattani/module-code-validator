<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

/**
 * Generate summary.
 */
class Summery
{
    /**
     * @param array $validators
     * @return string
     */
    public function execute($validators): string
    {
        $isSuccess = true;
        $successCount = 0;
        $totalCount = 0;

        foreach ($validators as $validator) {
            $isSuccess = $isSuccess && $validator->getSuccessStatus();
            $successCount += $validator->successCount;
            $totalCount += $validator->totalCount;
        }

        return sprintf(
            "\nTests complete! %s/%s completed successfully. %s\n",
            $successCount,
            $totalCount,
            $isSuccess ? "<success>All tests passed!</success>" : "<failure>Some tests failed!</failure>"
        );
    }
}
