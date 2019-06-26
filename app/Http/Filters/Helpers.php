<?php

namespace App\Http\Filters;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Helpers
{
    public function setOrdering(bool $withOrders): self
    {
        $this->withOrders = $withOrders;

        return $this;
    }

    protected function dateFilter(Builder $query, string $requestKey, array $options): void
    {
        if (!key_exists('operator', $options)) {
            throw new \Exception("Operator is required on '$requestKey'");
        } elseif (!key_exists('column', $options)) {
            throw new \Exception("Column is required on '$requestKey'");
        }

        try {
            $date = Carbon::parse($this->request->$requestKey);
        } catch (\Exception $e) {
            throw new \Exception('Invalid parameter for date filter');
        }

        if ($options['endOfDay'] ?? false) $date->endOfDay();

        $query->where($options['column'], $options['operator'], $date);
    }

    protected function from(string $column = 'created_at'): array
    {
        return $this->dateParams($column);
    }

    protected function dateParams(string $column, string $operator = '>=', bool $endOfDay = false): array
    {
        return [
            'action' => 'date',
            'operator' => $operator,
            'column' => $column,
            'endOfDay' => $endOfDay
        ];
    }

    protected function to(string $column = 'created_at'): array
    {
        return $this->dateParams($column, '<=', true);
    }

    protected function relationFilter(string $relation, $column, $queryMethod = 'whereIn'): array // column doesn't have type because it can be array or string
    {
        $relationHierarchy = explode('.', $relation);

        $output = [
            'action' => 'relation',
            'relationName' => array_shift($relationHierarchy),
            'rule' => $this->getRule($relationHierarchy, $column, $queryMethod)
        ];

        return $output;
    }

    protected function getRule(array &$remainingRelations, $column, $queryMethod = 'whereIn'): array
    {
        if (empty($remainingRelations))
            return $this->getRelationParams($column, $queryMethod);

        return $this->relationFilter(implode('.', $remainingRelations), $column, $queryMethod);
    }

    protected function params(string $column, string $queryMethod = 'whereIn', array $additionalParams = []): array
    {
        return [
            'params' => array_merge([
                'column' => $column,
                'queryMethod' => $queryMethod
            ], $additionalParams)
        ];
    }

    protected function searchParams($searchIn, bool $explode = false): array
    {
        return [
            'action'    => 'search',
            'searchIn'  => is_array($searchIn) ? $searchIn : func_get_args(),
            'explode'   => $explode
        ];
    }

    private function getRelationParams($column, string $queryMethod = 'whereIn'): array
    {
        return is_array($column) ? $column : [
            'params' => [
                'column' => $column,
                'queryMethod' => $queryMethod
            ]
        ];
    }

    private function applySearch(Builder $query, string $requestKey, array $options)
    {
        $formattedFields = $this->getFormattedSearchFields($options['searchIn']);

        $explode = $options['explode'] ?? false;

        $query->where(function (Builder $b) use ($requestKey, $formattedFields, $explode) {
            foreach ((array) $this->request->get($requestKey) as $search) {

                $searchKeys = $explode ? explode(' ', $search) : [$search];

                foreach ($searchKeys as $search) {
                    $b->orWhere(function (Builder $b) use ($search, $formattedFields) {
                        $this->applyRecursiveSearch($b, $formattedFields, $search);
                    });
                }
            }
        });
    }

    private function applyRecursiveSearch(Builder $b, array $fields, string $searchKey): void
    {
        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                $b->orWhereHas($key, function (Builder $b) use ($field, $searchKey) {
                    $this->applyRecursiveSearch($b, $field, $searchKey);
                });

            } else {

                // for first field we should use where to avoid bug e.g "OR EXISTS(
                //  SELECT
                //    *
                //  FROM
                //    `table1`
                //  WHERE
                //    `table2`.`foreign_id` = `table1`.`id` OR(`field` LIKE '%searchKey%')
                //)"
                $method = $key ? 'orWhere' : 'where';

                $b->$method($field, 'like', "%$searchKey%");
            }
        }
    }

    private function getFormattedSearchFields(array $fields): array
    {
        $formatted = [];

        foreach ($fields as $field) {
            $this->recursiveFormation($formatted, $field);
        }

        return $formatted;
    }

    private function recursiveFormation(array &$arr, string $field): void
    {
        if (count($exploded = explode('.', $field)) > 1) {
            $key  = array_shift($exploded);

            if (!isset($arr[$key])) {
                $arr[$key] = [];
            }

            $this->recursiveFormation($arr[$key], implode('.', $exploded));

        } else {
            array_push($arr, $exploded[0]);
        }
    }
}