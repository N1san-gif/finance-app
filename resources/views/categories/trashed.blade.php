@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Category Trash</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($categories->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->type }}</td>
                    <td>
                        <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-success">Restore</button>
                        </form>
                        <form action="{{ route('categories.forceDelete', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete permanently?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $categories->links() }}
    @else
        <p>The trash is empty.</p>
    @endif

    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back to Categories</a>
</div>
@endsection
