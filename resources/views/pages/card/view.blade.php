@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="height: 20px;"></div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div style="height: 20px;"></div>
        <h2>Your Shopping Basket</h2>
        <div style="height: 30px;"></div>
        @if (!$order || $order->items->isEmpty())
            <p>Your basket is currently empty.</p>
        @else
            <div class="list-group mb-4">
                @foreach ($order->items as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $item->game->title }}</h5>
                            <small>Price per item: €{{ number_format($item->price, 2) }}</small>
                        </div>

                        <div class="d-flex align-items-center">
                            <input type="number"
                                   class="form-control form-control-sm me-2 quantity-input"
                                   data-item-id="{{ $item->id }}"
                                   value="{{ $item->quantity }}"
                                   min="1"
                                   style="width: 70px;">

                            <form action="{{ route('cart.remove', $item->game->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('cart.clear') }}" method="POST" class="mb-3">
                @csrf
                <button type="submit" class="btn btn-outline-warning">🗑️ Clear Basket</button>
            </form>

            <div class="mb-3">
                <strong>Total: <span id="total-price">€{{ number_format($order->total_price, 2) }}</span></strong>
            </div>

            <form method="POST" action="{{ route('cart.checkout') }}">
                @csrf
                <button type="submit" class="btn btn-success">💳 Checkout with Stripe</button>
            </form>
            <div style="height: 50px;"></div>
        @endif
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInputs = document.querySelectorAll('.quantity-input');

            quantityInputs.forEach(input => {
                input.addEventListener('change', function () {
                    const itemId = this.getAttribute('data-item-id');
                    const newQuantity = this.value;

                    fetch(`/cart/update/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ quantity: newQuantity })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {

                                document.getElementById('total-price').innerText = `€${parseFloat(data.new_total).toFixed(2)}`;
                            } else {
                                alert('Failed to update quantity.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
