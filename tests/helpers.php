<?php

function create(string $model, int $count = 1, array $overrides = [])
{
    if($count > 1) {
        return factory($model, $count)->create($overrides);
    }
    return factory($model)->create($overrides);
}

function make(string $model, int $count = 1, array $overrides = [])
{
    if ($count > 1) {
        return factory($model, $count)->make($overrides);
    }
    return factory($model)->make($overrides);
}