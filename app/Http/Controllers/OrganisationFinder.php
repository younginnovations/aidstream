<?php namespace App\Http\Controllers;

use App\Http\Requests\Request;

/**
 * Class OrganisationFinder
 * @package App\Http\Controllers
 */
class OrganisationFinder extends Controller
{

    /**
     *
     */
    const PUBLISHER_FILE = 'data/publishers.json';
    /**
     *
     */
    const ORG_FILE = 'data/org.json';

    /**
     * IATI organisation type code to Org guide structure map
     *
     * @var array
     */
    private $iatiToOrgGuideMapper = [
        10 => 'government agency',
        15 => 'government agency',
        21 => 'charity',
        22 => 'charity',
        23 => 'charity',
        30 => 'company',
        40 => 'trust',
        60 => 'trust',
        70 => 'company',
        80 => 'company'
    ];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function findPublisher(Request $request)
    {
        $file = collect2(json_decode(file_get_contents(public_path(self::PUBLISHER_FILE)), true)['publishers']);
        list($name, $identifier, $country) = [collect([]), collect([]), collect([])];

        if ($request->has('country')) {
            $country = $file->match('country', $request->get('country'));
        }
        if ($request->has('name')) {
            $name = $file->match('name', $request->get('name'));
        }
        if ($request->has('identifier')) {
            $identifier = $file->match('identifier', $request->get('identifier'));
        }

        $file = $name->merge($identifier)->merge($country);

        return (count($file) === 0) ? response([], 404) : response()->json($file->values(), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function findOrg(Request $request)
    {
        $file = collect2(json_decode(file_get_contents(public_path(self::ORG_FILE)), true)['lists']);
        if ($request->has('country')) {
            $countryRegistrars = $file->matchRegistrar('coverage', $request->get('country'));
            $countryRegistrarsRegional = $file->matchRegistrar('coverage', 'XI');
            $countryRegistrars = $countryRegistrars->merge($countryRegistrarsRegional);
            $file = $countryRegistrars->merge($file->where('coverage', null));
        }

        if ($request->has('type')) {
            $typeRegistrars = $file->matchRegistrar('structure', $this->getOrgGuideType($request->get('type')));
            $file = $typeRegistrars->merge($file->where('coverage', null))->unique();
        }

        $filtered = [];
        foreach ($file->values() as $index => $data) {
            $filtered[] = collect($data)->only(['code', 'name', 'quality', 'url'])->toArray();
        }

        return empty($filtered) ? response([], 404) : response()->json($filtered, 200);
    }

    /**
     * Returns the org guide structure to IATI organisation type code.
     *
     * @param $type
     * @return mixed|string
     */
    protected function getOrgGuideType($type)
    {
        if ($type != "") {
            return $this->iatiToOrgGuideMapper[strtolower($type)];
        }

        return "";
    }
}
