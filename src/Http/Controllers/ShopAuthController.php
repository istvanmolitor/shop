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
use Molitor\Address\Repositories\AddressRepositoryInterface;

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
        LanguageRepositoryInterface $languageRepository,
        AddressRepositoryInterface $addressRepository
    ): RedirectResponse {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Customer optional fields
            'customer_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'invoice_zip_code' => ['nullable', 'string', 'max:32'],
            'invoice_city' => ['nullable', 'string', 'max:255'],
            'invoice_address' => ['nullable', 'string', 'max:255'],
            'shipping_zip_code' => ['nullable', 'string', 'max:32'],
            'shipping_city' => ['nullable', 'string', 'max:255'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
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
                'name' => $data['customer_name'] ?: $data['name'],
                'internal_name' => $data['email'],
                'is_buyer' => true,
                'user_id' => $user->id,
                'tax_number' => $data['tax_number'] ?? null,
                'currency_id' => $currencyRepository->getDefaultId(),
                'language_id' => $languageRepository->getDefaultId(),
                // invoice/shipping addresses will be auto-created in model creating hook
            ]);

            // Save addresses if provided
            $invoiceValues = [
                'name' => $customer->name,
                'zip_code' => $data['invoice_zip_code'] ?? '',
                'city' => $data['invoice_city'] ?? '',
                'address' => $data['invoice_address'] ?? '',
            ];
            $shippingValues = [
                'name' => $customer->name,
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
