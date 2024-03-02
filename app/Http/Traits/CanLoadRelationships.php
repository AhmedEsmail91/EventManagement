<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{
    /**
     * Load relationships for the given model or query builder.
     *
     * @param Model|QueryBuilder|EloquentBuilder $for The query resulting from a call to either `Model::query()`,`get()`, `find()` to the model ;
     * @param array $relations The array of relationship names to be loaded.
     * @return QueryBuilder|EloquentBuilder|Model
     */
public function loadRelationships(
    Model|QueryBuilder|EloquentBuilder $for,
    ?array $relations = null
): Model|QueryBuilder|EloquentBuilder {
    $relations = $relations ?? $this->relations ?? [];

    foreach ($relations as $relation) {
    $for->when(
        $this->shouldIncludeRelation($relation),
        fn($q) => $for instanceof Model ? $q->load($relation) : $q->with($relation)
    );
    }

    return $for;
}

protected function shouldIncludeRelation(string $relation): bool
{
    $include = request()->query('include');

    if (!$include) {
    return false;
    }

    $relations = array_map('trim', explode(',', $include));

    return in_array($relation, $relations);
}
}