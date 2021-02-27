<?php

function create(string $model, int $count = 1, array $overrides = [])
{
    if ($count > 1) {
        return $model::factory()->count($count)->create($overrides);
    }

    return $model::factory()->create($overrides);
}

function make(string $model, int $count = 1, array $overrides = [])
{
    if ($count > 1) {
        return $model::factory()->count($count)->make($overrides);
    }

    return $model::factory()->make($overrides);
}
