<?php namespace App\Lite\Services\PublishedFiles;

use App\Lite\Contracts\PublishedFilesRepositoryInterface;
use App\Lite\Services\Traits\ProvidesLoggerContext;
use App\Services\Publisher\Publisher;
use Psr\Log\LoggerInterface;

/**
 * Class PublishedFilesService
 * @package App\Lite\Services\PublishedFiles
 */
class PublishedFilesService
{
    use ProvidesLoggerContext;

    /**
     * @var PublishedFilesRepositoryInterface
     */
    protected $publishedFilesRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Publisher
     */
    protected $publisher;

    /**
     * PublishedFilesService constructor.
     * @param PublishedFilesRepositoryInterface $publishedFilesRepository
     * @param Publisher                         $publisher
     * @param LoggerInterface                   $logger
     */
    public function __construct(PublishedFilesRepositoryInterface $publishedFilesRepository, Publisher $publisher, LoggerInterface $logger)
    {
        $this->publishedFilesRepository = $publishedFilesRepository;
        $this->logger                   = $logger;
        $this->publisher                = $publisher;
    }

    /**
     * Get all published files for the current Organization.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->publishedFilesRepository->all();
    }

    /**
     * Delete an XML file.
     *
     * @param $id
     * @return bool|null
     */
    public function delete($id)
    {
        try {
            $this->publishedFilesRepository->delete($id);
            $this->logger->info('File successfully deleted', $this->getContext());

            return true;
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('File could not be deleted due to %s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Publish multiple Activity Files.
     *
     * @param array $files
     * @return bool|null
     */
    public function publish(array $files)
    {
        try {
            $this->beginPublishing($files);
            $this->logger->info(sprintf('File(s) successfully published.'), $this->getContext());

            return true;
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('File(s) could not be published due to  %s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Extract ActivityPublished files model from the provided fileMetaData.
     *
     * @param $fileMetaData
     * @return \App\Models\ActivityPublished
     */
    protected function extractActivityFile($fileMetaData)
    {
        $pieces = explode(':', $fileMetaData);
        list($fileId, $filename) = [$pieces[0], $pieces[1]];

        return $this->publishedFilesRepository->findActivity($fileId);
    }

    /**
     * Publish multiple selected Activity Files.
     *
     * @param array $data
     */
    protected function beginPublishing(array $data)
    {
        foreach ($data['activity_files'] as $file) {
            $activityPublishedFile = $this->extractActivityFile($file);
            $organization          = $activityPublishedFile->organization;
            $settings              = $organization->settings;

            $this->publisher->publishFile($settings->registry_info, $activityPublishedFile, $organization, $settings->publishing_type);
        }
    }

}
