<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Rule;
use Molitor\Customer\Models\Customer;

class ShopProfileController extends BaseController
{
    public function show(Request $request)
    {
        $user = $request->user();
        $customer = Customer::query()->where('user_id', $user->id)->first();

        return view('shop::profile.show', compact('user', 'customer'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->fill($data);
        $user->save();

        return redirect()->route('shop.profile.show')->with('status', __('Profil frissítve.'));
    }
}
