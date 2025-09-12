@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Machine</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Machine
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="machineTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Machine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" id="remark" class="form-control" placeholder="Enter remark" />
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="Addmachine">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Edit Machine</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <input type="hidden" id="editid">
                  <div class="col-md-12 mb-3">
                      <label for="editname" class="form-label">Name</label>
                      <input type="text" id="editname" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="editremark" class="form-label">Remark</label>
                      <input type="text" id="editremark" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="Editmachine">Save</button>
          </div>
      </div>
  </div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        const table = $('#machineTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('admin.machine.getall') }}",
                type: 'GET',
            },
            columns: [
                { data: "name" },
                { data: "remark" },
                {
                    data: "status",
                    render: (data, type, row) => {
                        if (row.status === "active") {
                            return '<span class="badge bg-label-success me-1">Active</span>';
                        }
                        if (row.status === "Inactive") {
                            return '<span class="badge bg-label-danger me-1">Inactive</span>';
                        }
                        return '';
                    }
                },
                {
                    data: "action",
                    render: (data, type, row) => {
                        const statusButton = row.status === "Inactive"
                            ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 'active')">Activate</button>`
                            : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 'inactive')">Deactivate</button>`;

                        const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;
                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                        return `${statusButton} ${editButton} ${deleteButton}`;
                    },
                },
            ],
        });

        // Add Machine
        $('#Addmachine').click(function(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('remark', $('#remark').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $('.error-text').text('');

            $.ajax({
                url: '{{ route('admin.machine.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#addModal').modal('hide');
                        $('#addModal').find('input').val('');
                        table.ajax.reload();
                    } else if (response.errors) {
                        for (let field in response.errors) {
                            let $field = $(`#${field}`);
                            if ($field.length) {
                                $field.siblings('.error-text').text(response.errors[field][0]);
                            }
                        }
                    } else {
                        setFlash("error", response.message);
                    }
                },
                error: function() {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });

        // Edit Machine
        window.editUser = function(id) {
            const url = '{{ route("admin.machine.get", ":id") }}'.replace(":id", id);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#editid').val(data.id);
                    $('#editname').val(data.name);
                    $('#editremark').val(data.remark);
                    $('#editModal').modal('show');
                },
                error: function() {
                    setFlash("error", "Machine not found.");
                }
            });
        };

        $('#Editmachine').click(function() {
            let formData = new FormData();
            formData.append('id', $('#editid').val());
            formData.append('name', $('#editname').val());
            formData.append('remark', $('#editremark').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '{{ route('admin.machine.update') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#editModal').modal('hide');
                        $('#editModal').find('input').val('');
                        table.ajax.reload();
                    } else if (response.errors) {
                        $('#editModal').find('.error-text').text('');
                        for (let field in response.errors) {
                            let $field = $(`#edit${field}`);
                            if ($field.length) {
                                $field.siblings('.error-text').text(response.errors[field][0]);
                            }
                        }
                    }
                },
                error: function() {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });

        // Update Machine Status
        window.updateUserStatus = function(userId, status) {
            const message = status === "active" ? "Machine will be activated." : "Machine will be deactivated.";

            Swal.fire({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Okay",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.machine.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", response.message);
                            } else {
                                setFlash("error", "There was an issue changing the status.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request.");
                        },
                    });
                } else {
                    table.ajax.reload();
                }
            });
        };

        // Delete Machine
        window.deleteUser = function(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this machine?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("admin.machine.destroy", ":userId") }}'.replace(":userId", userId);
                    $.ajax({
                        type: "DELETE",
                        url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", "Machine deleted successfully.");
                            } else {
                                setFlash("error", "There was an issue deleting the machine.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request.");
                        },
                    });
                }
            });
        };

        // Flash message function
        function setFlash(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        }
    });
</script>
@endsection
