<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class DefaultFieldValuesForm extends Form
{
    public function buildForm()
    {
        $this
            ->add(
                'default_currency',
                'select',
                [
                    'choices' => ['AED' => 'UAE Dirham', 'AFN' => 'Afghani']
                ]
            )
            ->add(
                'default_language',
                'select',
                [
                    'choices' => ['es' => 'Espanish', 'fr' => 'French']
                ]
            )
            ->add('default_hierarchy', 'text')
            ->add(
                'default_collaboration_type',
                'select',
                [
                    'choices' => ['1' => 'bilateral', '2' => 'multilateral']
                ]
            )
            ->add(
                'default_flow_type',
                'select',
                [
                    'choices' => ['10' => 'ODA', '20' => 'OOF']
                ]
            )
            ->add(
                'default_finance_type',
                'select',
                [
                    'choices' => ['310' => 'Deposit basis', '311' => 'Encashment basis']
                ]
            )
            ->add(
                'default_aid_type',
                'select',
                [
                    'choices' => ['A01' => 'General Budget Support', 'A02' => 'Sector Budget Support']
                ]
            )
            ->add(
                'Default_tied_status',
                'select',
                [
                    'choices' => ['3' => 'Partially Tied', '4' => 'Tied']
                ]
            );
    }
}