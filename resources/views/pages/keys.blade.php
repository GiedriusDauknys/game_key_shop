@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="height: 50px;"></div>
        <h2>Your Game Keys</h2>
        <div style="height: 20px;"></div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('refund_info'))
            <div class="alert alert-info">
                <h5>💸 Refund Summary</h5>
                <ul>
                    <li><strong>Game Refunded:</strong> {{ session('refund_info.game') }}</li>
                    <li><strong>Original Price:</strong> €{{ session('refund_info.original_price') }}</li>
                    <li><strong>Processing Fee Deducted:</strong> €{{ session('refund_info.refund_fee') }}</li>
                    <li><strong>Total Refunded:</strong> €{{ session('refund_info.refunded_amount') }}</li>
                    <li><strong>Refund ID:</strong> {{ session('refund_info.refund_id') }}</li>
                    <li>The refund was processed back to the original card used during checkout.</li>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse ($keys->groupBy('game_id') as $gameId => $gameKeys)
            @foreach ($gameKeys as $index => $key)
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">
                                {{ $key->game->title }}
                                @if ($gameKeys->count() > 1)
                                    <small class="text-muted">(Copy {{ $index + 1 }})</small>
                                @endif
                            </h5>

                            <div id="key-container-{{ $key->id }}" style="display: none;" class="mt-2">
                                <p><strong>Key:</strong> <code>{{ $key->key }}</code></p>
                            </div>

                            <button class="btn btn-outline-primary btn-sm mt-2"
                                    onclick="toggleKey({{ $key->id }})"
                                    id="toggle-btn-{{ $key->id }}">
                                🔓 Show Key
                            </button>

                            @if (!$key->viewed_at)
                                <div class="mt-2 text-muted small" id="refund-label-{{ $key->id }}">
                                    <i>Eligible for refund (not yet viewed)</i>
                                </div>
                            @endif
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex">
                            @if (!$key->viewed_at)
                                <!-- Refund (only if not viewed) -->
                                <form id="refund-form-{{ $key->id }}"
                                       action="{{ route('user.keys.refund', $key->id) }}"
                                       method="POST"
                                       onsubmit="return confirm('Are you sure you want to refund this game?\n\nYou will receive 95% of the price.\n5% will be kept as a refund processing fee.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        💸 Refund
                                    </button>
                                </form>
                            @else
                                <!-- Delete (only if viewed) -->
                                <form action="{{ route('user.keys.delete', $key->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this key?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        🗑️ Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @empty
            <p>You have not purchased any games yet.</p>
        @endforelse
    </div>

    <script>
        function toggleKey(id) {
            const container = document.getElementById(`key-container-${id}`);
            const btn = document.getElementById(`toggle-btn-${id}`);

            if (container.style.display === 'none') {
                container.style.display = 'block';
                btn.innerText = 'Hide Key';

                // Mark as viewed
                fetch(`/keys/view/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    const refundForm = document.querySelector(`#refund-form-${id}`);
                    if (refundForm) refundForm.remove();

                    const refundLabel = document.querySelector(`#refund-label-${id}`);
                    if (refundLabel) refundLabel.remove();

                    const actionContainer = document.querySelector(`#toggle-btn-${id}`).closest('.card-body').querySelector('.d-flex');


                    if (!actionContainer.querySelector(`form[action="/keys/${id}"]`)) {
                        const deleteForm = document.createElement('form');
                        deleteForm.action = `/keys/${id}`;
                        deleteForm.method = 'POST';
                        deleteForm.onsubmit = () => confirm('Are you sure you want to delete this key?');
                        deleteForm.classList.add('ms-2');

                        deleteForm.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-sm btn-danger">
            🗑️ Delete
        </button>
    `;

                        actionContainer.appendChild(deleteForm);
                    }
                });

            } else {
                container.style.display = 'none';
                btn.innerText = '🔓 Show Key';
            }
        }
    </script>
@endsection
