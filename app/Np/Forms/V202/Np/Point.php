<?php namespace App\Np\Forms\V202\Np;


use App\Np\Forms\NpBaseForm;

class Point extends NpBaseForm
{
    public function buildForm()
    {
        $this->add(
            'latitude',
            'text',
            [
                'attr' => ['class' => 'latitude'],
                'label' => trans('elementForm.latitude'),
                'help_block' => $this->addHelpText('Activity_Location_Point_Pos-latitude'),
                'wrapper' => ['class' => 'col-sm-6 hidden']
            ]
        )
            ->add(
                'longitude',
                'text',
                [
                    'attr' => ['class' => 'longitude'],
                    'label' => trans('elementForm.longitude'),
                    'help_block' => $this->addHelpText('Activity_Location_Point_Pos-longitude'),
                    'wrapper' => ['class' => 'col-sm-6 hidden']
                ]
            )
            ->add(
                'map',
                'static',
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'map_container',
                        'style' => 'height: 400px'
                    ],
                    'wrapper' => ['class' => 'form-group full-width-wrap']
                ]
            );
    }
}