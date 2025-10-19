@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Add New Question</span>
        <a href="{{ route('questions.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
      </div>

      <div class="card-body">
        <form id="question-form" method="POST" action="{{ route('questions.store') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label for="question" class="form-label">Question</label>
            <input type="text" name="question" id="question"
                   class="form-control @error('question') is-invalid @enderror"
                   value="{{ old('question') }}" required>
            @error('question')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type"
                    class="form-select @error('type') is-invalid @enderror" required>
              <option value="">-- Select Type --</option>
              <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
              <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
              <option value="multiselect" {{ old('type') == 'multiselect' ? 'selected' : '' }}>Multi-Select</option>
              <option value="object" {{ old('type') == 'object' ? 'selected' : '' }}>Object-Oriented (4 Sub-Questions)</option>
            </select>
            @error('type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div id="option-fields" style="display:none;">
            <label>Options <small>(for checkbox or multi-select)</small></label>
            <div id="options-container">
              @php $oldOptions = old('options', ['']); @endphp
              @foreach ($oldOptions as $opt)
                <div class="option-wrapper d-flex mb-2">
                  <input type="text" name="options[]" class="form-control option-input" value="{{ $opt }}" />
                  <button type="button" class="btn btn-outline-danger btn-remove-option ms-2" title="Remove option">&times;</button>
                </div>
              @endforeach
            </div>
            <button type="button" id="add-option" class="btn btn-sm btn-secondary mb-3">+ Add Option</button>
            @error('options')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div id="sub-question-fields" style="display:none;">
            <label>Sub Questions <small>(exactly 4)</small></label>
            @php $oldSubs = old('sub_questions', ['', '', '', '']); @endphp
            @for ($i = 0; $i < 4; $i++)
              <input type="text" name="sub_questions[]" class="form-control mb-2 sub-question" value="{{ $oldSubs[$i] ?? '' }}">
            @endfor
            @error('sub_questions')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <input type="submit" class="btn btn-primary" value="Add Question">
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
<style>
  /* Fix spacing for invalid feedback outside the flex container */
  .option-wrapper + .invalid-feedback {
    margin-left: 0;
    margin-top: 0.25rem;
  }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {
  function toggleFields(type) {
    if (['checkbox', 'multiselect'].includes(type)) {
      $('#option-fields').show();
      $('#sub-question-fields').hide();
    } else if (type === 'object') {
      $('#option-fields').hide();
      $('#sub-question-fields').show();
    } else {
      $('#option-fields').hide();
      $('#sub-question-fields').hide();
    }
  }

  function updateAddOptionButtonState() {
    const count = $('#options-container input.option-input').length;
    $('#add-option').prop('disabled', count >= 4);
  }

  // Add new option input group
  $('#add-option').click(function() {
    const count = $('#options-container input.option-input').length;
    if (count < 4) {
      $('#options-container').append(
        `<div class="option-wrapper d-flex mb-2">
          <input type="text" name="options[]" class="form-control option-input" />
          <button type="button" class="btn btn-outline-danger btn-remove-option ms-2" title="Remove option">&times;</button>
        </div>`
      );
      updateAddOptionButtonState();
    } else {
      alert('Maximum 4 options allowed.');
    }
  });

  // Remove option input group on click (event delegation)
  $('#options-container').on('click', '.btn-remove-option', function() {
    $(this).closest('.option-wrapper').remove();
    updateAddOptionButtonState();
  });

  $('#type').change(function() {
    toggleFields(this.value);
    $('#question-form').validate().resetForm();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    updateAddOptionButtonState();
  });

  toggleFields($('#type').val());
  updateAddOptionButtonState();

  $('#question-form').validate({
    rules: {
      question: { required: true, minlength: 3 },
      type: { required: true }
    },
    messages: {
      question: {
        required: "The question field is required.",
        minlength: "Question must be at least 3 characters."
      },
      type: { required: "The type field is required." }
    },
    highlight: function(element) {
      $(element).addClass('is-invalid').removeClass('is-valid');
      $(element).next('.valid-feedback').remove();
    },
    unhighlight: function(element) {
      $(element).removeClass('is-invalid').removeClass('is-valid');
      $(element).next('.invalid-feedback').remove();
    },
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    errorPlacement: function(error, element) {
      if (element.hasClass('option-input')) {
        // Place error message after the entire option-wrapper div, not just input
        element.closest('.option-wrapper').after(error);
      } else {
        error.insertAfter(element);
      }
    },
    submitHandler: function(form) {
      const type = $('#type').val();
      let valid = true;

      $('input.option-input, input.sub-question').removeClass('is-invalid');
      $('.invalid-feedback').remove();

      if (type === 'checkbox' || type === 'multiselect') {
        const options = $('input.option-input');
        if (options.length < 1) {
          alert('Please add at least 1 option.');
          valid = false;
        } else if (options.length > 4) {
          alert('Maximum 4 options allowed.');
          valid = false;
        } else {
          options.each(function() {
            if ($.trim($(this).val()) === '') {
              $(this).addClass('is-invalid');
              if ($(this).closest('.option-wrapper').next('.invalid-feedback').length === 0) {
                $(this).closest('.option-wrapper').after('<div class="invalid-feedback">This option is required.</div>');
              }
              valid = false;
            }
          });
        }
      } else if (type === 'object') {
        const subQuestions = $('input.sub-question');
        if (subQuestions.length !== 4) {
          alert('Exactly 4 sub-questions are required.');
          valid = false;
        } else {
          subQuestions.each(function() {
            if ($.trim($(this).val()) === '') {
              $(this).addClass('is-invalid');
              if ($(this).next('.invalid-feedback').length === 0) {
                $(this).after('<div class="invalid-feedback">This sub-question is required.</div>');
              }
              valid = false;
            }
          });
        }
      }

      if (valid) {
        form.submit();
      }
    }
  });
});
</script>
@endsection
