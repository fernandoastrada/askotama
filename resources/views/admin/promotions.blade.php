@extends('layouts.admin')
@section('content')
<style>
    .item.delete {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    color: inherit;
}

</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Slide</h3>
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
                    <div class="text-tiny">Promotion Page</div>
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
                <a class="tf-button style-1 w208" href="{{route('admin.promotion.add')}}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            
            <div class="table-responsive" >
                @if(Session::has('success'))
                    <p class="alert alert-success">{{Session::get('success')}}</p>
                        @endif
                <table class="table table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Tagline</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promotions as $promotion)
                        <tr>
                            <td>{{$promotion->id}}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{asset('uploads/promotions')}}/{{$promotion->image}}" alt="" class="{{$promotion->title}}">
                                </div>
                            </td>
                            <td>{{$promotion->tagline}}</td>
                            <td>{{$promotion->title}}</td>
                            <td>{{$promotion->subtitle}}</td>
                            <td>{{$promotion->deskripsi}}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{route('admin.promotion.edit',['id'=>$promotion->id])}}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.promotion.delete', ['id' => $promotion->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$promotions->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        console.log("Delete button clicked"); // Debug
        var form = $(this).closest('form');
        swal({
            title: "Are you sure?",
            text: "You want to delete this data?",
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        }).then(function(result) {
            if (result) {
                console.log("Form submitted"); // Debug
                form.submit();
            }
        });
    });
</script>
@endpush

