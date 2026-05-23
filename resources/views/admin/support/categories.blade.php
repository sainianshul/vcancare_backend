@extends('admin.layouts.app')

@section('title', 'Manage Support Categories')

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Support Tickets', 'url' => route('admin.support.index')],
        ['label' => 'Manage Categories']
    ]" />

    <div class="row g-5 g-xl-8">
        <div class="col-xl-8">
            <div class="card border border-gray-200 mb-5">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Support Categories</span>
                            <span class="text-muted fw-semibold fs-7">Manage ticket categories</span>
                        </h3>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_category">
                            <i class="ki-outline ki-plus fs-4"></i> Add Category
                        </button>
                    </div>
                </div>
                
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_categories_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">ID</th>
                                    <th class="min-w-200px">Name</th>
                                    <th class="min-w-100px text-center">Status</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td class="text-gray-900 fw-bold">{{ $category->name }}</td>
                                        <td class="text-center">
                                            @if($category->status)
                                                <span class="badge badge-light-success border border-success px-2 py-1 fs-8 fw-bold">
                                                    <i class="ki-outline ki-check-circle fs-7 text-success me-1"></i> Active
                                                </span>
                                            @else
                                                <span class="badge badge-light-danger border border-danger px-2 py-1 fs-8 fw-bold">
                                                    <i class="ki-outline ki-cross-circle fs-7 text-danger me-1"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-icon btn-light-primary border border-primary btn-sm me-2" 
                                                    onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->status }})"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="ki-outline ki-pencil fs-5"></i>
                                            </button>
                                            
                                            <form action="{{ route('admin.support.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category? (Soft Delete will be applied)');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-light-danger border border-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <div class="d-flex flex-column flex-center">
                                                <i class="ki-outline ki-file text-muted fs-4x mb-3"></i>
                                                <span class="fs-6">No categories found. Create one to get started.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border border-gray-200">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center mb-5">
                        <i class="ki-outline ki-information-5 text-primary fs-1 me-3"></i>
                        <h2 class="text-gray-900 fw-bold mb-0">About Categories</h2>
                    </div>
                    
                    <p class="text-gray-700 fs-6 fw-semibold mb-6">
                        Categories help you organize and filter support tickets efficiently. When creating a new category, it will immediately be available for users when they open new tickets.
                    </p>

                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-30px me-3">
                            <span class="symbol-label bg-light-success border border-success">
                                <i class="ki-outline ki-check-circle text-success fs-4"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-900 fw-bold fs-6">Active Categories</span>
                            <span class="text-muted fs-8">Visible to users to select.</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-30px me-3">
                            <span class="symbol-label bg-light-danger border border-danger">
                                <i class="ki-outline ki-cross-circle text-danger fs-4"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-900 fw-bold fs-6">Inactive Categories</span>
                            <span class="text-muted fs-8">Hidden from users but retained.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="kt_modal_add_category" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content border border-gray-300 shadow-sm rounded">
                <form class="form" action="{{ route('admin.support.categories.store') }}" method="POST" id="kt_modal_add_category_form">
                    @csrf
                    <div class="modal-header border-bottom border-gray-200">
                        <h2 class="fw-bold fs-4 m-0">Add Support Category</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </div>
                    </div>
                    <div class="modal-body py-7 px-lg-10">
                        <div class="fv-row mb-7">
                            <label class="required fs-7 fw-semibold text-gray-800 mb-2">Category Name</label>
                            <input type="text" class="form-control" placeholder="e.g. Technical Issue" name="name" required />
                        </div>
                        <div class="fv-row mb-2">
                            <label class="required fs-7 fw-semibold text-gray-800 mb-2">Status</label>
                            <select name="status" class="form-select form-select-transparent border border-gray-300" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_modal_add_category">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-gray-200 py-4 flex-center">
                        <button type="reset" class="btn btn-light btn-sm fw-bold me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold">
                            <i class="ki-outline ki-check fs-5 me-1"></i> Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="kt_modal_edit_category" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content border border-gray-300 shadow-sm rounded">
                <form class="form" action="" method="POST" id="kt_modal_edit_category_form">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom border-gray-200">
                        <h2 class="fw-bold fs-4 m-0">Edit Support Category</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </div>
                    </div>
                    <div class="modal-body py-7 px-lg-10">
                        <div class="fv-row mb-7">
                            <label class="required fs-7 fw-semibold text-gray-800 mb-2">Category Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required />
                        </div>
                        <div class="fv-row mb-2">
                            <label class="required fs-7 fw-semibold text-gray-800 mb-2">Status</label>
                            <select name="status" id="edit_status" class="form-select form-select-transparent border border-gray-300">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-gray-200 py-4 flex-center">
                        <button type="button" class="btn btn-light btn-sm fw-bold me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold">
                            <i class="ki-outline ki-check fs-5 me-1"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function editCategory(id, name, status) {
        var form = document.getElementById('kt_modal_edit_category_form');
        form.action = "{{ route('admin.support.categories.index') }}/" + id;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_status').value = status;
        
        var editModal = new bootstrap.Modal(document.getElementById('kt_modal_edit_category'));
        editModal.show();
    }
</script>
@endpush
