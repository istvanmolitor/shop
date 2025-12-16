<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Customer\Models\Customer;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Language\Repositories\LanguageRepositoryInterface;
use Molitor\Shop\Http\Requests\ProfileUpdateRequest;

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
        ProfileUpdateRequest $request,
        AddressRepositoryInterface $addressRepository,
        CurrencyRepositoryInterface $currencyRepository,
        LanguageRepositoryInterface $languageRepository
    ): RedirectResponse {
        $user = $request->user();
        $data = $request->validated();

        DB::transaction(function () use ($user, $data, $addressRepository, $currencyRepository, $languageRepository) {
            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            $user->save();

            $customer = Customer::query()->where('user_id', $user->id)->first();
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $data['customer_name'],
                    'internal_name' => $user->email,
                    'is_buyer' => true,
                    'user_id' => $user->id,
                    'currency_id' => $currencyRepository->getDefaultId(),
                    'language_id' => $languageRepository->getDefaultId(),
                ]);
            } else {
                // update customer name
                $customer->name = $data['customer_name'];
                $customer->save();
            }

            // Ensure relations loaded
            $customer->load(['invoiceAddress', 'shippingAddress']);

            // Save invoice and shipping addresses (all fields are required)
            $addressRepository->saveAddress($customer->invoiceAddress, $data['invoice']);
            $addressRepository->saveAddress($customer->shippingAddress, $data['shipping']);
        });

        return redirect()->route('shop.profile.show')->with('status', __('Profil frissítve.'));
    }
}
