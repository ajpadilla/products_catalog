<?php


namespace App\Repositories;


use App\IdentificationType;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;


class IdentificationTypeRepository extends AbstractRepository
{

    /**
     * IdentificationTypeRepository constructor.
     * @param IdentificationType $model
     */
    function __construct(IdentificationType $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $joins
     * @param $table
     * @param $first
     * @param $second
     * @param string $join_type
     */
    private function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner')
    {
        if (!$joins->has($table)) {
            $joins->put($table, json_encode(compact('first', 'second', 'join_type')));
        }
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], $count = false)
    {
        /** @var Builder $query */
        $query = $this->model
            ->distinct()
            ->select('identification_types.*');

        $joins = collect();

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });


        if ($count) {
            return $query->count('identification_types.id');
        }

        return $query->orderBy('identification_types.id');
    }

}
