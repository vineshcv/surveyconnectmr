@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <h5>List of Products</h5>
        @can('create-product')
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-rounded btnFixed">
            <span>Create new product</span> <i class="fa-solid fa-plus"></i>
        </a>
        @endcan
        <br>

        <div class="table-responsive">
            <table id="productsTable" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-icon">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @can('edit-product')
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success btn-icon">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @endcan
                                @can('delete-product')
                                <form action="{{ route('products.destroy', $product->id) }}" method="post" class="d-inline" onsubmit="return confirm('Do you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            <span class="text-danger">
                                <strong>No Product Found!</strong>
                            </span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable for products table
    new DataTable('#productsTable');
});
</script>
@endsection