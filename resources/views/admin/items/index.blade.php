@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Item</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Item
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="itemTable">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Item Code</th>
                                    <th>Category Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Image</th>
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
                <h5 class="modal-title" id="exampleModalLabel1">items Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" id="code" class="form-control" placeholder="Enter Code" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" />
                        <div id="imagePreview" class="mt-2 d-none">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px">
                        </div>
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" id="price" class="form-control" placeholder="Enter Price" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="text" id="quantity" class="form-control" placeholder="Enter Quantity" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select id="category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="Additem">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Items Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="compid">
                    <div class="col-md-12 mb-3">
                        <label for="editname" class="form-label">Name</label>
                        <input type="text" id="editname" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="editcode" class="form-label">Code</label>
                        <input type="text" id="editcode" class="form-control" placeholder="Enter Code" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="editimage" class="form-label">Image</label>
                        <input type="file" id="editimage" name="image" class="form-control" accept="image/*" />
                        <div id="editimagePreview" class="mt-2">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px">
                        </div>
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="editprice" class="form-label">Price</label>
                        <input type="text" id="editprice" class="form-control" placeholder="Enter Price" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="editquantity" class="form-label">Quantity</label>
                        <input type="text" id="editquantity" class="form-control" placeholder="Enter Quantity" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="editcategory_id" class="form-label">Category</label>
                        <select id="editcategory_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="EditUser">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        const table = $('#itemTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('admin.item.getall') }}",
                type: 'GET',
            },
            columns: [
                { data: "name" },
                { data: "code" },
                { data: "category_name" },
                { data: "price" },
                { data: "qty" },
                { 
                    data: "image",
                    render: (data, type, row) => {
                        if (data) {
                            return `<img src="${data}" alt="${row.name}" class="img-thumbnail" style="max-height: 50px;">`;
                        }
                        return '<span class="badge bg-label-warning">No Image</span>';
                    }
                },
                {
                    data: "status",
                    render: (data, type, row) => {
                        if (row.status === 1) {
                            return '<span class="badge bg-label-success me-1">Active</span>';
                        }
                        if (row.status === 0) {
                            return '<span class="badge bg-label-danger me-1">Inactive</span>';
                        }
                        return '';
                    }
                },
                {
                    data: "action",
                    render: (data, type, row) => {
                        const statusButton = row.status === 0
                            ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 1)">Activate</button>`
                            : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 0)">Deactivate</button>`;

                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                        const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;
                        return `${statusButton} ${editButton} ${deleteButton}`;
                    },
                },
            ],
        });

        $('#Additem').click(function(e) {
            e.preventDefault();

            // Create FormData object to handle file upload
            let formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('code', $('#code').val());
            formData.append('price', $('#price').val());
            formData.append('quantity', $('#quantity').val());
            formData.append('image', $('#image')[0].files[0]);
            formData.append('category_id', $('#category_id').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Clear previous validation error messages
            $('.error-text').text('');

            $.ajax({
                url: '{{ route('admin.item.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#addModal').modal('hide');
                        $('#addModal').find('input').val('');
                        $('#imagePreview').addClass('d-none');
                        table.ajax.reload();
                    } else {
                        if (response.errors) {
                            for (let field in response.errors) {
                                let $field = $(`#${field}`);
                                if ($field.length) {
                                    $field.siblings('.error-text').text(response.errors[field][0]);
                                }
                            }
                        } else {
                            setFlash("error", response.message);
                        }
                    }
                },
                error: function() {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });

        // Add image preview functionality
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').removeClass('d-none').find('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').addClass('d-none');
            }
        });

        // Define editUser function
        window.editUser = function(userId) {
            const url = '{{ route("admin.item.get", ":userid") }}'.replace(":userid", userId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#compid').val(data.id);
                    $('#editname').val(data.name);
                    $('#editcode').val(data.code);
                    $('#editprice').val(data.price);
                    $('#editquantity').val(data.qty);
                    $('#editcategory_id').val(data.category_id);
                    
                    // Handle image preview
                    if (data.image) {
                        $('#editimagePreview').removeClass('d-none')
                            .find('img').attr('src', data.image);
                    } else {
                        $('#editimagePreview').addClass('d-none');
                    }

                    $('#editModal').modal('show');
                    setFlash("success", 'Item found successfully.');
                },
                error: function() {
                    setFlash("error", "Item not found. Please try again later.");
                }
            });
        };

        // Update the edit form submission handler
        $('#EditUser').on('click', function() {
            const userId = $('#compid').val();
            let formData = new FormData();
            formData.append('id', userId);
            formData.append('name', $('#editname').val());
            formData.append('code', $('#editcode').val());
            formData.append('price', $('#editprice').val());
            formData.append('qty', $('#editquantity').val());
            formData.append('category_id', $('#editcategory_id').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Only append image if a new one is selected
            if ($('#editimage')[0].files[0]) {
                formData.append('image', $('#editimage')[0].files[0]);
            }

            $.ajax({
                url: '{{ route('admin.item.update') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#editModal').modal('hide');
                        $('#editModal').find('input, textarea, select').val('');
                        $('#editimagePreview').addClass('d-none');
                        table.ajax.reload();
                    } else {
                        $('#editModal').find('.error-text').text('');
                        if (response.errors) {
                            for (let field in response.errors) {
                                let $field = $(`#edit${field}`);
                                if ($field.length) {
                                    $field.siblings('.error-text').text(response.errors[field][0]);
                                }
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
            const message = status === "active" ? "item will be able to log in after activation." : "item will not be able to log in after deactivation.";

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
                        url: "{{ route('admin.item.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                const successMessage = status === "active" ? "item activated successfully." : "item deactivated successfully.";
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
                text: "Do you want to delete this item?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("admin.item.destroy", ":userId") }}'.replace(":userId", userId);
                    $.ajax({
                        type: "DELETE",
                        url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", "User deleted successfully.");
                            } else {
                                setFlash("error", "There was an issue deleting the item. Please contact your system administrator.");
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
