<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GameFilters extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }


    /**
     * FILTER - Search all users of a matching box_art_url
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function box_art_url(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("box_art_url", "LIKE", "%{$query}%");
        }

        return null;
    }

    /**
     * FILTER - Search all users with a specific id
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function id(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("id", "LIKE", "%{$query}%");
        }

        return null;
    }

    /**
     * FILTER - Search all users of a matching username
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function name(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("name", "LIKE", "%{$query}%");
        }

        return null;
    }
}
