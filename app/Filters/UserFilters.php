<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFilters extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }


    /**
     * FILTER - Search all users of a specific broadcaster_type
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function broadcaster_type(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("broadcaster_type", $query);
        }

        return null;
    }

    /**
     * FILTER - Search all users with a matching description
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function description(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("description", "LIKE", "%${query}%");
        }

        return null;
    }

    /**
     * FILTER - Search all users with a matching id
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
     * FILTER - Search all users with/out an offline image
     *
     * @param bool|null $query
     * @return Builder|null
     */
    public function offline_image_url(?bool $query = null): ?Builder
    {
        if ($query === true) {
            return $this->builder->orWhereNotNull("offline_image_url");
        } elseif ($query === false) {
            return $this->builder->orWhereNull("offline_image_url");
        }

        return null;
    }

    /**
     * FILTER - Search all users with/out a profile image
     *
     * @param bool|null $query
     * @return Builder|null
     */
    public function profile_image_url(?bool $query = null): ?Builder
    {
        if ($query === true) {
            return $this->builder->orWhereNotNull("profile_image_url");
        } elseif ($query === false) {
            return $this->builder->orWhereNull("profile_image_url");
        }

        return null;
    }

    /**
     * FILTER - Search all users of a matching type
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function type(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("type", "LIKE", "%{$query}%");
        }

        return null;
    }

    /**
     * FILTER - Search all users of a matching username
     *
     * @param string|null $query
     * @return Builder|null
     */
    public function username(?string $query = null): ?Builder
    {
        if ($query) {
            return $this->builder->orWhere("username", "LIKE", "%{$query}%");
        }

        return null;
    }
}
