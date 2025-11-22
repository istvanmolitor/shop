<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Customer\Models\Customer;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Language\Repositories\LanguageRepositoryInterface;

class ShopProfileController extends BaseController
{
    public function show(Request $request, CountryRepositoryInterface $countryRepository)
    {
        $user = $request->user();
        $customer = Customer::query()
            ->with(['invoiceAddress', 'shippingAddress'])
            ->where('user_id', $user->id)
            ->first();
        $countries = $countryRepository->getAll();

        return view('shop::profile.show', compact('user', 'customer', 'countries'));
    }

    public function update(
        Request $request,
        AddressRepositoryInterface $addressRepository,
        CurrencyRepositoryInterface $currencyRepository,
        LanguageRepositoryInterface $languageRepository
    ): RedirectResponse {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'invoice.name' => ['nullable', 'string', 'max:255'],
            'invoice.country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'invoice.zip_code' => ['nullable', 'string', 'max:10'],
            'invoice.city' => ['nullable', 'string', 'max:255'],
            'invoice.address' => ['nullable', 'string', 'max:255'],
            'shipping.name' => ['nullable', 'string', 'max:255'],
            'shipping.country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'shipping.zip_code' => ['nullable', 'string', 'max:10'],
            'shipping.city' => ['nullable', 'string', 'max:255'],
            'shipping.address' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($user, $data, $addressRepository, $currencyRepository, $languageRepository) {
            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            $user->save();

            $customer = Customer::query()->where('user_id', $user->id)->first();
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $data['customer_name'] ?: $user->name,
                    'internal_name' => $user->email,
                    'is_buyer' => true,
                    'user_id' => $user->id,
                    'currency_id' => $currencyRepository->getDefaultId(),
                    'language_id' => $languageRepository->getDefaultId(),
                ]);
            } else {
                // update customer name if provided
                if (!empty($data['customer_name'])) {
                    $customer->name = $data['customer_name'];
                    $customer->save();
                }
            }

            // Ensure relations loaded
            $customer->load(['invoiceAddress', 'shippingAddress']);

            if (!empty($data['invoice'] ?? [])) {
                $addressRepository->saveAddress($customer->invoiceAddress, $data['invoice']);
            }
            if (!empty($data['shipping'] ?? [])) {
                $addressRepository->saveAddress($customer->shippingAddress, $data['shipping']);
            }
        });

        return redirect()->route('shop.profile.show')->with('status', __('Profil frissítve.'));
    }
}
