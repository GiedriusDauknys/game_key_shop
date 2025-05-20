<div class="d-flex align-items-center gap-2">
    <strong class="me-2" style="font-size: 1.4rem;">Average Rating:</strong>
    <span style="font-size: 2rem; color: #000;">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= floor($averageRating))
                ★
            @else
                ☆
            @endif
        @endfor
    </span>
    <span class="text-muted ms-2" style="font-weight: bold;">
        ({{ number_format($averageRating, 1) }}/5)
    </span>
</div>
