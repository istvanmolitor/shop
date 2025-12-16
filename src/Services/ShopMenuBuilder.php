<?php

declare(strict_types=1);

namespace Molitor\Shop\Services;

use Molitor\Menu\Services\Menu;
use Molitor\Menu\Services\MenuBuilder;

class ShopMenuBuilder extends MenuBuilder
{
    public function mainMenu(Menu $menu): void
    {
        // Products link - always visible
        $menu->addItem(__('shop::common.menu.products'), route('shop.products.index'));

        // Authenticated user menu items
        if (auth()->check()) {
            // Profile menu item with submenus
            $profileMenuItem = $menu->addItem(__('shop::common.menu.profile'), route('shop.profile.show'));

            // Orders as submenu item under Profile
            $profileMenuItem->addItem(__('shop::common.menu.orders'), route('shop.orders.index'));

            // Logout as submenu item under Profile
            $profileMenuItem->addItem(__('shop::common.menu.logout'), route('shop.logout'))
                ->setName('logout');

            // Admin link (only for users with admin permission)
            if (auth()->user()->can('acl', 'admin')) {
                $menu->addItem(__('shop::common.menu.admin'), '/admin');
            }
        } else {
            // Guest user menu items
            $menu->addItem(__('shop::common.menu.login'), route('shop.login'));
            $menu->addItem(__('shop::common.menu.register'), route('shop.register'));
        }
    }
}

