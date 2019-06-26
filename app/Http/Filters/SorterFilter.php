<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class SorterFilter extends AbstractFilter
{
    use SmartOrders;

    protected $withOrders = true;

    protected $defaultOrder = [
        'column' => 'updated_at',
        'direction' => 'desc',
    ];

    public function __construct(Request $request)
    {
        $this->setRequest($request);
    }

    public function handle(Builder $query): Builder
    {
        parent::handle($query);

        $this->applyOrder();

        return $this->query;
    }

    protected function applyOrder(): parent
    {
        if (!$this->withOrders) {
            return $this;
        }

        if (!$this->request->order_by) {

            if ($this->defaultOrder) {
                $this->query->orderBy($this->defaultOrder['column'], $this->defaultOrder['direction']);
            }

        } else {
            foreach ((array)$this->request->order_by as $column => $direction) {

                if (!array_key_exists($column, ($orders = $this->orders()))) continue;

                if (!is_array($params = $orders[$column])) {
                    $this->query->orderBy($params, $direction);

                } else {
                    //@TODO check
                    if (array_key_exists('options', $params)) {
                        $this->{$params['action']}($this->query, $direction, $column, $params['options'] ?? []);
                    } else {
                        $this->{$params['action']}($params)->orderBy($this->getColumn($params, $column), $direction);
                    }
                }

            }
        }

        return $this;
    }

    abstract protected function orders(): array;

    protected function filterUsingRules(): parent
    {
        $relations = [];

        foreach ($this->optimiseRules() as $requestKey => $options) {
            $this->filterApplier($this->query, $requestKey, $options, $relations);
        }

        $this->handleRelations($relations, $this->query);

        return $this;
    }

    private function optimiseRules(): array
    {
        $rules = $this->rules();

        foreach ($this->joined as $table) {
            $rules = array_merge($rules, $this->joinRulesMap()[$table] ?? []);
        }

        return $rules;
    }

    protected function joinRulesMap(): array
    {
        return [
            //
        ];
    }

    protected function sortByLabel(array $labelsArr): array
    {
        return [
            'action'    => 'applySortByLabel',
            'options'   => ['labels' => $labelsArr],
        ];
    }

    protected function applySortByLabel(Builder $b, string $direction, string $column, array $options): void
    {
        $labels = $options['labels'];

        ksort($labels, SORT_LOCALE_STRING);

        $imploded = implode(',', $labels);

        $b->orderByRaw("FIELD($column, $imploded) $direction");
    }
}