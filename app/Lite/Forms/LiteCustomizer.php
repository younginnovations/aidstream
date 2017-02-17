<?php namespace App\Lite\Forms;


trait LiteCustomizer
{

    protected $allowedVersions = [3];


    public function customize($elements)
    {
        if ($this->isCurrentSystemVersionAllowed()) {
            if (is_array($elements)) {
                foreach ($elements as $index => $element) {
                    $this->$element();
                }
            } else {
                $this->$elements();
            }
        }
    }

    protected function country()
    {
        $options                  = $this->country->getOptions();
        $options['selected']      = 'TZ';
        $options['attr']['class'] = 'form-control country';

        $this->country->setOptions($options);
    }

    protected function location()
    {
        $this->addAfter(
            'location',
            'add_more_location',
            'button',
            [
                'label' => trans('lite/elementForm.add_another_location'),
                'attr'  => [
                    'data-collection' => 'location',
                    'class'           => 'hidden add-to-collection'
                ]
            ]
        );
    }

    protected function isCurrentSystemVersionAllowed()
    {
        if (in_array($this->getSystemVersion(), $this->allowedVersions)) {
            return true;
        }

        return false;
    }

    protected function getSystemVersion()
    {
        if (auth()->check()) {
            if (auth()->user()->organization) {
                return auth()->user()->organization->system_version_id;
            }
        }

        return null;
    }
}

