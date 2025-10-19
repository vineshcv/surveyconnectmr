@if (session('registered'))
    <script>
        window.location.href = "{{ route('vendor-registration.thankyou') }}";
    </script>
@endif
@extends('layouts.register')

@section('content')
<div class="row jumbotron box8">
<form method="POST" action="{{ route('vendor-registration.store') }}">
    @csrf
    <div class="row">
    <div class="col-sm-6 form-group">
        <label for="name-f">Name *</label>
        <input type="text" class="form-control" name="vendor_name" id="name-f" required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="name-l">E-mail Id</label>
        <input type="text" class="form-control" name="email" id="name-l" required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="email">Mobile Number</label>
        <input type="text" class="form-control" name="contact_number" id="email"  required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="address-1">Alternate Contact</label>
        <input type="address" class="form-control" name="alternative_contact" id="address-1" required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="address-2">Company Name *</label>
        <input type="address" class="form-control" name="company_name" id="address-2"required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="State">Address Line One</label>
        <input type="address" class="form-control" name="address_line_one" id="State" 
            required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="zip">Address Line Two</label>
        <input type="zip" class="form-control" name="address_line_one" id="zip" required>
    </div>
   
    <div class="col-sm-6 form-group">
        <label for="zip">State</label>
        <input type="zip" class="form-control" name="state" id="zip" required>
    </div>
    <div class="col-sm-6 form-group">
        <label for="Country">Country</label>
        <select name="country" class="form-control custom-select browser-default">
            <option value="Afghanistan">Afghanistan</option>
            <option value="Åland Islands">Åland Islands</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="American Samoa">American Samoa</option>
            <option value="Andorra">Andorra</option>
        </select>
    </div>
    <div class="col-sm-6 form-group">
        <label for="zip">Pincode</label>
        <input type="zip" class="form-control" name="pincode" id="zip" required>
    </div>

    <button style="margin:auto; width:20%; margin-top: 25px;" type="submit" class="btn btn-primary btn-rounded">Save</button>
</div>
</form>
</div>

@endsection