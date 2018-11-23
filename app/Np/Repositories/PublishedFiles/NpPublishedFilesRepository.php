<?php namespace App\Np\Repositories\PublishedFiles;

use App\Np\Contracts\NpPublishedFilesRepositoryInterface;
use App\Np\Forms\V202\Activity;
use App\Models\ActivityPublished;
use App\Models\OrganizationPublished;

/**
 * Class PublishedFilesRepository
 * @package App\Np\Repositories\PublishedFiles
 */
class NpPublishedFilesRepository implements NpPublishedFilesRepositoryInterface
{
    /**
     * @var ActivityPublished
     */
    protected $activityPublished;

    /**
     * @var OrganizationPublished
     */
    protected $organizationPublished;

    /**
     * PublishedFilesRepository constructor.
     * @param ActivityPublished     $activityPublished
     * @param OrganizationPublished $organizationPublished
     */
    public function __construct(ActivityPublished $activityPublished, OrganizationPublished $organizationPublished)
    {
        $this->activityPublished     = $activityPublished;
        $this->organizationPublished = $organizationPublished;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->activityPublished->where('organization_id', '=', session('org_id'))->get();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $publishedFile = $this->activityPublished->findOrFail($id);

        return $publishedFile->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function findActivity($fileId)
    {
        return $this->activityPublished->findOrFail($fileId);
    }
}
