<?php
/**
 * Created by 2020/3/20 0020 20:10
 * User: yuansai chen
 */

function dd($str)
{
    echo $str . PHP_EOL;
}

function xrange($start, $limit, $step = 1)
{
    if ($start < $limit) {
        if ($step <= 0) {
            throw new LogicException('Step must be +ve');
        }
        for ($i = $start; $i <= $limit; $i += $step) {
            yield $i;
        }
    } else {
        if ($step >= 0) {
            throw new LogicException('Step must be -ve');
        }
        for ($i = $start; $i >= $limit; $i += $step) {
            yield $i;
        }
    }
}