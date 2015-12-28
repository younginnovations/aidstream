<?php namespace App\Core\V202\Xml\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class HumanitarianScope
 * @package App\Core\V202\Xml\Organization
 */
class HumanitarianScope extends BaseElement
{
    /**
     * @param Activity $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityHumanitarianScope = [];
        $humanitarianScopes        = (array) $activity->humanitarian_scope;
        foreach ($humanitarianScopes as $humanitarianScope) {
            $activityHumanitarianScope[] = [
                '@attributes' => [
                    'type'           => $humanitarianScope['type'],
                    'vocabulary'     => $humanitarianScope['vocabulary'],
                    'vocabulary-uri' => $humanitarianScope['vocabulary_uri'],
                    'code'           => $humanitarianScope['code'],
                ],
                'narrative'   => $this->buildNarrative($humanitarianScope['narrative'])
            ];
        }

        return $activityHumanitarianScope;
    }
}
