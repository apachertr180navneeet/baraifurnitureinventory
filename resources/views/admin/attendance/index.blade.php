@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Attendance</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Attendance
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="AttendanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Person Name</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Leave Day</th>
                                    <th>Leave Reason</th>
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

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Attendances Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" placeholder="Enter Date" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="in_time" class="form-label">In Time</label>
                        <input type="text" id="in_time" class="form-control" placeholder="Enter In Time" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="out_time" class="form-label">Out Time</label>
                        <input type="text" id="out_time" class="form-control" placeholder="Enter Out Time" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="leave_day" class="form-label">Leave Day</label>
                        <input type="text" id="leave_day" class="form-control" placeholder="Enter Leave Day" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="leave_reason" class="form-label">Leave Reason</label>
                        <input type="text" id="leave_reason" class="form-control" placeholder="Enter Leave Reason" />
                        <small class="error-text text-danger"></small>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="AddAttendance">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Attendance Edit</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <input type="hidden" id="editid">
                  <div class="col-md-12 mb-3">
                      <label for="editdate" class="form-label">Date</label>
                      <input type="date" id="editdate" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="editname" class="form-label">Name</label>
                      <input type="text" id="editname" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="editin_time" class="form-label">In Time</label>
                      <input type="text" id="editin_time" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="editout_time" class="form-label">Out Time</label>
                      <input type="text" id="editout_time" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="editleave_day" class="form-label">Leave Day</label>
                      <input type="text" id="editleave_day" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="editleave_reason" class="form-label">Leave Reason</label>
                      <input type="text" id="editleave_reason" class="form-control" />
                      <small class="error-text text-danger"></small>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="EditAttendance">Save</button>
          </div>
      </div>
  </div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        const table = $('#AttendanceTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('admin.attendance.getall') }}",
                type: 'GET',
            },
            columns: [
                { data: "date" },
                { data: "name" },
                { data: "in_time" },
                { data: "out_time" },
                { data: "leave_day" },
                { data: "leave_reason" },
                {
                    data: "status",
                    render: (data, type, row) => {
                        if (row.status === "active") {
                            return '<span class="badge bg-label-success me-1">Active</span>';
                        }
                        if (row.status === "inactive") {
                            return '<span class="badge bg-label-danger me-1">Inactive</span>';
                        }
                        return '';
                    }
                },
                {
                    data: "action",
                    render: (data, type, row) => {
                        const statusButton = row.status === "inactive"
                            ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 'active')">Activate</button>`
                            : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 'inactive')">Deactivate</button>`;

                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                        const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;
                        return `${statusButton} ${editButton} ${deleteButton}`;
                    },
                },
            ],
        });

        $('#AddAttendance').click(function(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('date', $('#date').val());
            formData.append('name', $('#name').val());
            formData.append('in_time', $('#in_time').val());
            formData.append('out_time', $('#out_time').val());
            formData.append('leave_day', $('#leave_day').val());
            formData.append('leave_reason', $('#leave_reason').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $('.error-text').text('');

            $.ajax({
                url: '{{ route('admin.attendance.store') }}',
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

        // Define editUser function
        // Load data into edit modal
        window.editUser = function(attId) {
            const url = '{{ route("admin.attendance.get", ":id") }}'.replace(":id", attId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#editid').val(data.id);
                    $('#editdate').val(data.date);
                    $('#editname').val(data.name);
                    $('#editin_time').val(data.in_time);
                    $('#editout_time').val(data.out_time);
                    $('#editleave_day').val(data.leave_day);
                    $('#editleave_reason').val(data.leave_reason);

                    $('#editModal').modal('show');
                },
                error: function() {
                    setFlash("error", "Attendance not found.");
                }
            });
        };

        // Update attendance
        $('#EditAttendance').click(function() {
            let formData = new FormData();
            formData.append('id', $('#editid').val());
            formData.append('date', $('#editdate').val());
            formData.append('name', $('#editname').val());
            formData.append('in_time', $('#editin_time').val());
            formData.append('out_time', $('#editout_time').val());
            formData.append('leave_day', $('#editleave_day').val());
            formData.append('leave_reason', $('#editleave_reason').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '{{ route('admin.attendance.update') }}',
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


        // Update user status
        window.updateUserStatus = function(userId, status) {
            const message = status === "active" ? "Attendance will be able to log in after activation." : "Attendance will not be able to log in after deactivation.";

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
                        url: "{{ route('admin.attendance.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                const successMessage = status === "active" ? "Attendance activated successfully." : "Attendance deactivated successfully.";
                                setFlash("success", successMessage);
                            } else {
                                setFlash("error", "There was an issue changing the status. Please contact your system administrator.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request. Please try again later.");
                        },
                    });
                } else {
                    table.ajax.reload();
                }
            });
        };

        // Delete user
        window.deleteUser = function(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this Attendance?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("admin.attendance.destroy", ":userId") }}'.replace(":userId", userId);
                    $.ajax({
                        type: "DELETE",
                        url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", "User deleted successfully.");
                            } else {
                                setFlash("error", "There was an issue deleting the attendance. Please contact your system administrator.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request. Please try again later.");
                        },
                    });
                }
            });
        };

        // Flash message function using Toast.fire
        function setFlash(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        }
    });
</script>
@endsection
