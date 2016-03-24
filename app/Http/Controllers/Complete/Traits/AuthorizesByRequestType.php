<?php namespace App\Http\Controllers\Complete\Traits;


use Illuminate\Database\Eloquent\Model;

trait AuthorizesByRequestType
{
    /**
     * @param Model $model
     * @param       $column
     * @param bool  $isResult
     * @return mixed
     */
    protected function authorizeByRequestType(Model $model, $column, $isResult = false)
    {
        if (!$isResult) {
            return $this->generalAuthorization($model, $column);
        }

        return $this->authorizeForResults($model, $column);

    }

    /**
     * @param Model $model
     * @param       $column
     * @return mixed
     */
    protected function generalAuthorization(Model $model, $column)
    {
        if (is_null($model->$column) || $model->$column == "" ) {
            return $this->authorize('add_activity');
        }

        return $this->authorize('edit_activity');
    }

    /**
     * @param Model $model
     * @param       $column
     * @return mixed
     */
    protected function authorizeForResults(Model $model, $column)
    {
        if (!array_key_exists($column, $model->toArray())) {
            return $this->authorize('add_activity');
        }

        return $this->authorize('edit_activity');
    }
}


