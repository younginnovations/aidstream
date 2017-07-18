<?php namespace App\Http\Controllers;

use App\Http\Requests\Request;

/**
 * Class OrganisationFinder
 * @package App\Http\Controllers
 */
class OrganisationFinder extends Controller
{

    const PUBLISHER_FILE = 'data/publishers.json';
    const ORG_FILE       = 'data/org.json';

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
            $file              = $countryRegistrars->merge($file->where('coverage', null));
        }

//        if ($request->has('name')) {
//            $file = $file->where('name.en', $request->get('name'));
//        }
        return $file->isEmpty() ? response([], 404) : response()->json($file->values(), 200);
    }
}
