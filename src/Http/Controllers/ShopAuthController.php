<?php

namespace Molitor\Shop\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Molitor\Customer\Models\Customer;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Language\Repositories\LanguageRepositoryInterface;

class ShopAuthController extends BaseController
{
    public function showLogin()
    {
        return view('shop::auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('shop.products.index'));
        }

        throw ValidationException::withMessages([
            'email' => __('A megadott hitelesítési adatok nem egyeznek a nyilvántartásunkkal.'),
        ]);
    }

    public function showRegister()
    {
        return view('shop::auth.register');
    }

    public function register(
        Request $request,
        CurrencyRepositoryInterface $currencyRepository,
        LanguageRepositoryInterface $languageRepository
    ): RedirectResponse {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], // hashed by model cast
            ]);

            // Create related customer
            $customer = Customer::create([
                'name' => $data['name'],
                'internal_name' => $data['email'],
                'is_buyer' => true,
                'user_id' => $user->id,
                'currency_id' => $currencyRepository->getDefaultId(),
                'language_id' => $languageRepository->getDefaultId(),
                // invoice/shipping addresses will be auto-created in model creating hook
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            throw ValidationException::withMessages([
                'email' => __('Regisztráció sikertelen. Kérjük, próbálja meg később.'),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('shop.products.index'))
            ->with('status', __('Sikeres regisztráció!'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('shop.products.index');
    }
}
