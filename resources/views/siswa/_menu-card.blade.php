<div class="card h-100" style="border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s;" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->nama) }}')">
    <div style="height:120px;background:linear-gradient(135deg,#FFF0EB,#FFD4C2);position:relative;overflow:hidden;">
        @if($menu->foto)
            <img src="{{ asset('storage/'.$menu->foto) }}" style="width:100%;height:100%;object-fit:cover;transition:.2s;" alt="{{ $menu->nama }}">
        @else
            <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;">
                {{ ['🍱','🍜','🍔','🥤','🍛','🍕','🥗','🧋','🍝','🍲'][$menu->id % 10] }}
            </div>
        @endif
        @if($menu->terjual > 20)
        <span style="position:absolute;top:.4rem;left:.4rem;background:#FF6B35;color:#fff;font-size:.62rem;font-weight:800;padding:1px 6px;border-radius:6px;">🔥 Populer</span>
        @endif
    </div>
    <div class="p-2">
        <div class="fw-700 text-truncate mb-1" style="font-size:.82rem;">{{ $menu->nama }}</div>
        @if(isset($menu->penjual))
        <div class="text-muted text-truncate mb-1" style="font-size:.72rem;"><i class="bi bi-shop" style="font-size:.65rem;"></i> {{ $menu->penjual->penjualProfile?->nama_warung ?? $menu->penjual->name }}</div>
        @endif
        <div class="d-flex align-items-center justify-content-between">
            <span class="fw-800" style="color:var(--primary);font-size:.85rem;">{{ $menu->harga_format }}</span>
            <button id="add-btn-{{ $menu->id }}"
                    class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                    style="width:26px;height:26px;padding:0;border-radius:8px;font-size:.75rem;"
                    onclick="event.stopPropagation(); addToCart({{ $menu->id }}, '{{ addslashes($menu->nama) }}')"
                    title="Tambah ke keranjang">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
        @if($menu->stok <= 5 && $menu->stok > 0)
        <div style="font-size:.7rem;color:#E53E3E;font-weight:600;">Sisa {{ $menu->stok }}!</div>
        @elseif($menu->stok == 0)
        <div style="font-size:.7rem;color:#A0AEC0;font-weight:600;">Habis</div>
        @endif
    </div>
</div>
