@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-end">
        <div class="col-md-12">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="h5">{{ __('Url list') }}</span>
                    <!-- add url Button -->
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add url
                    </button>
                </div>

                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">S.No.</th>
                                <th scope="col">Original url</th>
                                <th scope="col">Short url</th>
                                <th scope="col">Status</th>
                                <th scope="col">Expiry Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $serial = 1;
                            @endphp
                            @foreach($urls as $url)
                            <tr>
                                <th scope="row">{{ $serial++ }}</th>
                                <td>{{ $url->original_url }}</td>
                                <td>
                                    <a href="{{ url($url->short_code) }}" target="_blank">{{ url($url->short_code) }}</a>
                                </td>
                                <td>
                                    @if( $url->is_active == 0)
                                    <span class="badge bg-danger">Disable</span>
                                    @else
                                    <span class="badge bg-success">Active</span>

                                    @endif
                                </td>
                                <td>{{ $url->expires_at ? $url->expires_at : 'Never' }}</td>
                                <td class="d-flex gap-2">
                                    <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editmodal{{ $url->id }}" style="display:inline;">
                                        Edit
                                    </button></li>
                                        <li> <form class="dropdown-item" action="{{ route('url.disable',[$url->id]) }}" method="post" >
                                        @csrf
                                        <button class="dropdown-item" type="submit">Disable</button>
                                    </form></li>
                                        <li><form class="dropdown-item" action="{{ route('url.delete',[$url->id]) }}" method="post" >
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item" type="submit">Delete</button>
                                    </form></li>
                                    </ul>
                                    </div>

                                    


                                    <!--Edit Modal -->
                                    <div class="modal fade" id="editmodal{{ $url->id }}" tabindex="-1" aria-labelledby="editmodalLabel{{ $url->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Edit URL</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('url.edit',[$url->id])}}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">

                                                        <input type="url" class="form-control" name="original_url" value="{{ $url->original_url }}" placeholder="Enter Url" required>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                   


                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add URL</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('store.url')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <input type="url" class="form-control" name="url" placeholder="Enter Url" required>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection