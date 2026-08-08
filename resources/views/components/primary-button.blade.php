<button {{ $attributes->merge(['type' => 'submit', 'class' => 'main-btn main-dark']) }}>
    {{ $slot }}
</button>
