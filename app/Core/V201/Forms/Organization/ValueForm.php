<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;
use Illuminate\Http\Response;

class ValueForm extends Form
{
    public function buildForm()
    {
        $json = file_get_contents(app_path("Core/V201/Codelist/". config('app.locale'). "/Organization/CurrencyCodelist.json"));
        $response = json_decode($json,true);
        $currency = $response['Currency'];
        $code_arr = [];
        foreach($currency as $val) {
            $code_arr[$val['code']] = $val['code'] . ' - ' . $val['name'];
        }
        $this
            ->add('amount', 'text')
            ->add('currency', 'select', [
                'choices' => $code_arr,
                'label' => 'Language'
            ])
            ->add('value_date', 'date');
    }
}