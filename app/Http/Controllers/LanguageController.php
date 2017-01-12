<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function switchLanguage($language)
    {
        if (array_key_exists($language, Config::get('languages'))) {
            return redirect()->back()->withCookie('language', $language);
        }

        return redirect()->back();
    }

    public function localisedFormText()
    {
        $currentLanguage = ($language = (Cookie::get('language'))) ? $language : 'en';

        return file_get_contents(sprintf(resource_path('lang/%s/script.json'), $currentLanguage));
    }
}
