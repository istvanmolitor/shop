<nav aria-label="Checkout steps" class="mb-6">
    <ol class="grid grid-cols-4 gap-2">
        @foreach($steps as $number => $step)
            <li class="min-w-0">
                @if($isCompleted($number))
                    <a href="{{ $links[$step['key']] ?? '#' }}" class="block {{ $getStepClasses($number) }}">
                        <span class="{{ $getCircleClasses($number) }}">{{ $number }}</span>
                        <span class="truncate">{{ $step['label'] }}</span>
                    </a>
                @else
                    <div class="{{ $getStepClasses($number) }}">
                        <span class="{{ $getCircleClasses($number) }}">{{ $number }}</span>
                        <span class="truncate">{{ $step['label'] }}</span>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
    <div class="mt-2 text-xs text-gray-500">{{ __('shop::common.checkout.steps.legend') }}</div>
</nav>
