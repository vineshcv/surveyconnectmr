@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">

        <!-- <form method="GET" action="{{ route('questions.index') }}" class="row mb-3 g-2">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search question or type...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form> -->

        <!-- Floating Add Question Button -->
        <h5>List of Questions</h5>
        @can('create-question')
        <a href="{{ route('questions.create') }}" class="btn btn-primary btn-rounded btnFixed">
            <span>Add Question</span> <i class="fa-solid fa-plus"></i>
        </a>
        @endcan
        <br>

        <!-- Questions Table -->
        <div class="table-responsive">
            <table id="questionsTable" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Question</td>
                        <td>Type</td>
                        <td>Options</td>
                        <td>Sub Questions</td>
                        <td>Status</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $index => $question)
                        <tr>
                            <td>{{ $questions->firstItem() + $index }}</td>
                            <td>{{ $question->question }}</td>
                            <td>{{ ucfirst($question->type) }}</td>
                            <td>
                                @if(is_array($question->options) && count($question->options))
                                    <ul class="mb-0 ps-3">
                                        @foreach($question->options as $opt)
                                            <li>{{ $opt }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>—</em>
                                @endif
                            </td>
                            <td>
                                @if(is_array($question->sub_questions) && count($question->sub_questions))
                                    <ol class="mb-0 ps-3">
                                        @foreach($question->sub_questions as $sub)
                                            <li>{{ $sub }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    <em>—</em>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $question->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $question->status ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('questions.show', $question->id) }}" class="btn btn-info btn-icon btn-show" aria-label="View Question">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-warning btn-icon btn-edit" aria-label="Edit Question">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this question?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-icon" type="submit" aria-label="Delete Question">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td ></td>
                             <td ></td>
                              <td ></td>
                               <td ></td>
                                <td ></td>
                                 <td ></td>
                                  <td ></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $questions->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable for questions table
    new DataTable('#questionsTable');
});
</script>
@endsection
