<?php namespace App\Http\Controllers\Tz\Project;

use App\Http\Controllers\Tz\TanzanianController;
use App\Tz\Aidstream\Services\Project\ProjectService;

/**
 * Class ProjectController
 * @package App\Http\Controllers\Tz\Project
 */
class ProjectController extends TanzanianController
{
    /**
     * @var ProjectService
     */
    protected $project;

    /**
     * ProjectController constructor.
     * @param ProjectService $project
     */
    public function __construct(ProjectService $project)
    {
        $this->project = $project;
    }
}
