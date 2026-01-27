@props(['size' => 48, 'class' => '', 'showText' => false])

<div class="flex items-center gap-3 {{ $class }}">
    <!-- Logo Image -->
    <div class="relative flex-shrink-0">
        <img 
            src="{{ asset('storage/logo.png?v=' . time()) }}" 
            alt="Job Seeker Hub Logo" 
            class="object-contain"
            style="width: {{ $size }}px; height: auto; max-height: {{ $size }}px;"
        />
    </div>
    
    @if($showText)
    <!-- Logo Text (Optional) -->
    <div class="flex flex-col leading-tight">
        <span class="font-bold" style="font-size: {{ $size * 0.5 }}px; color: #f97316; line-height: 1.1;">
            Job Seeker
        </span>
        <span class="font-bold" style="font-size: {{ $size * 0.5 }}px; color: #3b82f6; line-height: 1.1;">
            Hub
        </span>
    </div>
    @endif
</div>
