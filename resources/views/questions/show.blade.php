@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>View Question</h3>
            <a href="{{ route('questions.index') }}" class="btn btn-primary btn-rounded">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Question:</label>
                    <div class="col-md-8">
                        {{ $question->question ?? '-' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Type:</label>
                    <div class="col-md-8">
                        {{ ucfirst($question->type ?? '-') }}
                    </div>
                </div>

                @if(in_array($question->type, ['checkbox', 'multiselect']) && is_array($question->options ?? null))
                    <div class="mb-3 row">
                        <label class="col-md-4 fw-bold">Options:</label>
                        <div class="col-md-8">
                            <ul class="mb-0 ps-3">
                                @foreach($question->options as $option)
                                    <li>{{ $option }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if(($question->type ?? null) === 'object' && is_array($question->sub_questions ?? null))
                    <div class="mb-3 row">
                        <label class="col-md-4 fw-bold">Sub Questions:</label>
                        <div class="col-md-8">
                            <ol class="mb-0 ps-3">
                                @foreach($question->sub_questions as $sub)
                                    <li>{{ $sub }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
