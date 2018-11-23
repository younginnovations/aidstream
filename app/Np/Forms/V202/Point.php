<?php namespace App\Np\Forms\V202;


use App\Np\Forms\NpBaseForm;

class Point extends NpBaseForm
{
    public function buildForm()
    {
        $this->add(
            'locationName',
            'text',
            [
                'attr'    => ['class' => 'locationName'],
                'label'   => trans('elementForm.name'),
                //                'help_block' => $this->addHelpText('Activity_Location_Point_Pos-latitude'),
                'wrapper' => ['class' => 'col-sm-6 hidden']
            ]
        )->add(
            'latitude',
            'text',
            [
                'attr'       => ['class' => 'latitude'],
                'label'      => trans('elementForm.latitude'),
                'help_block' => $this->addHelpText('Activity_Location_Point_Pos-latitude'),
                'wrapper'    => ['class' => 'col-sm-6 hidden']
            ]
        )
             ->add(
                 'longitude',
                 'text',
                 [
                     'attr'       => ['class' => 'longitude'],
                     'label'      => trans('elementForm.longitude'),
                     'help_block' => $this->addHelpText('Activity_Location_Point_Pos-longitude'),
                     'wrapper'    => ['class' => 'col-sm-6 hidden']
                 ]
             )
             ->add(
                 'remove_point',
                 'button',
                 [
                     'attr'  => ['class' => 'remove_from_location hidden'],
                     'label' => trans('global.delete')
                 ]
             );
    }
}