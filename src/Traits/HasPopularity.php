<?php

namespace KanojoDb\LaravelPopular\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use KanojoDb\LaravelPopular\Models\Visit;
use ReflectionClass;

trait HasPopularity
{
    /**
     * Registers a visit for the current page.
     */
    public function visit(): Visit
    {
        $clientIdentifier = implode('-', array(request()->ip(), Carbon::now()->format('Y-m-d'), request()->userAgent()));

        $clientHash = hash('sha256', $clientIdentifier);

        return $this->visits()->updateOrCreate(
            [
                'client_hash' => $clientHash,
                'model_id' => $this->id,
                'model_type' => (new ReflectionClass($this))->getName(),
                'date' => Carbon::now()->toDateString(),
            ]
        );
    }

    /**
     * Setting relationship
     */
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, '');
    }

    /**
     * Return count of the visits in the last day
     *
     * @return mixed
     */
    public function visitsDay()
    {
        return $this->visitsLast(1);
    }

    /**
     * Return count of the visits in the last 7 days
     *
     * @return mixed
     */
    public function visitsWeek()
    {
        return $this->visitsLast(7);
    }

    /**
     * Return count of the visits in the last 30 days
     *
     * @return mixed
     */
    public function visitsMonth()
    {
        return $this->visitsLast(30);
    }

    /**
     * Return the count of visits since system was installed
     *
     * @return mixed
     */
    public function visitsForever()
    {
        return $this->visits()
            ->count();
    }

    /**
     * Filter by popular in the last $days days
     *
     * @return mixed
     */
    public function scopePopularLast($query, $days)
    {
        return $this->queryPopularLast($query, $days);
    }

    /**
     * Filter by popular in the last day
     *
     * @return mixed
     */
    public function scopePopularDay($query)
    {
        return $this->queryPopularLast($query, 1);
    }

    /**
     * Filter by popular in the last 7 days
     *
     * @return mixed
     */
    public function scopePopularWeek($query)
    {
        return $this->queryPopularLast($query, 7);
    }

    /**
     * Filter by popular in the last 30 days
     *
     * @return mixed
     */
    public function scopePopularMonth($query)
    {
        return $this->queryPopularLast($query, 30);
    }

    /**
     * Filter by popular in the last 365 days
     *
     * @return mixed
     */
    public function scopePopularYear($query)
    {
        return $this->queryPopularLast($query, 365);
    }

    /**
     * Filter by popular in a given interval date
     *
     * @return mixed
     */
    public function scopePopularBetween($query, $from, $to)
    {
        return $query->queryPopularBetween($query, $from, $to);
    }

    /**
     * Filter by popular in all time
     *
     * @return mixed
     */
    public function scopePopularAllTime($query)
    {
        return $query->withCount('visits')->orderBy('visits_count', 'desc');
    }

    /**
     * Return the visits of the model in the last ($days) days
     */
    public function visitsLast($days): int
    {
        return $this->visits()
            ->where('date', '>=', Carbon::now()->subDays($days)->toDateString())
            ->count();
    }

    /**
     * Return the visits of the model in a given interval date
     */
    public function visitsBetween($from, $to): int
    {
        return $this->visits()
            ->whereBetween('date', [$from, $to])
            ->count();
    }

    /**
     * Returns a Query Builder with Model ordered by popularity in the Last ($days) days
     *
     * @return mixed
     */
    public function queryPopularLast($query, $days)
    {
        return $query->withCount(['visits' => function ($query) use ($days) {
            $query->where('date', '>=', Carbon::now()->subDays($days)->toDateString());
        }])->orderBy('visits_count', 'desc');
    }

    /**
     * Returns a Query Builder with Model ordered by popularity in a given interval date
     *
     * @param $days
     * @return mixed
     */
    public function queryPopularBetween($query, $from, $to)
    {
        return $query->withCount(['visits' => function ($query) use ($from, $to) {
            $query->whereBetween('date', [$from, $to]);
        }])->orderBy('visits_count', 'desc');
    }
}
