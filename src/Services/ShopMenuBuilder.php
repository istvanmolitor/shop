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
            // Profile menu item with submenus (icon only, no label)
            $profileMenuItem = $menu->addItem('', null)
                ->setIcon('heroicon-o-user-circle');

            // My Profile as submenu item under Profile
            $profileMenuItem->addItem(__('shop::common.menu.my_profile'), route('shop.profile.show'))
                ->setIcon('heroicon-o-user');

            // Orders as submenu item under Profile
            $profileMenuItem->addItem(__('shop::common.menu.orders'), route('shop.orders.index'))
                ->setIcon('heroicon-o-shopping-cart');

            // Logout as submenu item under Profile
            $profileMenuItem->addItem(__('shop::common.menu.logout'), route('shop.logout'))
                ->setName('logout')
                ->setIcon('heroicon-o-arrow-right-on-rectangle');

            // Admin link (only for users with admin permission)
            if (auth()->user()->can('acl', 'admin')) {
                $menu->addItem(__('shop::common.menu.admin'), '/admin')
                    ->setIcon('heroicon-o-cog-6-tooth');
            }
        } else {
            // Guest user menu items
            $menu->addItem(__('shop::common.menu.login'), route('shop.login'))
                ->setIcon('heroicon-o-arrow-right-on-rectangle');
            $menu->addItem(__('shop::common.menu.register'), route('shop.register'))
                ->setIcon('heroicon-o-user-plus');
        }
    }
}

