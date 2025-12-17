<?php

namespace Molitor\Shop\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use Molitor\Customer\Models\Customer;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Language\Repositories\LanguageRepositoryInterface;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Shop\Http\Requests\RegisterRequest;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;
use Molitor\Shop\Services\Owner;

class ShopAuthController extends BaseController
{
    public function showLogin()
    {
        return view('shop::auth.login');
    }

    public function login(Request $request, CartProductRepositoryInterface $cartRepository): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Block unverified users from logging in
            $user = Auth::user();
            if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => __('Kérjük, erősítse meg az e-mail címét a belépés előtt. Ellenőrizze a postafiókját.'),
                ]);
            }
            
            $cartCount = $cartRepository->count(new Owner());
            if ($cartCount > 0) {
                return redirect()->route('shop.cart.index');
            }

            return redirect()->route('shop.products.index');
        }

        throw ValidationException::withMessages([
            'email' => __('A megadott hitelesítési adatok nem egyeznek a nyilvántartásunkkal.'),
        ]);
    }

    public function showRegister(CountryRepositoryInterface $countryRepository)
    {
        $countries = $countryRepository->getAll();
        $defaultCountryId = $countryRepository->getDefaultId();
        return view('shop::auth.register', compact('countries', 'defaultCountryId'));
    }

    public function register(
        RegisterRequest $request,
        CurrencyRepositoryInterface $currencyRepository,
        LanguageRepositoryInterface $languageRepository,
        AddressRepositoryInterface $addressRepository
    ): RedirectResponse {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $customer = Customer::create([
                'name' => $data['customer_name'] ?: $data['name'],
                'internal_name' => $data['email'],
                'is_buyer' => true,
                'user_id' => $user->id,
                'tax_number' => $data['tax_number'] ?? null,
                'currency_id' => $currencyRepository->getDefaultId(),
                'language_id' => $languageRepository->getDefaultId(),
            ]);

            $invoiceValues = [
                'name' => $data['invoice_name'] ?? $customer->name,
                'country_id' => $data['invoice_country_id'] ?? null,
                'zip_code' => $data['invoice_zip_code'] ?? '',
                'city' => $data['invoice_city'] ?? '',
                'address' => $data['invoice_address'] ?? '',
            ];
            $shippingValues = [
                'name' => $data['shipping_name'] ?? $customer->name,
                'country_id' => $data['shipping_country_id'] ?? null,
                'zip_code' => $data['shipping_zip_code'] ?? '',
                'city' => $data['shipping_city'] ?? '',
                'address' => $data['shipping_address'] ?? '',
            ];

            // Load address models and save values
            $customer->load(['invoiceAddress', 'shippingAddress']);
            if ($customer->invoiceAddress) {
                $addressRepository->saveAddress($customer->invoiceAddress, $invoiceValues);
            }
            if ($customer->shippingAddress) {
                $addressRepository->saveAddress($customer->shippingAddress, $shippingValues);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            throw ValidationException::withMessages([
                'email' => __('Regisztráció sikertelen. Kérjük, próbálja meg később.'),
            ]);
        }

        event(new Registered($user));
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('shop.register.success');
    }

    public function registerSuccess()
    {
        return view('shop::auth.register-success');
    }

    public function verifyEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Validate hash matches
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('shop.login')->with('status', __('Az e-mail cím már meg lett erősítve.'));
        }

        $user->markEmailAsVerified();

        return redirect()->route('shop.login')->with('status', __('Sikeres e-mail megerősítés! Most már bejelentkezhet.'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('shop.products.index');
    }
}
