<?php namespace App\Http\Controllers\SuperAdmin;

use App\Core\SuperAdmin\CorrectionService;
use App\Core\SuperAdmin\OrganizationService;
use App\Http\API\CKAN\CkanClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;

/**
 * Class PublishedFilesCorrectionController
 * @package App\Http\Controllers\SuperAdmin
 */
class PublishedFilesCorrectionController extends Controller
{
    /**
     * @var OrganizationService
     */
    protected $organizationService;

    /**
     * @var CorrectionService
     */
    protected $correctionService;

    /**
     * PublishedFilesCorrectionController constructor.
     * @param OrganizationService $organizationService
     * @param CorrectionService   $correctionService
     */
    public function __construct(OrganizationService $organizationService, CorrectionService $correctionService)
    {
        $this->middleware('auth.superAdmin');
        $this->organizationService = $organizationService;
        $this->correctionService   = $correctionService;
    }

    /**
     * Show the page for Publishing Correction.
     * @param         $organizationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($organizationId)
    {
        $organization               = $this->organizationService->find($organizationId);
        $publishedFiles             = $organization->publishedFiles;
        $organizationPublishedFiles = $organization->organizationPublished()->get();
        $settings                   = $organization->settings;

        return view('superAdmin.publishedFilesCorrection.show', compact('organization', 'publishedFiles', 'settings', 'organizationPublishedFiles'));
    }

    /**
     * Delete an unpublished xml file.
     * @param         $organizationId
     * @param         $fileId
     * @return mixed
     */
    public function deleteXmlFile($organizationId, $fileId)
    {
        $file = $this->organizationService->findPublishedFile($fileId);

        return $this->deleteFile($file, $organizationId);
    }

    /**
     * Unlink an Xml File.
     * @param         $organizationId
     * @param         $fileId
     * @return mixed
     */
    public function unlinkXmlFile($organizationId, $fileId)
    {
        $settings = $this->organizationService->find($organizationId)->settings;
        $file     = $this->organizationService->findPublishedFile($fileId);

        if (!$this->correctionService->unlinkFile($file, $settings)) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
                ['messages' => ['message' => 'File could not be Unlinked from the IATI Registry.'], 'type' => 'warning']
            );
        }

        if (!$this->organizationService->updatePublishedStatus($file)) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
                ['messages' => ['message' => 'File could not be updated from the IATI Registry.'], 'type' => 'warning']
            );
        }

        return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
            ['messages' => ['message' => 'File successfully Unlinked from the IATI Registry.'], 'type' => 'success']
        );
    }

    /**
     * ReSync Registry data.
     * @param $organizationId
     */
    public function reSyncRegistryData($organizationId)
    {
        $organization = $this->organizationService->find($organizationId);

        if (!$this->correctionService->getPublisherDataFor($organization)->syncPublisherData()) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withReponse(['messages' => ['message' => 'Looks like something is not right.'], 'type' => 'warning']);
        }

        return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
            ['messages' => ['message' => 'Database Successfully synced with the registry.'], 'type' => 'success']
        );
    }

    /**
     * Delete an Organization's Xml file.
     * @param $organizationId
     * @param $fileId
     * @return mixed
     */
    public function deleteOrganizationXmlFile($organizationId, $fileId)
    {
        $file = $this->organizationService->findPublishedFile($fileId, true);

        return $this->deleteFile($file, $organizationId);
    }

    /**
     * Unlink Organization File from the IATI Registry.
     * @param $organizationId
     * @param $fileId
     * @return mixed
     */
    public function unlinkOrganizationXmlFile($organizationId, $fileId)
    {
        $settings = $this->organizationService->find($organizationId)->settings;
        $file     = $this->organizationService->findPublishedFile($fileId, true);

        if (!$this->correctionService->unlinkFile($file, $settings)) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
                ['messages' => ['message' => 'File could not be Unlinked from the IATI Registry.'], 'type' => 'warning']
            );
        }

        if (!$this->organizationService->updatePublishedStatus($file)) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
                ['messages' => ['message' => 'File could not be updated from the IATI Registry.'], 'type' => 'warning']
            );
        }

        return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
            ['messages' => ['message' => 'File successfully Unlinked from the IATI Registry.'], 'type' => 'success']
        );
    }

    /**
     * Resync OrganizationPublished with the IATI Registry.
     * @param $organizationId
     * @return mixed
     */
    public function reSyncOrganizationData($organizationId)
    {
        $organization = $this->organizationService->find($organizationId);

        if (!$this->correctionService->getPublisherDataFor($organization)->syncOrgData()) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withReponse(['messages' => ['message' => 'Looks like something is not right.'], 'type' => 'warning']);
        }

        return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(
            ['messages' => ['message' => 'Database Successfully synced with the registry.'], 'type' => 'success']
        );
    }

    /**
     * Delete a file.
     * @param $file
     * @param $organizationId
     * @return mixed
     */
    protected function deleteFile($file, $organizationId)
    {
        if ($this->correctionService->isLinkedToRegistry($file)) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(['messages' => ['message' => 'File is linked with the registry.'], 'type' => 'warning']);
        }

        if (!$this->correctionService->delete($file)) {
            return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(['messages' => ['message' => 'File could not be deleted.'], 'type' => 'warning']);
        }

        return redirect()->route('superadmin.correct-published-files', $organizationId)->withResponse(['messages' => ['message' => 'File Successfully deleted.'], 'type' => 'success']);
    }
}
