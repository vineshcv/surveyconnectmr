@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <h5>List of Roles</h5>
        @can('create-role')
        <!-- Create New Role Button -->
        <button type="button" class="btn btn-primary btn-rounded btnFixed" data-bs-toggle="modal" data-bs-target="#roleCreateModal">
            <span>Create new role</span> <i class="fa-solid fa-plus"></i>
        </button>
        @endcan
        <br>

        <!-- Roles Table -->
        <div class="table-responsive">
            <table id="rolesTable" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Role Name</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $index => $role)
                    <tr>
                        <td>{{ $roles->firstItem() + $index }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="actions">
                                <button class="btn btn-info btn-icon btn-show" data-id="{{ $role->id }}"><i class="fa fa-eye"></i></button>

                                @if ($role->name !== 'Super Admin')
                                    @can('edit-role')
                                    <button class="btn btn-success btn-icon btn-edit" data-id="{{ $role->id }}"><i class="fa fa-pen"></i></button>
                                    @endcan

                                    @can('delete-role')
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                                    </form>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $roles->links() }}
        </div>

    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="roleCreateModal" tabindex="-1" aria-labelledby="roleCreateLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createRoleForm" method="POST" action="{{ route('roles.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row jumbotron box8">
            <div class="col-sm-12 form-group">
              <label for="name">Role Name *</label>
              <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="col-sm-12 form-group">
              <label>Permissions</label>
              <div class="row" id="createPermissionsContainer">
                @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                  <div class="col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                      <label class="form-check-label" for="perm_{{ $permission->id }}">{{ str_replace('-', ' ', $permission->name) }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Save Role</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="roleEditModal" tabindex="-1" aria-labelledby="roleEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editRoleForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row jumbotron box8">
            <div class="col-sm-12 form-group">
              <label for="edit_role_name">Role Name *</label>
              <input type="text" class="form-control" name="name" id="edit_role_name" required>
            </div>
            <div class="col-sm-12 form-group">
              <label>Permissions</label>
              <div class="row" id="editPermissionsContainer">
                @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                  <div class="col-md-4">
                    <div class="form-check">
                      <input class="form-check-input edit-permission" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="edit_perm_{{ $permission->id }}">
                      <label class="form-check-label" for="edit_perm_{{ $permission->id }}">{{ str_replace('-', ' ', $permission->name) }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Update Role</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Show Role Modal -->
<div class="modal fade" id="roleShowModal" tabindex="-1" aria-labelledby="roleShowLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Role Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Role Name</label>
          <div class="col-md-6">
            <p id="show_role_name" class="form-control-plaintext"></p>
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Permissions</label>
          <div class="col-md-6">
            <p id="show_permissions" class="form-control-plaintext"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<!-- jQuery Validate (CDN) - jQuery already loaded in main layout -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<style>
  /* Specific styles for roles page modals only */
  #createPermissionsContainer .form-check-label{
    width: 200px !important;
  }
  #editPermissionsContainer .form-check-label{
    width: 200px !important;
  }
</style>

<script>
$(document).ready(function () {
  // Initialize DataTable for roles table
  new DataTable('#rolesTable');

  // Show Role Modal
  $(document).on('click', '.btn-show', function () {
    const id = $(this).data('id');
    $.get(`/roles/${id}`, function (data) {
      $('#show_role_name').text(data.role.name);
      let perms = data.rolePermissions.map(p => p.name.replace(/-/g, ' ')).join(', ');
      $('#show_permissions').text(perms);
      $('#roleShowModal').modal('show');
    });
  });

  // Edit Role Modal
  $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    $.get(`/roles/${id}/edit`, function (data) {
      $('#edit_role_name').val(data.role.name);
      $('#editRoleForm').attr('action', `/roles/${id}`);

      // Uncheck all permissions
      $('.edit-permission').prop('checked', false);

      // Check permissions of role
      data.rolePermissions.forEach(pid => {
        $(`#edit_perm_${pid}`).prop('checked', true);
      });

      $('#roleEditModal').modal('show');
    });
  });

  // Validation for Create Role Form
  $('#createRoleForm').validate({
    rules: {
      name: { required: true, minlength: 3 },
      'permissions[]': { required: true }
    },
    messages: {
      name: { required: "Role name is required", minlength: "Minimum 3 characters" },
      'permissions[]': { required: "Please select at least one permission" }
    },
    errorClass: 'text-danger',
    errorPlacement: function(error, element) {
      if (element.attr("name") === "permissions[]") {
        error.insertAfter('#createPermissionsContainer');
      } else {
        error.insertAfter(element);
      }
    }
  });

  // Validation for Edit Role Form
  $('#editRoleForm').validate({
    rules: {
      name: { required: true, minlength: 3 },
      'permissions[]': { required: true }
    },
    messages: {
      name: { required: "Role name is required", minlength: "Minimum 3 characters" },
      'permissions[]': { required: "Please select at least one permission" }
    },
    errorClass: 'text-danger',
    errorPlacement: function(error, element) {
      if (element.attr("name") === "permissions[]") {
        error.insertAfter('#editPermissionsContainer');
      } else {
        error.insertAfter(element);
      }
    }
  });

});
</script>
@endsection
