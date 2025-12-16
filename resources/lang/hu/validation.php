<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'A(z) :attribute el kell fogadni.',
    'accepted_if' => 'A(z) :attribute el kell fogadni, ha :other értéke :value.',
    'active_url' => 'A(z) :attribute nem érvényes URL.',
    'after' => 'A(z) :attribute :date utáni dátum kell, hogy legyen.',
    'after_or_equal' => 'A(z) :attribute nem lehet korábbi dátum, mint :date.',
    'alpha' => 'A(z) :attribute kizárólag betűket tartalmazhat.',
    'alpha_dash' => 'A(z) :attribute kizárólag betűket, számokat, kötőjeleket és alulvonásokat tartalmazhat.',
    'alpha_num' => 'A(z) :attribute kizárólag betűket és számokat tartalmazhat.',
    'array' => 'A(z) :attribute egy tömb kell, hogy legyen.',
    'ascii' => 'A(z) :attribute kizárólag egy-bájtos alfanumerikus karaktereket és szimbólumokat tartalmazhat.',
    'before' => 'A(z) :attribute :date előtti dátum kell, hogy legyen.',
    'before_or_equal' => 'A(z) :attribute nem lehet későbbi dátum, mint :date.',
    'between' => [
        'array' => 'A(z) :attribute :min - :max elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete :min és :max kilobájt között kell, hogy legyen.',
        'numeric' => 'A(z) :attribute :min és :max között kell, hogy legyen.',
        'string' => 'A(z) :attribute hossza :min és :max karakter között kell, hogy legyen.',
    ],
    'boolean' => 'A(z) :attribute mező csak true vagy false értéket kaphat.',
    'can' => 'A(z) :attribute mező nem engedélyezett értéket tartalmaz.',
    'confirmed' => 'A(z) :attribute megerősítése nem egyezik.',
    'contains' => 'A(z) :attribute nem tartalmazza a szükséges értéket.',
    'current_password' => 'A jelszó helytelen.',
    'date' => 'A(z) :attribute nem érvényes dátum.',
    'date_equals' => 'A(z) :attribute :date-el egyező dátum kell, hogy legyen.',
    'date_format' => 'A(z) :attribute nem egyezik az alábbi formátummal: :format.',
    'decimal' => 'A(z) :attribute :decimal tizedesjegyet kell, hogy tartalmazzon.',
    'declined' => 'A(z) :attribute el kell utasítani.',
    'declined_if' => 'A(z) :attribute el kell utasítani, ha :other értéke :value.',
    'different' => 'A(z) :attribute és :other különböző kell, hogy legyen.',
    'digits' => 'A(z) :attribute :digits számjegyű kell, hogy legyen.',
    'digits_between' => 'A(z) :attribute :min és :max közötti számjegyű kell, hogy legyen.',
    'dimensions' => 'A(z) :attribute felbontása nem megfelelő.',
    'distinct' => 'A(z) :attribute ismétlődő értéket tartalmaz.',
    'doesnt_end_with' => 'A(z) :attribute nem végződhet a következők valamelyikével: :values.',
    'doesnt_start_with' => 'A(z) :attribute nem kezdődhet a következők valamelyikével: :values.',
    'email' => 'A(z) :attribute érvényes e-mail cím kell, hogy legyen.',
    'ends_with' => 'A(z) :attribute a következők valamelyikével kell végződjön: :values.',
    'enum' => 'A kiválasztott :attribute érvénytelen.',
    'exists' => 'A kiválasztott :attribute érvénytelen.',
    'extensions' => 'A(z) :attribute a következő kiterjesztések valamelyikével kell rendelkezzen: :values.',
    'file' => 'A(z) :attribute fájl kell, hogy legyen.',
    'filled' => 'A(z) :attribute megadása kötelező.',
    'gt' => [
        'array' => 'A(z) :attribute több, mint :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nagyobb kell, hogy legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nagyobb kell, hogy legyen, mint :value.',
        'string' => 'A(z) :attribute hosszabb kell, hogy legyen, mint :value karakter.',
    ],
    'gte' => [
        'array' => 'A(z) :attribute legalább :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nem lehet kisebb, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nem lehet kisebb, mint :value.',
        'string' => 'A(z) :attribute nem lehet rövidebb, mint :value karakter.',
    ],
    'hex_color' => 'A(z) :attribute érvényes hexadecimális színkód kell, hogy legyen.',
    'image' => 'A(z) :attribute kép kell, hogy legyen.',
    'in' => 'A kiválasztott :attribute érvénytelen.',
    'in_array' => 'A(z) :attribute nem létezik a(z) :other értékek között.',
    'integer' => 'A(z) :attribute egész szám kell, hogy legyen.',
    'ip' => 'A(z) :attribute érvényes IP-cím kell, hogy legyen.',
    'ipv4' => 'A(z) :attribute érvényes IPv4-cím kell, hogy legyen.',
    'ipv6' => 'A(z) :attribute érvényes IPv6-cím kell, hogy legyen.',
    'json' => 'A(z) :attribute érvényes JSON szöveg kell, hogy legyen.',
    'list' => 'A(z) :attribute lista kell, hogy legyen.',
    'lowercase' => 'A(z) :attribute kisbetűs kell, hogy legyen.',
    'lt' => [
        'array' => 'A(z) :attribute kevesebb, mint :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete kisebb kell, hogy legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute kisebb kell, hogy legyen, mint :value.',
        'string' => 'A(z) :attribute rövidebb kell, hogy legyen, mint :value karakter.',
    ],
    'lte' => [
        'array' => 'A(z) :attribute legfeljebb :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nem lehet nagyobb, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nem lehet nagyobb, mint :value.',
        'string' => 'A(z) :attribute nem lehet hosszabb, mint :value karakter.',
    ],
    'mac_address' => 'A(z) :attribute érvényes MAC-cím kell, hogy legyen.',
    'max' => [
        'array' => 'A(z) :attribute legfeljebb :max elemet tartalmazhat.',
        'file' => 'A(z) :attribute mérete nem lehet több, mint :max kilobájt.',
        'numeric' => 'A(z) :attribute nem lehet nagyobb, mint :max.',
        'string' => 'A(z) :attribute nem lehet hosszabb, mint :max karakter.',
    ],
    'max_digits' => 'A(z) :attribute nem tartalmazhat :max számjegynél többet.',
    'mimes' => 'A(z) :attribute a következő fájltípusok egyike kell, hogy legyen: :values.',
    'mimetypes' => 'A(z) :attribute a következő fájltípusok egyike kell, hogy legyen: :values.',
    'min' => [
        'array' => 'A(z) :attribute legalább :min elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete legalább :min kilobájt kell, hogy legyen.',
        'numeric' => 'A(z) :attribute legalább :min kell, hogy legyen.',
        'string' => 'A(z) :attribute legalább :min karakter kell, hogy legyen.',
    ],
    'min_digits' => 'A(z) :attribute legalább :min számjegyet kell, hogy tartalmazzon.',
    'missing' => 'A(z) :attribute mező hiányzik.',
    'missing_if' => 'A(z) :attribute mező hiányzik, ha :other értéke :value.',
    'missing_unless' => 'A(z) :attribute mező hiányzik, kivéve, ha :other értéke :value.',
    'missing_with' => 'A(z) :attribute mező hiányzik, ha :values van jelen.',
    'missing_with_all' => 'A(z) :attribute mező hiányzik, ha :values vannak jelen.',
    'multiple_of' => 'A(z) :attribute :value többszöröse kell, hogy legyen.',
    'not_in' => 'A kiválasztott :attribute érvénytelen.',
    'not_regex' => 'A(z) :attribute formátuma érvénytelen.',
    'numeric' => 'A(z) :attribute szám kell, hogy legyen.',
    'password' => [
        'letters' => 'A(z) :attribute legalább egy betűt kell, hogy tartalmazzon.',
        'mixed' => 'A(z) :attribute legalább egy kis- és egy nagybetűt kell, hogy tartalmazzon.',
        'numbers' => 'A(z) :attribute legalább egy számot kell, hogy tartalmazzon.',
        'symbols' => 'A(z) :attribute legalább egy szimbólumot kell, hogy tartalmazzon.',
        'uncompromised' => 'A megadott :attribute adatszivárgásban szerepelt. Kérjük, válasszon másik :attribute-t.',
    ],
    'present' => 'A(z) :attribute mező üres kell, hogy legyen.',
    'present_if' => 'A(z) :attribute mezőnek jelen kell lennie, ha :other értéke :value.',
    'present_unless' => 'A(z) :attribute mezőnek jelen kell lennie, kivéve, ha :other értéke :value.',
    'present_with' => 'A(z) :attribute mezőnek jelen kell lennie, ha :values van jelen.',
    'present_with_all' => 'A(z) :attribute mezőnek jelen kell lennie, ha :values vannak jelen.',
    'prohibited' => 'A(z) :attribute mező nem engedélyezett.',
    'prohibited_if' => 'A(z) :attribute mező nem engedélyezett, ha :other értéke :value.',
    'prohibited_unless' => 'A(z) :attribute mező nem engedélyezett, kivéve, ha :other értéke :values valamelyike.',
    'prohibits' => 'A(z) :attribute mező :other jelenlétét tiltja.',
    'regex' => 'A(z) :attribute formátuma érvénytelen.',
    'required' => 'A(z) :attribute megadása kötelező.',
    'required_array_keys' => 'A(z) :attribute mezőnek tartalmaznia kell a következő kulcsokat: :values.',
    'required_if' => 'A(z) :attribute megadása kötelező, ha :other értéke :value.',
    'required_if_accepted' => 'A(z) :attribute megadása kötelező, ha :other el van fogadva.',
    'required_if_declined' => 'A(z) :attribute megadása kötelező, ha :other el van utasítva.',
    'required_unless' => 'A(z) :attribute megadása kötelező, kivéve, ha :other értéke :values valamelyike.',
    'required_with' => 'A(z) :attribute megadása kötelező, ha :values közül valamelyik meg van adva.',
    'required_with_all' => 'A(z) :attribute megadása kötelező, ha :values mindegyike meg van adva.',
    'required_without' => 'A(z) :attribute megadása kötelező, ha :values egyike sincs megadva.',
    'required_without_all' => 'A(z) :attribute megadása kötelező, ha :values egyike sincs megadva.',
    'same' => 'A(z) :attribute és :other értékének egyeznie kell.',
    'size' => [
        'array' => 'A(z) :attribute :size elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete :size kilobájt kell, hogy legyen.',
        'numeric' => 'A(z) :attribute :size kell, hogy legyen.',
        'string' => 'A(z) :attribute :size karakter hosszú kell, hogy legyen.',
    ],
    'starts_with' => 'A(z) :attribute a következők valamelyikével kell kezdődjön: :values.',
    'string' => 'A(z) :attribute szöveg kell, hogy legyen.',
    'timezone' => 'A(z) :attribute érvényes időzóna kell, hogy legyen.',
    'unique' => 'A(z) :attribute már foglalt.',
    'uploaded' => 'A(z) :attribute feltöltése sikertelen.',
    'uppercase' => 'A(z) :attribute nagybetűs kell, hogy legyen.',
    'url' => 'A(z) :attribute érvényes URL kell, hogy legyen.',
    'ulid' => 'A(z) :attribute érvényes ULID kell, hogy legyen.',
    'uuid' => 'A(z) :attribute érvényes UUID kell, hogy legyen.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'név',
        'email' => 'e-mail cím',
        'password' => 'jelszó',
        'password_confirmation' => 'jelszó megerősítése',
        'customer_name' => 'ügyfél neve',
        'tax_number' => 'adószám',

        // Profile Update Request
        'invoice.name' => 'számlázási név',
        'invoice.country_id' => 'számlázási ország',
        'invoice.zip_code' => 'számlázási irányítószám',
        'invoice.city' => 'számlázási város',
        'invoice.address' => 'számlázási cím',
        'shipping.name' => 'szállítási név',
        'shipping.country_id' => 'szállítási ország',
        'shipping.zip_code' => 'szállítási irányítószám',
        'shipping.city' => 'szállítási város',
        'shipping.address' => 'szállítási cím',

        // Register Request
        'invoice_name' => 'számlázási név',
        'invoice_country_id' => 'számlázási ország',
        'invoice_zip_code' => 'számlázási irányítószám',
        'invoice_city' => 'számlázási város',
        'invoice_address' => 'számlázási cím',
        'shipping_name' => 'szállítási név',
        'shipping_country_id' => 'szállítási ország',
        'shipping_zip_code' => 'szállítási irányítószám',
        'shipping_city' => 'szállítási város',
        'shipping_address' => 'szállítási cím',

        // Checkout & Payment
        'billing.name' => 'számlázási név',
        'billing.country_id' => 'számlázási ország',
        'billing.zip_code' => 'számlázási irányítószám',
        'billing.city' => 'számlázási város',
        'billing.address' => 'számlázási cím',
        'billing_same_as_shipping' => 'számlázási cím megegyezik a szállítási címmel',
        'shipping_same_as_billing' => 'szállítási cím megegyezik a számlázási címmel',
        'order_payment_id' => 'fizetési mód',
        'order_shipping_id' => 'szállítási mód',
        'comment' => 'megjegyzés',
    ],

];

