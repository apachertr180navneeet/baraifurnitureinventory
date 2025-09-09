@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Stock In Management</span>
                </h5>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Stock In
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
                                        <th>Vendor Name</th>
                                        <th>Item Name</th>
                                        <th>Rate</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
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

    <!-- Add Stock In Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="stockInForm">
                        <div class="row mb-3">
                            <!-- Date -->
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Vendor Dropdown -->
                            <div class="col-md-6">
                                <label class="form-label">Vendor</label>
                                <select name="vendor_id" class="form-select" required>
                                    <option value="">-- Select Vendor --</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addVendorModal">
                                    +
                                </button>
                            </div>
                        </div>

                        <hr>

                        <!-- Items Section -->
                        <h6>Items Details</h6>
                        <div class="row mb-3">
                            <!-- Item Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label">Item</label>
                                <select name="item_id" class="form-select">
                                    <option value="">-- Select Item --</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Qty -->
                            <div class="col-md-2">
                                <label class="form-label">Qty</label>
                                <input type="number" name="qty" class="form-control" min="1" value="1">
                            </div>

                            <!-- Rate -->
                            <div class="col-md-2">
                                <label class="form-label">Rate</label>
                                <input type="number" name="rate" class="form-control" step="0.01">
                            </div>

                            <!-- Add Button -->
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="addItemBtn">Add Item</button>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Item Name</th>
                                        <th>Rate</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Items will be added dynamically -->
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end">
                            <h5>Total Amount: <span id="totalAmount">0.00</span></h5>
                            <!-- Hidden Input for Total Amount -->
                            <input type="hidden" name="total_amount" id="total_amount" value="0.00">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveStockIn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Vendor Modal -->
    <div class="modal fade" id="addVendorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="vendorForm">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveVendorBtn">Save Vendor</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function setFlash(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        $(document).ready(function() {

            const table = $('#itemTable').DataTable({
                processing: true,
                serverSide: true, // ✅ optional if you paginate from backend
                ajax: {
                    url: "{{ route('admin.stockIn.getall') }}",
                    type: 'GET',
                },
                columns: [
                    { data: "vendor_name" }, // Vendor Name
                    { data: "item_name" },   // Item Name
                    { data: "rate" },        // Rate
                    { data: "qty" },         // Quantity
                    { data: "total_amount" },// Total Amount
                    {
                        data: "status",
                        render: (data, type, row) => {
                            if (row.status === 'active') {
                                return '<span class="badge bg-label-success">Active</span>';
                            }
                            if (row.status === 'inactive') {
                                return '<span class="badge bg-label-danger">Inactive</span>';
                            }
                            return '';
                        }
                    },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                        render: (data, type, row) => {
                            const statusButton = row.status === 'inactive'
                                ? `<button type="button" class="btn btn-sm btn-success" onclick="updateStockStatus(${row.id}, 'active')">Activate</button>`
                                : `<button type="button" class="btn btn-sm btn-danger" onclick="updateStockStatus(${row.id}, 'inactive')">Deactivate</button>`;

                            //const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editStock(${row.id})">Edit</button>`;
                            const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteStock(${row.id})">Delete</button>`;

                            return `${statusButton} ${deleteButton}`;
                        }
                    },
                ],
            });

            // Save Vendor
            $("#saveVendorBtn").on("click", function() {
                let formData = $("#vendorForm").serialize();

                $.ajax({
                    url: "{{ route('admin.vendor.store') }}",
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $("select[name='vendor_id']").append(
                                `<option value="${response.vendor.id}" selected>${response.vendor.name}</option>`
                            );

                            $("#addVendorModal").modal("hide");
                            $("#vendorForm")[0].reset();

                            // 🔥 Reopen Stock In modal
                            setTimeout(function() {
                                $("#addModal").modal("show");
                            }, 500);

                            setFlash("success", response.message ??
                                "Vendor added successfully!");
                        } else {
                            setFlash("error", response.message ??
                                "Something went wrong while adding vendor.");
                        }
                    },
                    error: function(xhr) {
                        setFlash("error", xhr.responseText ??
                            "Server error while saving vendor.");
                    },
                });
            });

            let itemIndex = 0;
            let grandTotal = 0;

            // Add Item to table
            $("#addItemBtn").on("click", function() {
                let itemId = $("select[name='item_id']").val();
                let itemName = $("select[name='item_id'] option:selected").text();
                let qty = $("input[name='qty']").val();
                let rate = $("input[name='rate']").val();

                if (!itemId || qty <= 0 || rate <= 0) {
                    setFlash("error", "Please select item, enter qty and rate properly.");
                    return;
                }

                let total = (qty * rate).toFixed(2);
                itemIndex++;

                $("#itemsTable tbody").append(`
                    <tr data-index="${itemIndex}">
                        <td>${itemIndex}</td>
                        <td>${itemName}</td>
                        <td>${rate}</td>
                        <td>${qty}</td>
                        <td class="rowTotal">${total}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeItem">X</button>
                        </td>

                        <input type="hidden" name="items[${itemIndex}][item_id]" value="${itemId}">
                        <input type="hidden" name="items[${itemIndex}][qty]" value="${qty}">
                        <input type="hidden" name="items[${itemIndex}][rate]" value="${rate}">
                        <input type="hidden" class="itemTotalInput" name="items[${itemIndex}][total]" value="${total}">
                    </tr>
                `);

                updateTotalAmount();

                $("select[name='item_id']").val("");
                $("input[name='qty']").val(1);
                $("input[name='rate']").val("");
            });

            // Remove Item
            $(document).on("click", ".removeItem", function() {
                $(this).closest("tr").remove();
                updateTotalAmount();
            });

            function updateTotalAmount() {
                grandTotal = 0;
                $("#itemsTable tbody tr").each(function() {
                    let rowTotal = parseFloat($(this).find(".rowTotal").text());
                    grandTotal += rowTotal;
                });

                $("#totalAmount").text(grandTotal.toFixed(2));
                $("#total_amount").val(grandTotal.toFixed(2));
            }

            // Save Stock In
            $("#saveStockIn").on("click", function(e) {
                e.preventDefault();

                let formData = $("#stockInForm").serialize();

                $.ajax({
                    url: "{{ route('admin.stockIn.store') }}",
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            setFlash("success", response.message ??
                                "Stock In saved successfully!");

                            $("#stockInForm")[0].reset();
                            $("#itemsTable tbody").empty();
                            $("#totalAmount").text("0.00");

                            $("#addModal").modal("hide");
                            table.ajax.reload();
                        } else {
                            setFlash("error", response.message ??
                                "Something went wrong while saving stock in.");
                        }
                    },
                    error: function(xhr) {
                        setFlash("error", xhr.responseText ??
                            "Server error while saving stock in.");
                    }
                });
            });

            window.updateStockStatus = function(stockId, status) {
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
                            url: "{{ route('admin.stockIn.status') }}",
                            data: { stockId, status, _token: $('meta[name="csrf-token"]').attr('content') },
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
            window.deleteStock = function(userId) {
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
                        const url = '{{ route("admin.stockIn.destroy", ":userId") }}'.replace(":userId", userId);
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
        });
    </script>
@endsection
