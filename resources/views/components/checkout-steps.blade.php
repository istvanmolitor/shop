<nav aria-label="Checkout steps" class="mb-6">
    <ol class="grid grid-cols-5 gap-2">
        @foreach($getSteps() as $stepName => $step)
            <li class="min-w-0">
                @if($step['is_completed'])
                    <a href="{{ $step['link'] }}" class="block {{ $getStepClasses($step['number']) }}">
                        <span class="{{ $getCircleClasses($stepName) }}">{{ $step['number'] }}</span>
                        <span class="truncate">{{ $step['label'] }}</span>
                    </a>
                @else
                    <div class="{{ $getStepClasses($stepName) }}">
                        <span class="{{ $getCircleClasses($stepName) }}">{{ $step['number'] }}</span>
                        <span class="truncate">{{ $step['label'] }}</span>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
    <div class="mt-2 text-xs text-gray-500">{{ __('shop::common.checkout.steps.legend') }}</div>
</nav>
