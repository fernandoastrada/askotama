@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Clients</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Clients</div></li>
            </ul>
        </div>

        <!-- Box -->
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <!-- Search -->
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.clients') }}">
                        <fieldset class="name">
                            <input type="text" 
                                   placeholder="Search here..." 
                                   name="name"
                                   class=""
                                   tabindex="2"
                                   value="{{ request('name') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit">
                                <i class="icon-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Add new -->
                <a class="tf-button style-1 w208" href="{{ route('admin.clients.add') }}">
                    <i class="icon-plus"></i> Add New
                </a>
            </div>

            <!-- Table -->
            <div class="wg-table table-all-user mt-4" style="overflow-x:auto;">
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif

                    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">No</th>
                <th>Name</th>
                <th style="width: 120px; text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
                <tr>
                    <td class="text-center">
                        {{ ($clients->firstItem() ?? 0) + $loop->index }}
                    </td>
                    <td>{{ $client->name }}</td>
                    <td class="text-center">
                        <form action="{{ route('admin.client.delete', $client->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure want to delete this client?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="icon-trash-2"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No clients found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
                </div>

                <!-- Pagination -->
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $clients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
