@props(['active'])

@php
$classes = ($active ?? false)
? 'inline-flex items-center px-1 pt-1 text-sm font-medium text-white'
: 'inline-flex items-center px-1 pt-1 text-sm font-medium text-white hover:text-gray-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>