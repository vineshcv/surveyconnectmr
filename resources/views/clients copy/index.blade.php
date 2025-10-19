@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Vendors</span>
                <a href="{{ route('vendors.create') }}" class="btn btn-primary btn-sm">+ Add New Vendor</a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vendor Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Vendor ID</th>
                            <th class="text-center" width="180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $index => $vendor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $vendor->vendor_name }}</td>
                                <td>{{ $vendor->email ?? '—' }}</td>
                                <td>{{ $vendor->contact_number }}</td>
                                <td>{{ $vendor->vendor_id }}</td>
                                <td class="text-center">
                                    <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this vendor?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No vendors found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
