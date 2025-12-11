@props([
    // 1: Kosár, 2: Szállítás, 3: Fizetés, 4: Megrendelés
    'current' => 1,
    // Optional custom links per step: ['cart' => url, 'shipping' => url, 'payment' => url, 'finalize' => url]
    'links' => [],
])

@php
    $steps = [
        1 => ['key' => 'cart', 'label' => 'Kosár'],
        2 => ['key' => 'shipping', 'label' => 'Szállítás'],
        3 => ['key' => 'payment', 'label' => 'Fizetés'],
        4 => ['key' => 'finalize', 'label' => 'Megrendelés'],
    ];

    // Default routes for steps
    $defaultLinks = [
        'cart' => route('shop.cart.index'),
        'shipping' => route('shop.checkout.shipping'),
        'payment' => route('shop.checkout.payment'),
        'finalize' => route('shop.checkout.finalize'),
    ];
    $links = array_merge($defaultLinks, is_array($links) ? $links : []);
@endphp

<nav aria-label="Checkout steps" class="mb-6">
    <ol class="grid grid-cols-4 gap-2">
        @foreach($steps as $number => $step)
            @php
                $isCurrent = $number === (int) $current;
                $isCompleted = $number < (int) $current;
                $baseClasses = 'flex items-center gap-3 p-3 border rounded transition';
                $stateClasses = $isCurrent
                    ? 'border-emerald-600 bg-emerald-50 text-emerald-800'
                    : ($isCompleted ? 'border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50' : 'border-gray-200 bg-white text-gray-500');
                $circleBase = 'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold';
                $circleClasses = $isCurrent
                    ? 'bg-emerald-600 text-white'
                    : ($isCompleted ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500');
            @endphp
            <li class="min-w-0">
                @if($isCompleted)
                    <a href="{{ $links[$step['key']] ?? '#' }}" class="block {{ $baseClasses }} {{ $stateClasses }}">
                        <span class="{{ $circleBase }} {{ $circleClasses }}">{{ $number }}</span>
                        <span class="truncate">{{ $step['label'] }}</span>
                    </a>
                @else
                    <div class="{{ $baseClasses }} {{ $stateClasses }}">
                        <span class="{{ $circleBase }} {{ $circleClasses }}">{{ $number }}</span>
                        <span class="truncate">{{ $step['label'] }}</span>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
    <div class="mt-2 text-xs text-gray-500">Lépések: Kosár → Szállítás → Fizetés → Megrendelés</div>
  </nav>
