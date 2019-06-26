<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

trait SmartOrders
{
    protected $joined = [];
    private $initialTable;

    protected function applyJoin(array $params): Builder
    {
        if (!in_array($params['table'], $this->joined)) {
            $this->joined[] = $params['table'];

            return $this->query->{$params['method'] ?? 'join'}($params['table'], $this->getFirst($params), '=', $this->getSecond($params));
        }

        return $this->query;
    }

    public function getFirst(array $params): string
    {
        return $params['first'] ?? $this->getInitialTable() . '.' . $this->getForeignKey($params['table']);
    }

    private function getInitialTable(): string
    {
        return $this->initialTable ?? $this->initialTable = $this->query->getModel()->getTable();
    }

    private function getForeignKey(string $table): string
    {
        return $this->getModelByTable($table) . '_id';
    }

    private function getModelByTable(string $table): string
    {
        return substr($table, 0, -1);
    }

    public function getSecond(array $params): string
    {
        return $params['second'] ?? $params['table'] . '.' . 'id';
    }

    protected function leftJoin(string $table, ?string $column = null, ?string $first = null, ?string $second = null): array
    {
        return $this->join($table, $column, $first, $second, 'leftJoin');
    }

    protected function join(string $table, ?string $column = null, ?string $first = null, ?string $second = null, ?string $method = 'join', string $action = 'applyJoin'): array
    {
        return [
            'column'    => $column,
            'table'     => $table,
            'first'     => $first,
            'second'    => $second,
            'method'    => $method,
            'action'    => $action
        ];
    }

    protected function rightJoin(string $table, ?string $column = null, ?string $first = null, ?string $second = null): array
    {
        return $this->join($table, $column, $first, $second, 'rightJoin');
    }

    private function getColumn(array $params, string $requestKey): string
    {
        return $params['column'] ?? $params['table'] . '.' . $requestKey;
    }
}