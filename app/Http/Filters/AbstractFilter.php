<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractFilter
{
    use Helpers;

    public $request;

    protected $query;

    function __construct(Request $request)
    {
        $this->setRequest($request);
    }

    protected function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function handle(Builder $query): Builder
    {
        $this->setQuery($query)
            ->filterUsingRules();

        return $this->query;
    }

    protected function filterUsingRules(): self
    {
        $relations = [];

        foreach ($this->rules() as $requestKey => $options) {
            $this->filterApplier($this->query, $requestKey, $options, $relations);
        }

        $this->handleRelations($relations, $this->query);

        return $this;
    }

    public function rules(): array
    {
        return [
//            'score' => [
//                'params' => [
//                    'column' => 'some_column',
//                    'operator' => '>',
//                ]
//            ],
//            'secondParam' => [
//                'action' => 'relation',
//                'relationName' => 'sections',
//                'rule' => [
//                    'action' => 'relation',
//                    'relationName' => 'location',
//                    'rule' => [
//                        'params' => [
//                            'column' => 'some_other2',
//                            'queryMethod' => 'wherein'
//                        ]
//                    ]
//                ]
//            ],
//            'agegroup' => [
//                'action' => 'relation',
//                'relationName' => 'sections',
//                'rule' => [
//                    'params' => [
//                        'column' => 'some_other3',
//                    ]
//                ]
//            ]

        ];
    }

    //@TODO make Abstract

    protected function filterApplier(Builder $query, string $requestKey, array $options, &$relations): void
    {
        $sentFromFE = array_search($requestKey, $this->nullableKeys ?? []) === false ?
            $this->request->has($requestKey) :
            $this->request->exists($requestKey);

        if (!$sentFromFE && !(isset($options['params']['defaultValue']))) {
            return;
        }

        $magicMethods = ['relation', 'date', 'search'];

        $method = $options['action'] ?? 'simple';

        if (!(in_array($method, $magicMethods) || method_exists($this, $method))) {
            throw new \Exception("Method '$method' not found");
        }

        $this->applyMethod($method, $requestKey, $options, $query, $relations);
    }

    private function applyMethod(string $method, string $requestKey, array $options, Builder $query, array &$relations): void
    {
        switch ($method) {

            case 'relation':
                $this->collectRelations($requestKey, $options, $relations);
                break;

            case 'date':
                $this->dateFilter($query, $requestKey, $options);
                break;

            case 'search':
                $this->applySearch($query, $requestKey, $options);
                break;

            default :
                $this->standardFilter(
                    $query,
                    $requestKey,
                    $method,
                    $options['params'] ?? []
                );
        }
    }

    private function collectRelations(string $requestKey, array $options, array &$relations): void
    {
        if (!key_exists('relationName', $options)) {
            throw new \Exception("RelationName is required on '$requestKey'");
        } elseif (!key_exists('rule', $options)) {
            throw new \Exception("Rule is required on '$requestKey'");
        }

        $relations[$options['relationName']][$requestKey] = $options['rule'];
    }

    private function standardFilter(Builder $query, string $requestKey, string $method, array $params): void
    {
        $query->where(function (Builder $query) use ($method, $requestKey, $params) {
            call_user_func_array(
                [$this, $method],
                [$query, $requestKey, $params]
            );
        });
    }

    protected function handleRelations(array $relations, Builder $query): void
    {
        foreach ($relations as $relationName => $rules) {
            $query->whereHas($relationName, function ($relationQuery) use ($rules, $relationName, $relations) {
                $nestedRelations = [];

                foreach ($rules as $requestKey => $rule) {
                    $this->filterApplier($relationQuery, $requestKey, $rule, $nestedRelations);
                }

                $this->handleRelations($nestedRelations, $relationQuery);
            });
        }
    }

    protected function setQuery(Builder $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getPerPageCount(int $default = 10): int
    {
        return $this->request->items_per_page ?? $default;
    }

    protected function relationFilter(string $relation, $column = 'id', $queryMethod = 'whereIn'): array // column doesn't have type because it can be array or string
    {
        $relationHierarchy = explode('.', $relation);

        $output = [
            'action' => 'relation',
            'relationName' => array_shift($relationHierarchy),
            'rule' => $this->getRule($relationHierarchy, $column, $queryMethod)
        ];

        return $output;
    }
//
//    public function __call(string $name, array $arguments)
//    {
//        foreach (explode('|', $name) as $method) {
//            $this->{$method}(...$arguments);
//        }
//
//        return $this->query;
//    }

    protected function getRule(array &$remainingRelations, $column, $queryMethod = 'whereIn'): array
    {
        if (empty($remainingRelations))
            return $this->getRelationParams($column, $queryMethod);

        return $this->relationFilter(implode('.', $remainingRelations), $column, $queryMethod);
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

    protected function action(string $action, array $params = []): array
    {
        return [
            'action' => $action,
            'params' => $params
        ];
    }

    private function simple($query, $requestKey, $params): void
    {

        if (!key_exists('column', $params)) {
            throw new \Exception("Column is required on '$requestKey'");
        }

        $arguments = key_exists('operator', $params)
            ? [$params['column'], $params['operator'], $this->request->get($requestKey)]
            : [$params['column'], $this->request->get($requestKey)];

        //@TODO clean code
        if (isset($params['queryMethod'])) {
            if (strcasecmp($params['queryMethod'], 'whereRaw') == 0) {
                $operator = $params['operator'] ?? '=';
                $arguments = [$params['column'] . $operator . "'" . $this->request->get($requestKey) . "'"];
            } elseif (strcasecmp($params['queryMethod'], 'whereIn') == 0) {
                $arguments[1] = (array)$arguments[1]; //second item is values, so for whereIn we cast it to array (just in case)
            }
        }
        //END

        call_user_func_array(
            [$query, $params['queryMethod'] ?? 'where'],
            $arguments
        );
    }
}