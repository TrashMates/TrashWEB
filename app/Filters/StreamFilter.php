<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StreamFilter extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }


    /**
     * FILTER - Skip the first X results
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function after(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->skip($query);
        }

        return null;
    }

    /**
     * FILTER - Search all streams of a specific game_id
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function game_id(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->where("game_id", $query);
        }

        return null;
    }

    /**
     * FILTER - Search all streams of a specific language
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function language(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->where("language", $query);
        }

        return null;
    }

    /**
     * FILTER - Select only the latest X elements
     *
     * @param int|null $query
     * @return Builder|null
     */
    public function limit(?int $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orderBy("created_at", "DESC")->take($query);
        }
    }

    /**
     * FILTER - Select specific columns
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function select(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->select(explode(",", $query));
        }

        return null;
    }

    /**
     * FILTER - Select live counts
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function stats(?string $query = null): ?Builder
    {
        if ($this->request->has("stats")) {
            return $this->builder->selectRaw("created_at, language, COUNT(stopped_at) AS finished, COUNT(*) AS total")->groupBy(\DB::raw("DAY(created_at), MONTH(created_at), language"));
        }

        return null;
    }

    /**
     * FILTER - Search all streams matching this title
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function title(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->where("title", "LIKE", "%{$query}%");
        }

        return null;
    }

    /**
     * FILTER - Search all streams of a specific type
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function type(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->where("type", $query);
        }

        return null;
    }

    /**
     * FILTER - Search all streams of a specific user_id
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function user_id(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->where("user_id", $query);
        }

        return null;
    }
}
