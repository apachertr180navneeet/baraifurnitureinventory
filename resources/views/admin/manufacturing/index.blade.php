@extends('admin.layouts.app')
@section('style')
@endsection
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Manufacturing</span>
                </h5>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Manufacturing
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
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Quantity</th>
                                        <th>Add Amount</th>
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

    <!-- Add Manufacturing Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Manufacturing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="stockInForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Item</label>
                                <select name="item_id" class="form-select" required>
                                    <option value="">-- Select Item --</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">QTY</label>
                                <input type="number" name="qty" class="form-control" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Add Amount</label>
                                <input type="text" name="add_amount" class="form-control" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Image</label>
                                <input type="file" name="product_image" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <!-- Materials Section -->
                        <h6>Materials Required</h6>
                        <div class="row mb-3">
                            <!-- Material Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label">Material Name</label>
                                <select name="material_name" class="form-select">
                                    <option value="">-- Select Material --</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Qty -->
                            <div class="col-md-4">
                                <label class="form-label">Qty</label>
                                <input type="number" name="material_qty" class="form-control" value="0">
                            </div>

                            <!-- Add Button -->
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="addItemBtn">Add Material</button>
                            </div>
                        </div>

                        <!-- Materials Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Material Name</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Materials will be added dynamically -->
                                </tbody>
                            </table>
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
            serverSide: true,
            ajax: {
                url: "{{ route('admin.manufacturing.getall') }}",
                type: 'GET',
            },
            columns: [
                { data: "item_name" },
                { data: "start_date" },
                { data: "end_date" },
                { data: "qty" },
                { data: "add_amount" },
                {
                    data: "status",
                    render: (data, type, row) => {
                        let badgeClass = "";
                        let label = "";

                        switch (row.status) {
                            case "pending":
                                badgeClass = "bg-label-warning";
                                label = "Pending";
                                break;
                            case "process":
                                badgeClass = "bg-label-info";
                                label = "In Process";
                                break;
                            case "complete":
                                badgeClass = "bg-label-success";
                                label = "Complete";
                                break;
                            default:
                                badgeClass = "bg-label-secondary";
                                label = row.status ?? "Unknown";
                        }
                        return `<span class="badge ${badgeClass}">${label}</span>`;
                    }
                },
                {
                    data: "action",
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        let statusOptions = `
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Change Status
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStockStatus(${row.id}, 'pending')">Pending</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStockStatus(${row.id}, 'process')">Process</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStockStatus(${row.id}, 'complete')">Complete</a></li>
                                </ul>
                            </div>
                        `;

                        //const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteStock(${row.id})">Delete</button>`;
                        return `${statusOptions}`;// ${deleteButton}`;
                    }
                },
            ],
        });

        let materialIndex = 0;

        // Add Material
        $("#addItemBtn").on("click", function () {
            let materialId = $("select[name='material_name']").val();
            let materialName = $("select[name='material_name'] option:selected").text();
            let qty = $("input[name='material_qty']").val();

            if (!materialId || qty <= 0) {
                setFlash("error", "Please select a material and enter a valid quantity.");
                return;
            }

            materialIndex++;

            $("#itemsTable tbody").append(`
                <tr data-index="${materialIndex}">
                    <td>${materialIndex}</td>
                    <td>${materialName}</td>
                    <td>${qty}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeMaterial">Remove</button>
                    </td>

                    <input type="hidden" name="materials[${materialIndex}][material_id]" value="${materialId}">
                    <input type="hidden" name="materials[${materialIndex}][qty]" value="${qty}">
                </tr>
            `);

            $("select[name='material_name']").val("");
            $("input[name='material_qty']").val(1);
        });

        // Remove Material
        $(document).on("click", ".removeMaterial", function () {
            $(this).closest("tr").remove();
        });

        // Save Manufacturing
        $("#saveStockIn").on("click", function(e) {
            e.preventDefault();
            let formData = new FormData($("#stockInForm")[0]);

            $.ajax({
                url: "{{ route('admin.manufacturing.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message ?? "Manufacturing saved successfully!");
                        $("#stockInForm")[0].reset();
                        $("#itemsTable tbody").empty();
                        $("#addModal").modal("hide");
                        table.ajax.reload();
                    } else {
                        setFlash("error", response.message ?? "Something went wrong.");
                    }
                },
                error: function(xhr) {
                    setFlash("error", xhr.responseText ?? "Server error.");
                }
            });
        });

        // Update Status
        window.updateStockStatus = function(stockId, status) {
            Swal.fire({
                title: "Are you sure?",
                text: `This item will be marked as ${status}.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Okay",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manufacturing.status') }}",
                        data: { stockId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", `Item status updated to ${status}.`);
                            } else {
                                setFlash("error", "There was an issue updating the status.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "Request failed.");
                        },
                    });
                }
            });
        };

        // Delete Manufacturing
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
                    const url = '{{ route("admin.manufacturing.destroy", ":userId") }}'.replace(":userId", userId);
                    $.ajax({
                        type: "DELETE",
                        url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", "Manufacturing deleted successfully.");
                            } else {
                                setFlash("error", "There was an issue deleting the item.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "Request failed.");
                        },
                    });
                }
            });
        };
    });
</script>
@endsection
