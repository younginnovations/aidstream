<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class DefaultFieldValuesForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('default currency', 'select', [
                'choices' => ['AED' => 'UAE Dirham', 'AFN' => 'Afghani']
            ])
            ->add('default language', 'select', [
                'choices' => ['es' => 'Espanish', 'fr' => 'French']
            ])
            ->add('default hierarchy', 'text')
            ->add('default collaboration type', 'select', [
                'choices' => ['1' => 'bilateral', '2' => 'multilateral']
            ])
            ->add('default flow type', 'select', [
                'choices' => ['10' => 'ODA', '20' => 'OOF']
            ])
            ->add('default finance type', 'select', [
                'choices' => ['310' => 'Deposit basis', '311' => 'Encashment basis']
            ])
            ->add('default aid type', 'select', [
                'choices' => ['A01' => 'General Budget Support', 'A02' => 'Sector Budget Support']
            ])
            ->add('Default tied status', 'select', [
                'choices' => ['3' => 'Partially Tied', '4' => 'Tied']
            ]);
    }
}