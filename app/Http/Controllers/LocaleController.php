<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Change the application locale
     */
    public function change(Request $request, string $locale)
    {
        $availableLocales = config('app.available_locales', ['en']);

        // Validate the locale
        if (!in_array($locale, $availableLocales)) {
            abort(400, 'Invalid locale');
        }

        // Store the locale in session
        Session::put('locale', $locale);

        // Redirect back to the previous page
        return redirect()->back();
    }
}
