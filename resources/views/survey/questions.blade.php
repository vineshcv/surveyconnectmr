@extends('layouts.survey')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4><i class="fa fa-question-circle"></i> Survey Questions</h4>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Please answer the following questions before proceeding to the survey:</h5>
                </div>

                <form method="POST" action="{{ route('survey.submitQuestions', $participant->participant_id) }}">
                    @csrf
                    
                    @foreach($project->questions as $index => $question)
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                {{ $index + 1 }}. {{ $question->question }}
                            </label>
                            
                            @switch($question->type)
                                @case('text')
                                    <input type="text" name="answers[{{ $question->id }}]" class="form-control" required>
                                    @break
                                
                                @case('radio')
                                    @if(is_array($question->options))
                                        @foreach($question->options as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" id="q{{ $question->id }}_{{ $loop->index }}" required>
                                                <label class="form-check-label" for="q{{ $question->id }}_{{ $loop->index }}">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                    @break
                                
                                @case('checkbox')
                                    @if(is_array($question->options))
                                        @foreach($question->options as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}" id="q{{ $question->id }}_{{ $loop->index }}">
                                                <label class="form-check-label" for="q{{ $question->id }}_{{ $loop->index }}">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                    @break
                                
                                @case('select')
                                    <select name="answers[{{ $question->id }}]" class="form-select" required>
                                        <option value="">Please select...</option>
                                        @if(is_array($question->options))
                                            @foreach($question->options as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break
                                
                                @default
                                    <textarea name="answers[{{ $question->id }}]" class="form-control" rows="3" required></textarea>
                            @endswitch
                        </div>
                    @endforeach

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-arrow-right"></i> Continue to Survey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
