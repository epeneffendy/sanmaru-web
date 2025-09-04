<form action="{{ route('kantin.cart.add') }}" method="POST" id="{{ $product->schedule->type == 'ready' ? 'addToCartForm' : 'preorderForm'}}">
    @csrf
    @method('POST')
    <input type="hidden" name="id" value="{{ $product->id }}">
    <input type="hidden" name="type" value="{{ $product->schedule->type }}">
    <input type="hidden" name="note" value="">
    <div class="row">
        <div class="col-6">
            <div class="image-wrapper">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100%">
            </div>
        </div>
        <div class="col-6">
            <div class="cart-detail-wrapper">
                <div>
                    <h5 class="display-xs bold black">{{ $product->name }}</h5>
                    <h6 class="text-lg medium secondary-green price">{{ $product->price_siswa_range }}</h6>
                </div>
                <div>
                    @if ($product->details)
                        @forelse ($product->details as $detail)
                        <div class="radio">
                            <label>
                                @if ($detail->stock > 0)
                                <input type="radio" name="detail_id" value="{{ $detail->id }}" required>
                                {{ $detail->size }}
                                @else
                                <input type="radio" name="detail_id" value="{{ $detail->id }}" disabled>
                                <s>{{ $detail->size }}</s> <h6 class="soldOut">Sold Out</h6>
                                @endif
                            </label>
                        </div>
                        @empty
                            Tidak ada ukuran
                        @endforelse
                    @endif
                </div>
                <div class="input-number mt-2">
                    <span class="minus"><i class="icon icon-minus"></i></span>
                    <input type="text" class="text-md bold input-counter"  name="qty" value="1" required>
                    <span class="plus"><i class="icon icon-plus"></i></span>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    .soldOut {
        margin-left: 0.5em;
        color: red;
        float: right;
        text-align: right;
        align-content: center;
    }
</style>
