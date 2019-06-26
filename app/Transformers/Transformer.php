<?php

namespace App\Transformers;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

abstract class Transformer
{
    private static $instances = [];
    protected $defaultPrecision = 2;

    public static function __callStatic($name, $arguments): array
    {
        if (method_exists(self::getInstance(), 'transform' . ucfirst($name))){
            return self::getInstance()->{'transform' . ucfirst($name)}(...$arguments);
        }

        return self::getInstance()->{$name . 'Transform'}(...$arguments);
    }

    private static function getInstance(): self
    {
        return self::$instances[static::class] ?? self::$instances[static::class] = new static();
    }

    public function universalPaginationTransform(Builder $builder, string $method = 'transform', bool $paginateByDefault = true, ...$args) : array
    {
        return $this->universalTransform($builder, $method, $paginateByDefault, 'transformPagination', ...$args);
    }

    public function universalSimplePaginationTransform(Builder $builder, string $method = 'transform', bool $paginateByDefault = true, ...$args) : array
    {
        return $this->universalTransform($builder, $method, $paginateByDefault, 'transformSimplePagination', ...$args);
    }

    private function universalTransform(
        Builder $builder,
        string $method,
        bool $paginateByDefault,
        string $paginationTransformType,
        ...$args
    ) : array
    {
        if (request()->has('without_pagination') ? request()->without_pagination : !$paginateByDefault) {
            if ($builder->count() > 500) Log::info('Response count for user '. Auth::id() .' was limited in ' . request()->route()->action['controller'], request()->all());

            $items = $builder->limit(500)->get();

            return [
                'has_more_pages'    => null,
                'total'             => $items->count(),
                'items'             => $this->transformCollection($items, $method, ...$args),
            ];

        } else {
            return $this->$paginationTransformType($builder->paginate(request()->items_per_page ?? 15), $method, ...$args);
        }
    }

    public function transformCollection(Collection $items, string $method = 'simpleTransform', ...$args) : array
    {
        return $items->map(function ($item) use ($method, $args) {
            return $this->{$method}($item, ...$args);
        })->toArray();
    }

    public function transformPagination(LengthAwarePaginator $paginator, string $method = 'simpleTransform', ...$args) : array
    {
        return [
            'total'         => $paginator->total(),
            'per_page'      => $paginator->perPage(),
            'hasMorePages'  => method_exists($paginator, 'hasMorePages') ? $paginator->hasMorePages() : null,
            'items'         => $this->transformArray($paginator->items(), $method, ...$args)
        ];
    }

    public function transformSimplePagination(AbstractPaginator $paginator, string $method = 'simpleTransform', ...$args): array
    {
        return [
            'has_more_pages'    => $paginator->hasMorePages(),
            'items'             => $this->transformArray($paginator->items(), $method, ...$args)
        ];
    }

    public function transformArray(array $items, string $method = 'simpleTransform', ...$args) : array
    {
        return array_map(function($item) use ($method,$args) {
            return $this->{$method}($item, ...$args);
        },$items);
    }

    protected function transformDate(Carbon $date): string
    {
        return $date->toDateTimeString();
    }

    abstract public function simpleTransform(Model $item) : array; //simple Transformer, to merge with transformers

    protected function roundForPrice(?float $number): ?float
    {
        return is_null($number) ? null : round($number, 2);
    }
}