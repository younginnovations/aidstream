<?php namespace App\Core\V201\Repositories\Organization;

use App\Core\Repositories\OrganizationRepositoryInterface;
use App\Http\API\CKAN\CkanClient;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use App\Models\Settings;
use App\User;
use Psr\Log\LoggerInterface;

/**
 * Class OrganizationRepository
 * @package App\Core\V201\Repositories\Organization
 */
class OrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @var LoggerInterface
     */
    protected $loggerInterface;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var OrganizationData
     */
    protected $orgData;

    /**
     * @var OrganizationPublished
     */
    protected $orgPublished;

    /**
     * @var Organization
     */
    private $org;

    /**
     * @param Organization          $org
     * @param OrganizationData      $orgData
     * @param OrganizationPublished $orgPublished
     * @param LoggerInterface       $loggerInterface
     * @param User                  $user
     */
    function __construct(Organization $org, OrganizationData $orgData, OrganizationPublished $orgPublished, LoggerInterface $loggerInterface, User $user)
    {
        $this->org             = $org;
        $this->orgData         = $orgData;
        $this->orgPublished    = $orgPublished;
        $this->loggerInterface = $loggerInterface;
        $this->user            = $user;
    }

    /**
     * write brief description
     * @param array $input
     */
    public function createOrganization(array $input)
    {
        $org                  = new Organization();
        $org->name            = json_encode($input['name']);
        $org->user_identifier = $input['user_identifier'];
        $org->address         = $input['address'];
        $org->telephone       = $input['telephone'];
        $org->reporting_org   = json_encode($input['reporting_org']);
        $org->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrganizations()
    {
        return $this->org->all();
    }

    /**
     * @param $id
     * @return model
     */
    public function getOrganization($id)
    {
        return $this->org->find($id);
    }

    /**
     * @param $input
     * @param $org
     */
    public function updateOrganization($input, $org)
    {
        $org->name            = $input['name'];
        $org->user_identifier = $input['user_identifier'];
        $org->address         = $input['address'];
        $org->telephone       = $input['telephone'];
        $org->reporting_org   = json_encode($input['reporting_org']);
        $org->status          = $input['status'];
        $org->save();
    }

    /**
     * @param $id
     * @return model
     */
    public function getOrganizationData($id)
    {
        return $this->orgData->where('organization_id', $id)->first();
    }

    /**
     * @param $organization_id
     * @return model
     */
    public function getStatus($organization_id)
    {
        return $this->orgData->where('organization_id', $organization_id)->first()->status;
    }

    /**
     * @param array            $input
     * @param OrganizationData $organizationData
     * @return bool
     */
    public function updateStatus(array $input, OrganizationData $organizationData)
    {
        $organizationData->status = $input['status'];

        return $organizationData->save();
    }

    /**
     * @param $organization_id
     */
    public function resetStatus($organization_id)
    {
        $this->orgData->where('organization_id', $organization_id)->update(['status' => 0]);
    }

    /**
     * @param $id
     * @return model
     */
    public function getPublishedFiles($id, $filename = null)
    {
        if ($filename) {
            return $this->orgPublished->where('organization_id', $id)
                                      ->where('filename', '=', $filename)
                                      ->get();
        }

        return $this->orgPublished->where('organization_id', $id)
                                  ->get();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deletePublishedFile($id)
    {
        $result = $this->orgPublished->find($id);
        if ($result) {
            $file   = sprintf('%s%s', public_path('files') . config('filesystems.xml'), $result->filename);
            $result = $result->delete();
            if ($result && file_exists($file)) {
                unlink($file);
            }
        }

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updatePublishToRegister($id)
    {
        $publishedFile                        = $this->orgPublished->find($id);
        $publishedFile->published_to_register = 1;

        return $publishedFile->save();

    }

    /**
     * @param Organization $organization
     * @param Settings     $settings
     * @param              $filename
     * @return bool
     */
    public function publishToRegistry(Organization $organization, Settings $settings, $filename)
    {
        $orgPublishedFiles = $this->getPublishedFiles($organization->id, $filename);
        $api_url           = config('filesystems.iati_registry_api_base_url');
        $apiCall           = new CkanClient($api_url, $settings['registry_info'][0]['api_id']);

        try {
            foreach ($orgPublishedFiles as $publishedFile) {
                $data = $this->generateJson($publishedFile, $settings, $organization);
                if ($publishedFile['published_to_register'] == 0) {
                    $apiCall->package_create($data);
                    $this->updatePublishToRegister($publishedFile->id);
                } elseif ($publishedFile['published_to_register'] == 1) {
//                    $package = $settings['registry_info'][0]['publisher_id'] . '-org';
                    $apiCall->package_update($data);
                }

                $this->loggerInterface->info(
                    'Organization File Published',
                    [
                        'payload' => $data
                    ]
                );
            }

            return true;
        } catch (\Exception $e) {
            if (isset($data)) {
                $this->loggerInterface->error(
                    $e,
                    [
                        'trace'   => $e->getTraceAsString(),
                        'payload' => $data
                    ]
                );

                return false;
            }

            $this->loggerInterface->error(
                $e,
                [
                    'trace' => $e->getTraceAsString()
                ]
            );

            return false;
        }
    }

    /**
     * @param              $publishedFile
     * @param Settings     $settings
     * @param Organization $organization
     * @return string
     */
    protected function generateJson($publishedFile, Settings $settings, Organization $organization)
    {
        $filePath = sprintf('%s%s%s', public_path('files'), config('filesystems.xml'), $publishedFile->filename);
        if (file_exists($filePath)) {
            $xml = simplexml_load_string(file_get_contents($filePath));
            $xml = json_decode(json_encode($xml), true);

            if (is_array($xml['iati-organisation']['name'])) {
                if (is_array($xml['iati-organisation']['name']['narrative'])) {
                    $orgTitle = $xml['iati-organisation']['name']['narrative'][0];
                } else {
                    $orgTitle = $xml['iati-organisation']['name']['narrative'];
                }
            } else {
                $orgTitle = $xml['iati-organisation']['name'];
            }
        } else {
            $organization = $this->getOrganization($organization->id);
            $orgTitle     = $organization->name;
        }

        $email        = $this->user->getUserByOrgId();
        $author_email = $email[0]->email;

        $title = $orgTitle . ' Organization File';
        $name  = $settings['registry_info'][0]['publisher_id'] . '-org';

        $data = [
            'title'        => $title,
            'name'         => $name,
            'author_email' => $author_email,
            'owner_org'    => $settings['registry_info'][0]['publisher_id'],
            'resources'    => [
                [
                    'format'   => config('xmlFiles.format'),
                    'mimetype' => config('xmlFiles.mimeType'),
                    'url'      => url(sprintf('files/xml/%s', $publishedFile->filename))
                ]
            ],
            'extras'       => [
                ['key' => 'filetype', 'value' => 'organization'],
                ['key' => 'data_updated', 'value' => $publishedFile->updated_at->toDateTimeString()],
                ['key' => 'language', 'value' => config('app.locale')],
                ['key' => 'verified', 'value' => 'no']
            ]
        ];

        return json_encode($data);
    }

    /**
     * @param $filename
     * @param $orgId
     * @return bool
     */
    public function saveOrganizationPublishedFiles($filename, $orgId)
    {
        $published = $this->orgPublished->firstOrNew(['filename' => $filename, 'organization_id' => $orgId]);
        $published->touch();
        $published->filename        = $filename;
        $published->organization_id = $orgId;

        return $published->save();
    }
}
