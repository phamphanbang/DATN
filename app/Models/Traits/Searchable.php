<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function searchAttributes($query, string $term)
    {
        $table = $this->getTable();

        $firstConditionAdded = false;

        foreach ($this->getSearchableAttributes() as $column) {
            if (!$firstConditionAdded) {
                $query = $query->where($table . '.' . $column, 'LIKE', '%' . $term . '%');

                $firstConditionAdded = true;
                continue;
            }
            $query = $query->orWhere($table . '.' . $column, 'LIKE', '%' . $term . '%');
        }
        return $query;
    }

    private function getSearchableAttributes()
    {
        return isset($this->searchableAttributes) ? $this->searchableAttributes : [];
    }
}
