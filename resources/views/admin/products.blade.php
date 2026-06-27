@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Products</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All Products</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.product.add')}}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="display:none;">#</th>
            <th style="width:100%">Name</th>
            <th>Price</th>
            <th>SalePrice</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Featured</th>
            <th>Stock</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td style="display:none;">{{ $product->id }}</td>
                <td class="pname" style="width:100%">
    <div class="image">
        <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
             alt="{{ $product->name }}" 
             class="thumbnail-img">
    </div>
    <div class="name">
        <a href="#" class="body-title-2" title="{{ $product->name }}">
            {{ $product->name }}
        </a>
        <div class="text-tiny mt-3">{{ $product->slug }}</div>
    </div>
</td>

                <td>Rp {{ number_format($product->regular_price ?? 0, 0, ',', '.') }}</td>
                <td>{{ $product->sale_price ?? 0 }}</td>
                <td>{{ $product->SKU ?? '-' }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ $product->brand->name ?? '-' }}</td>
                <td>{{ ($product->featured ?? 0) == 0 ? "No" : "Yes" }}</td>
                <td>{{ $product->stock_status ?? '-' }}</td>
                <td>{{ $product->quantity ?? 0 }}</td>
                <td>
                                <div class="list-icon-function">
                                    <a href="javascript:void(0)" class="btn-view" data-url="{{ route('admin.product.show', $product->id) }}">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                    <a href="{{route('admin.product.edit',['id'=>$product->id])}}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.product.delete', ['id' => $product->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center text-muted">Tidak ada produk tersedia</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- pagination --}}
<div class="d-flex justify-content-center mt-3">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

<!-- Modal Bootstrap 5 -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="modal-name">Product Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-5 text-center" id="modal-image">
            <!-- gambar produk akan diisi via AJAX -->
          </div>
          <div class="col-md-7">
            <p><strong>Price:</strong> <span id="modal-price"></span></p>
            <p><strong>Category:</strong> <span id="modal-category"></span></p>
            <p><strong>Description:</strong> <span id="modal-desc"></span></p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>




@endsection

@push('scripts')
<script>
//     if (product.image) {
//     $('#modal-image').html(
//         '<img src="'+ baseImgPath + '/' + product.image +'" alt="'+ (product.name || '') +'" class="img-fluid">'
//     );
// } else {
//     $('#modal-image').html('<img src="/images/default-product.png" alt="No image" class="img-fluid">');
// }
$(document).ready(function() {
    // VIEW -> ambil data via AJAX dan tampilkan modal (Bootstrap 5)
    $(document).on('click', '.btn-view', function(e) {
        e.preventDefault();

        var url = $(this).data('url');
        if (!url) return console.error('URL not found on .btn-view');

        // optional: tampilkan loading / disable tombol
        var $btn = $(this);
        $btn.prop('disabled', true);

        $.get(url)
            .done(function(product) {
                // safety checks
                var name = product.name || '-';
                var formattedName = name.replace(/-/g, ' ').toUpperCase();
                var price = product.regular_price ?? '-';
                var category = (product.category && product.category.name) ? product.category.name : '-';
                var brand = (product.brand && product.brand.name) ? product.brand.name : '-';
                var desc = product.short_description || '-';
                // isi modal
                $('#modal-name').text(formattedName);
                $('#modal-price').text(new Intl.NumberFormat('id-ID',{style:'currency', currency: 'IDR'}).format(price));
                $('#modal-category').text(category + (brand ? (' — '+brand) : ''));
                $('#modal-desc').text(desc);

                // image: gunakan base path dari Blade asset()
                var baseImgPath = "{{ asset('uploads/products/thumbnails') }}";
                if (product.image) {
                    $('#modal-image').html('<img src="'+ baseImgPath + '/' + product.image +'" alt="'+ (product.name || '') +'" class="img-fluid">');
                } else {
                    $('#modal-image').html('');
                }

                // show modal (Bootstrap 5)
                var modalEl = document.getElementById('productModal');
                if (modalEl) {
                    var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    bsModal.show();
                } else {
                    console.error('productModal element not found');
                }
            })
            .fail(function(xhr) {
                // handle error
                var msg = 'Failed to load product data.';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            })
            .always(function() {
                $btn.prop('disabled', false);
            });
    });

    // DELETE -> sweetalert confirm (kamu sudah punya, saya rapikan sedikit)
    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');

        swal({
            title: "Are you sure?",
            text: "You want to delete this data?",
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        }).then(function(result) {
            if (result) {
                form.submit();
            }
        });
    });
});


</script>
@endpush
