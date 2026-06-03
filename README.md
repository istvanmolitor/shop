# Shop modul

Frontend webshop modul Laravel alkalmazásokhoz.

A csomag publikus bolti oldalakat, kosárfolyamatot, vásárlói bejelentkezést és több lépéses checkoutot ad a meglévő Molitor csomagokra építve.

## Függőségek

A csomag a következő modulokra épít:

- `istvanmolitor/menu`
- `istvanmolitor/product`
- `istvanmolitor/customer`
- `istvanmolitor/order`
- `istvanmolitor/address`
- `istvanmolitor/currency`
- `istvanmolitor/language`
- `istvanmolitor/stock`
- `livewire/livewire`

## Telepítés

### 1. Composer bekötés

Ha a csomagot a monorepón belül, local path repositoryként használod, a gyökér `composer.json` fájlba add hozzá:

```json
{
	"require": {
		"istvanmolitor/shop": "@dev"
	},
	"repositories": [
		{
			"type": "path",
			"url": "packages/shop"
		}
	]
}
```

Ezután futtasd:

```bash
vendor/bin/sail composer update istvanmolitor/shop --ignore-platform-req=php
```

Ha a konténer környezet elérhető, akkor a `vendor/bin/sail` használata az ajánlott.

### 2. Service provider

A package Composer autodiscovery-t használ, ezért külön provider-regisztráció általában nem szükséges. A csomag a következő providert regisztrálja:

```php
Molitor\Shop\Providers\ShopServiceProvider::class
```

Ez a provider:

- betölti a migrációkat,
- regisztrálja a `shop::...` view namespace-t,
- betölti a fordításokat,
- regisztrálja a webshop route-okat,
- felveszi a Blade komponenseket,
- regisztrálja a Livewire komponenseket,
- publikálja a publikus asseteket.

### 3. Migrációk

```bash
vendor/bin/sail artisan migrate
```

### 4. Publikus assetek publikálása

A fallback képek és egyéb publikus assetek a `public` tag alatt publikálhatók:

```bash
vendor/bin/sail artisan vendor:publish --tag=public
```

Ez a package assetjeit a `public/vendor/shop` könyvtárba másolja.

## Mit ad a csomag?

### Publikus oldalak

Az alábbi route-ok érhetők el:

- `GET /shop/products` - terméklista
- `GET /shop/products/{product:slug}` - termék részletező oldal
- `GET /shop/categories/{productCategory:slug}` - kategóriaoldal
- `GET /shop/cart` - kosár
- `POST /shop/cart` - termék kosárba helyezése
- `GET /shop/login` - vásárlói bejelentkezés
- `POST /shop/login` - bejelentkezés feldolgozása
- `GET /shop/register` - regisztrációs oldal
- `POST /shop/register` - regisztráció mentése
- `GET /shop/register/success` - sikeres regisztráció oldal
- `POST /shop/logout` - kijelentkezés
- `GET /email/verify/{id}/{hash}` - email megerősítés

### Auth után elérhető oldalak

Bejelentkezett felhasználóknak:

- `GET /shop/profile` - profil oldal
- `POST /shop/profile` - profil módosítása
- `GET /shop/checkout/shipping` - szállítási mód kiválasztása
- `GET /shop/checkout/shipping/{shipping:code}` - szállítási mód részletező
- `POST /shop/checkout/shipping/{shipping:code}` - szállítási mód mentése
- `GET /shop/checkout/payment` - fizetési mód választás
- `POST /shop/checkout/payment` - fizetési mód mentése
- `GET /shop/checkout/invoice` - számlázási adatok oldal
- `POST /shop/checkout/invoice` - számlázási adatok mentése
- `GET /shop/checkout/finalize` - rendelés véglegesítése
- `POST /shop/checkout/place` - rendelés leadása
- `GET /shop/checkout` - visszafelé kompatibilis checkout belépési pont
- `POST /shop/checkout` - visszafelé kompatibilis checkout mentés
- `GET /shop/orders` - rendeléslista
- `GET /shop/orders/{code}` - rendelés részletei

## Livewire komponensek

A provider az alábbi komponenseket regisztrálja:

- `shop.cart`
- `shop.products-list`
- `shop.header-cart`
- `shop.product-gallery`
- `shop.products-filter`
- `shop.sidebar-categories`

## Blade komponensek

Blade namespace: `x-shop::...`

Elérhető komponensek:

- `x-shop::checkout-steps`
- `x-shop::product-card`
- `x-shop::product-card-image`
- `x-shop::sidebar-categories`

## Nézetek

A csomag view namespace-e: `shop::`

Fontosabb nézetek:

- `shop::products.index`
- `shop::products.show`
- `shop::categories.show`
- `shop::cart.index`
- `shop::auth.login`
- `shop::auth.register`
- `shop::profile.show`
- `shop::orders.index`
- `shop::orders.show`
- `shop::checkout.*`

## Fontos működési megjegyzések

- A termékoldal a `product:slug` route model bindingot használja.
- A terméklista és a képgaléria Livewire komponensekkel működik.
- A kosár vendégként sessionben, bejelentkezve adatbázisban is tud működni.
- A termékárak a currency package `Price` osztályán keresztül jelennek meg.
- A készletinformáció a stock package `StockService` szolgáltatásából érkezik.
- A fallback termékkép a `public/vendor/shop/product/noimage.png` útvonalon érhető el publikálás után.

## Gyors használat

Terméklista oldal megjelenítése:

```php
return view('shop::products.index');
```

Termékkártya Blade komponens használata:

```blade
<x-shop::product-card :product="$product" />
```

Termékképgaléria Livewire komponens használata:

```blade
@livewire('shop.product-gallery', ['productId' => $product->id])
```

## Fejlesztői megjegyzések

- A csomag jelenleg web route-okat ad, külön API route definíció nincs benne.
- A csomag saját seedert nem tartalmaz.
- A publikus assetek hiánya esetén a fallback képek nem fognak megjelenni.
- Ha a termékképek media URL-ről jönnek, a media package publikus file-kiszolgálása is szükséges.
