<?php namespace App\Core\V203\Requests\Activity;

use App\Core\V201\Requests\Activity\ActivityBaseRequest as ActivityBaseRequest;

/**
 * Class Sector
 * @package App\Core\V202\Requests\Activity
 */
class Tag extends ActivityBaseRequest
{

    public function rules()
    {
        return $this->getTagsRules($this->get('tag'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->getTagsMessages($this->get('tag'));
    }

    /**
     * returns rules for tag
     * @param $formFields
     * @return array|mixed
     */
    public function getTagsRules($formFields)
    {
        $rules = [];
        foreach ($formFields as $tagIndex => $tag) {
            $tagForm                                                = sprintf('tag.%s', $tagIndex);
            $rules[sprintf('%s.tag_vocabulary', $tagForm)]          = 'required';
            // $rules[sprintf('%s.narrative.0.narrative',$tagForm)] = 'required';
            $rules[sprintf('%s.tag_code', $tagForm)]                = 'required';
            if($tag['tag_vocabulary'] == 99) {
                $rules[sprintf('%s.vocabulary_uri', $tagForm)] = 'url|required_with:' . $tagForm . '.tag_vocabulary';
            }
        }

        return $rules;
    }

    /**
     * returns messages for tag
     * @param $formFields
     * @return array|mixed
     */
    public function getTagsMessages($formFields)
    {
        $messages = [];

        foreach ($formFields as $tagIndex => $tag) {
            $tagForm                                                      = sprintf('tag.%s', $tagIndex);
            // $messages[sprintf('%s.vocabulary_uri.url', $tagForm)]         = trans('validation.url');
            $messages[sprintf('%s.tag_vocabulary.required', $tagForm)]    = trans('validation.required', ['attribute' => trans('elementForm.tag_vocabulary')]);
            $messages[sprintf('%s.narrative.0.narrative.required', $tagForm)]      = trans('validation.required', ['attribute' => trans('elementForm.narrative')]);
            $messages[sprintf('%s.tag_code.required', $tagForm)]      = trans('validation.required', ['attribute' => trans('elementForm.tag_code')]);

            // $messages[sprintf('%s.tag_code.%s', $tagForm, 'required_with')] = trans(
            //     'validation.required_with',
            //     ['attribute' => trans('elementForm.tag_code'), 'values' => trans('elementForm.tag_vocabulary')]
            // );
            // if ($tag['tag_vocabulary'] == 1) {
                // if ($tag['tag_vocabulary'] == 1) {

                // }
                // if ($tag['tag_code'] != "") {
                //     $messages[sprintf('%s.tag_vocabulary.%s', $tagForm, 'required_with')] = trans(
                //         'validation.required_with',
                //         [
                //             'attribute' => trans('elementForm.tag_vocabulary'),
                //             'values'    => trans('elementForm.tag_code')
                //         ]
                //     );
                // }
            if($tag['tag_vocabulary'] == 99) {
                // if ($tag['tag_vocabulary'] != "") {
                //     $messages[sprintf('%s.tag_text.%s', $tagForm, 'required_with')] = trans(
                //         'validation.required_with',
                //         ['attribute' => trans('elementForm.tag_code'), 'values' => trans('elementForm.tag_vocabulary')]
                //     );
                // }

                // if ($tag['tag_text'] != "") {
                //     $messages[sprintf('%s.tag_vocabulary.%s', $tagForm, 'required_with')] = trans(
                //         'validation.required_with',
                //         [
                //             'attribute' => trans('elementForm.tag_vocabulary'),
                //             'values'    => trans('elementForm.tag_code')
                //         ]
                //     );
                // }

                // if ($tag['tag_vocabulary'] == "99" || $tag['tag_vocabulary'] == "98") {
                    $messages[sprintf('%s.vocabulary_uri.%s', $tagForm, 'required_with')] = trans(
                        'validation.required_with',
                        [
                            'attribute' => trans('elementForm.vocabulary_uri'),
                            'values'    => trans('elementForm.tag_vocabulary')
                        ]
                    );
                // }
            }

            // $messages[sprintf('%s.percentage.numeric', $tagForm)]  = trans('validation.numeric', ['attribute' => trans('elementForm.percentage')]);
            // $messages[sprintf('%s.percentage.max', $tagForm)]      = trans('validation.max.numeric', ['attribute' => trans('elementForm.percentage'), 'max' => 100]);
            // $messages[sprintf('%s.percentage.required', $tagForm)] = trans('validation.required', ['attribute' => trans('elementForm.percentage')]);
            // $messages[sprintf('%s.percentage.sum', $tagForm)]      = trans('validation.sum', ['attribute' => trans('elementForm.percentage')]);
            $messages                                                 = array_merge($messages, $this->getMessagesForNarrative($tag['narrative'], $tagForm));
        }

        return $messages;
    }
}
