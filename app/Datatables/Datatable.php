<?php

namespace App\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Datatable
{
  protected string $model;

  protected array $with;

  protected bool $includeTrashed = false;

  protected bool $globalFilter = true;

  protected Builder $builder;

  public function make(Request $request): array
  {
    $pageNumber = $this->getPageNumber($request);
    $rows = $request->query('rows');

    $this->builder = $this->newModel()->newQuery();

    if ($this->includeTrashed) {
      $this->builder->withTrashed();
    }

    if (count($this->with)) {
      $this->builder = $this->builder->with($this->with);
    }

    if ($this->globalFilter) {
      $this->builder = $this->builder->where($this->globalFilter($request));
    }

    $this->builder = $this->sortAndOrder($request);

    /** @var LengthAwarePaginator $paginator */
    $paginator = $this->builder->paginate($rows, ['*'], 'page', $pageNumber);

    $collection = $paginator->getCollection()->map($this->map());

    $paginator->setCollection($collection);

    return $paginator->toArray();
  }

  protected function getPageNumber(Request $request): int
  {
    return ($request->query('first') / $request->query('rows')) + 1;
  }

  protected function newModel(): Model
  {
    return new $this->model();
  }

  protected function sortAndOrder(Request $request): Builder
  {
    if (property_exists($request->query('sortField'), true) || !property_exists($request->query('sortOrder'), true)) {
      return $this->builder;
    }

    $direction = ($request->query('sortOrder') == 1) ? 'desc' : 'asc';

    return $this->builder->orderBy($request->query('sortField'), $direction);
  }

  abstract protected function map(): callable;

  protected function globalFilter(Request $request): callable
  {
    // dd($request->query('filters'));
    $filters = $request->query('filters');

    // dd($filters['global']);
    $filterValue = $filters['global'];
    return function (Builder $query) use ($filterValue) {

      if (isset($filterValue['value']))
        $query->where('name', 'LIKE', '%' . $filterValue . '%');
      return $query;
    };
  }
}
