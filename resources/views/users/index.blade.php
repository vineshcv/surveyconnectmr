@extends('layouts.app')
<style>
    #createRolesContainer .form-check-label{
        width: 200px;;
    }
    #editRolesContainer .form-check-label{
        width: 200px;;
    }
</style>
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <!-- Create User Button -->
        <h5>List of Users</h5>
        @can('create-user')
        <button type="button" class="btn btn-primary btn-rounded btnFixed" data-bs-toggle="modal" data-bs-target="#createModal">
            <span>Create new user</span> <i class="fa-solid fa-plus"></i>
        </button>
        @endcan
        <br>

        <div class="table-responsive">
            <table id="example" class="table">
                <thead>
                    <tr>
                        <td>SID</td>
                        <td>Name</td>
                        <td>Email ID</td>
                        <td>Role</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-success btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $user->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn btn-info btn-icon btn-show" data-bs-toggle="modal" data-bs-target="#showModal" data-id="{{ $user->id }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="createModalLabel">Add New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

            <!-- Name -->
            <div class="mb-3 row">
                <label for="createName" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                <div class="col-md-6">
                    <input type="text" id="createName" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3 row">
                <label for="createEmail" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                <div class="col-md-6">
                    <input type="email" id="createEmail" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3 row">
                <label for="createPassword" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                <div class="col-md-4">
                    <input type="text" id="createPassword" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-2">
                    <label style="cursor:pointer; font-size:12px" id="generatePasswordBtn">Generate Password</label>
                </div>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-3 row">
                <label for="createPasswordConfirm" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                <div class="col-md-6">
                    <input type="text" id="createPasswordConfirm" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <!-- Roles (radio buttons) -->
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label text-md-end text-start">Role</label>
                <div class="col-md-6" id="createRolesContainer">
                    @forelse ($roles as $role)
                        @if ($role != 'Super Admin' || Auth::user()->hasRole('Super Admin'))
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="roles[]" id="createRole_{{ $role }}" value="{{ $role }}"
                                    {{ (old('roles') && in_array($role, old('roles'))) ? 'checked' : '' }} required>
                                <label class="form-check-label" for="createRole_{{ $role }}">{{ $role }}</label>
                            </div>
                        @endif
                    @empty
                        <p>No roles available</p>
                    @endforelse
                </div>
                @error('roles')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Add User" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="editForm">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

            <!-- Name -->
            <div class="mb-3 row">
                <label for="editName" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                <div class="col-md-6">
                <input type="text" id="editName" name="name" class="form-control" required>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3 row">
                <label for="editEmail" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                <div class="col-md-6">
                <input type="email" id="editEmail" name="email" class="form-control" required>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3 row">
                <label for="editPassword" class="col-md-4 col-form-label text-md-end text-start">Password (leave blank to keep current)</label>
                <div class="col-md-4">
                <input type="text" id="editPassword" name="password" class="form-control">
                </div>
                <div class="col-md-2">
                <label style="cursor:pointer; font-size:12px"  id="generatePasswordBtnEdit">Generate Password</label>
                </div>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-3 row">
                <label for="editPasswordConfirm" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                <div class="col-md-6">
                <input type="text" id="editPasswordConfirm" name="password_confirmation" class="form-control">
                </div>
            </div>

            <!-- Roles (radio buttons) -->
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label text-md-end text-start">Role</label>
                <div class="col-md-6" id="editRolesContainer">
                    @forelse ($roles as $role)
                        @if ($role != 'Super Admin' || Auth::user()->hasRole('Super Admin'))
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="roles[]" id="editRole_{{ $role }}" value="{{ $role }}" required>
                                <label class="form-check-label" for="editRole_{{ $role }}">{{ $role }}</label>
                            </div>
                        @endif
                    @empty
                        <p>No roles available</p>
                    @endforelse
                </div>
                @error('roles')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-warning" value="Update User" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
    </div>

    <!-- Show Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="showModalLabel">User Details</h5>
            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end text-start">Name</label>
            <div class="col-md-6">
                <p id="showName" class="form-control-plaintext"></p>
            </div>
            </div>

            <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end text-start">Email</label>
            <div class="col-md-6">
                <p id="showEmail" class="form-control-plaintext"></p>
            </div>
            </div>

            <div class="mb-3 row">
            <label class="col-md-4 col-form-label text-md-end text-start">Roles</label>
            <div class="col-md-6">
                <p id="showRoles" class="form-control-plaintext"></p>
            </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery + jQuery Validate (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {
    // Show user modal details
    $(document).on('click', '.btn-show', function() {
        let userId = $(this).data('id');
        $.get('/users/' + userId, function(user) {
            $('#showName').text(user.name);
            $('#showEmail').text(user.email);
            $('#showRoles').text(user.roles.map(r => r.name).join(', '));
            $('#showModal').modal('show');
        });
    });

    // Populate edit modal form
    $(document).on('click', '.btn-edit', function() {
        let userId = $(this).data('id');
        $.get('/users/' + userId, function(user) {
            $('#editForm').attr('action', '/users/' + user.id);
            $('#editName').val(user.name);
            $('#editEmail').val(user.email);
            $('#editPassword').val('');
            $('#editPasswordConfirm').val('');

            // Uncheck all roles radios
            $('#editRolesContainer input[name="roles[]"]').prop('checked', false);

            // Select user's role (single)
            if (user.roles && user.roles.length > 0) {
                let roleName = user.roles[0].name;
                $('#editRolesContainer input[name="roles[]"][value="' + roleName + '"]').prop('checked', true);
            }

            $('#editModal').modal('show');
        });
    });

    // Generate password buttons
    $('#generatePasswordBtn').click(function() {
        let pwd = generatePassword(12);
        $('#createPassword').val(pwd);
        $('#createPasswordConfirm').val(pwd);
    });

    $('#generatePasswordBtnEdit').click(function() {
        let pwd = generatePassword(12);
        $('#editPassword').val(pwd);
        $('#editPasswordConfirm').val(pwd);
    });

    function generatePassword(length) {
        let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let retVal = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        return retVal;
    }

    // jQuery Validation for Create Form
    $('#createUserForm').validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
            password_confirmation: { required: true, equalTo: '#createPassword' },
            'roles[]': { required: true }
        },
        messages: {
            name: { required: "Name is required", minlength: "Minimum 3 characters" },
            email: { required: "Email is required", email: "Enter a valid email" },
            password: { required: "Password is required", minlength: "Minimum 6 characters" },
            password_confirmation: { required: "Confirm your password", equalTo: "Passwords do not match" },
            'roles[]': { required: "Please select a role" }
        },
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
            if (element.attr("name") === "roles[]") {
                error.insertAfter('#createRolesContainer');
            } else {
                error.insertAfter(element);
            }
        }
    });

    // jQuery Validation for Edit Form
    $('#editForm').validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            password: { minlength: 6 },
            password_confirmation: { equalTo: '#editPassword' },
            'roles[]': { required: true }
        },
        messages: {
            name: { required: "Name is required", minlength: "Minimum 3 characters" },
            email: { required: "Email is required", email: "Enter a valid email" },
            password: { minlength: "Minimum 6 characters" },
            password_confirmation: { equalTo: "Passwords do not match" },
            'roles[]': { required: "Please select a role" }
        },
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
            if (element.attr("name") === "roles[]") {
                error.insertAfter('#editRolesContainer');
            } else {
                error.insertAfter(element);
            }
        }
    });
});
</script>
@endsection
