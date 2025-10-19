@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Product
                </div>
                <div class="float-end">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

            <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
            @csrf

                <input name="name" placeholder="Project Name" required>

                <select name="project_type" id="project_type" required>
                    <option value="single">Single</option>
                    <option value="multiple">Multiple</option>
                    <option value="unique">Unique</option>
                </select>

                <select name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                    @endforeach
                </select>

                <select name="login_type_id">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
        
                <select name="countries[]" id="country_select" multiple>
                    <option value="1">India</option>
                    <option value="2">United States</option>
                    <option value="3">Germany</option>
                    <option value="4">Japan</option>
                    <option value="5">Australia</option>
                </select>


                <textarea name="specifications" placeholder="Project Specifications"></textarea>
                <input name="quota" type="number">
                <input name="loi">
                <input name="ir">

                <select name="status" required>
                    @foreach(['live', 'pause', 'invoice', 'ir', 'commission', 'cancelled'] as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <input name="live_url" placeholder="Live URL (optional)">
                <input name="test_url" placeholder="Test URL (optional)">

                <label>
                    <input type="checkbox" name="enable_questions" value="1" onchange="toggleQuestions()">
                    Enable Questions
                </label>

                <div id="question_select" style="display:none;">
                    <select id="questions" name="questions[]" multiple>
                        @foreach($questions as $question)
                            <option value="{{ $question->id }}">{{ $question->question }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="csv_upload" style="display:none;">
                    <input type="file" name="csv_file" accept=".csv">
                </div>

                <button type="submit">Create</button>
            </form>

            


            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('project_type').addEventListener('change', function () {
        const type = this.value;
        const countrySelect = document.getElementById('country_select');
        const csvUpload = document.getElementById('csv_upload');

        countrySelect.multiple = (type !== 'single');
        csvUpload.style.display = (type === 'unique') ? 'block' : 'none';
    });

    function toggleQuestions() {
        document.getElementById('question_select').style.display =
            document.querySelector('[name="enable_questions"]').checked ? 'block' : 'none';
    }
</script>

<script>
    $(document).ready(function() {
        $('#country_select').select2({
            placeholder: "Select permissions",
            allowClear: true,
            width: '100%'
        });
    });
    $(document).ready(function() {
        $('#questions').select2({
            placeholder: "Select permissions",
            allowClear: true,
            width: '100%'
        });
    });
    
</script>
@endpush
@endsection