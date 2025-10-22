@extends('layouts.vertical', ['page_title' => 'Document Workflow'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
           'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
           'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
           'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>   /* Internal CSS To style the table, extend the layout of the table and adopt the dark mode of the Template */
        /* Floating Label Styles */
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating textarea.form-control {
            min-height: 100px;
            height: auto;
            padding-top: 1.625rem;
        }
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: #2f2f2f;
            transition: all 0.8s;
            pointer-events: none;
            z-index: 1;
        }
        .form-floating input.form-control:focus,
        .form-floating input.form-control:not(:placeholder-shown),
        .form-floating select.form-select:focus,
        .form-floating select.form-select:not([value=""]),
        .form-floating textarea.form-control:focus,
        .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #033c42;
            box-shadow: none;
        }
        .form-floating input.form-control:focus ~ label,
        .form-floating input.form-control:not(:placeholder-shown) ~ label,
        .form-floating select.form-select:focus ~ label,
        .form-floating select.form-select:not([value=""]) ~ label,
        .form-floating textarea.form-control:focus ~ label,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }
        .form-floating input.form-control:focus ~ label::before,
        .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        .form-floating select.form-select:focus ~ label::before,
        .form-floating select.form-select:not([value=""]) ~ label::before,
        .form-floating textarea.form-control:focus ~ label::before,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #033c42;
            border-radius: 5px;
            z-index: -1;
        }

        /* Dark mode styles */
        [data-bs-theme="dark"] .form-floating input.form-control,
        [data-bs-theme="dark"] .form-floating select.form-select,
        [data-bs-theme="dark"] .form-floating textarea.form-control {
            border-color: #6c757d;
            color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .form-floating label {
            color: #adb5bd;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown),
        [data-bs-theme="dark"] .form-floating select.form-select:focus,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]),
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #0dcaf0;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            background: #0dcaf0;
        }

        /* Action Button Styles */
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        
        .action-btn i {
            font-size: 0.875rem;
        }

        /* Workflow Specific Styles */
        .workflow-container {
            padding: 1.5rem;
            background: var(--ct-card-bg);
            border-radius: 0.5rem;
        }

        .workflow-step {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid var(--ct-border-color);
            border-radius: 0.5rem;
            background: var(--ct-card-bg);
        }

        .workflow-step:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .step-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .step-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--ct-body-color);
            margin: 0;
        }

        .step-actions {
            display: flex;
            gap: 0.5rem;
        }

        .drag-handle {
            cursor: move;
            color: var(--ct-gray-500);
            margin-right: 0.5rem;
        }

        .workflow-controls {
            margin-bottom: 1.5rem;
        }

        .workflow-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkflowModal">
                            <i class="fa-solid fa-plus-circle me-1"></i> Add Workflow
                        </button>
                    </div>
                    <h4 class="page-title">Document Workflows</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="workflow-container">
                            @if(isset($workflows) && count($workflows) > 0)
                                @foreach($workflows as $workflow)
                                    <div class="workflow-section mb-4">
                                        <div class="workflow-controls">
                                            <div class="workflow-name">{{ $workflow->name }}</div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info action-btn me-1" 
                                                        onclick="editWorkflow('{{ $workflow->id }}')"
                                                        title="Edit Workflow">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ route('workflows.destroy', $workflow->id) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger action-btn"
                                                            title="Delete Workflow"
                                                            data-name="{{ $workflow->name }}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="workflow-steps" data-workflow-id="{{ $workflow->id }}">
                                            @foreach($workflow->steps as $step)
                                                <div class="workflow-step" data-step-id="{{ $step->id }}">
                                                    <div class="step-header">
                                                        <div>
                                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                                            <span class="step-title">{{ $step->name }}</span>
                                                        </div>
                                                        <div class="step-actions">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-info action-btn"
                                                                    onclick="editStep('{{ $step->id }}')"
                                                                    title="Edit Step">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger action-btn"
                                                                    onclick="deleteStep('{{ $step->id }}')"
                                                                    title="Delete Step">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="step-description">
                                                        {{ $step->description }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" 
                                                class="btn btn-success mt-2"
                                                onclick="addStep('{{ $workflow->id }}')"
                                                title="Add Step">
                                            <i class="fa-solid fa-plus me-1"></i> Add Step
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p>No workflows found. Create your first workflow to get started.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Workflow Modal -->
    <div class="modal fade" id="workflowModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Workflow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="workflowForm"  method="POST">        <!-- The Route to Store Here. Create Action point here HSW 03/01/25 -->
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="workflow_name" name="name" placeholder=" " required>
                            <label for="workflow_name">Workflow Name</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="workflow_description" name="description" placeholder=" " required></textarea>
                            <label for="workflow_description">Description</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Workflow</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Step Modal -->
    <div class="modal fade" id="stepModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Step</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="stepForm"  method="POST">          <!-- Todo; Route to Store-->                    @csrf
                    <input type="hidden" id="workflow_id" name="workflow_id">
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="step_name" name="name" placeholder=" " required>
                            <label for="step_name">Step Name</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="step_description" name="description" placeholder=" " required></textarea>
                            <label for="step_description">Description</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="step_type" name="type" required>
                                <option value="">Select a step type</option>
                                <option value="approval">Approval</option>
                                <option value="review">Review</option>
                                <option value="notification">Notification</option>
                            </select>
                            <label for="step_type">Step Type</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Step</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Sortable for each workflow's steps 
            document.querySelectorAll('.workflow-steps').forEach(function(el) {
                new Sortable(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function(evt) {
                        updateStepOrder(evt.to.dataset.workflowId);
                    }
                });
            });

            // Handle workflow form submission
            $('#workflowForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#workflowModal').modal('hide');
                            form[0].reset();
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error creating workflow');
                    }
                });
            });

            // Handle step form submission
            $('#stepForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#stepModal').modal('hide');
                            form[0].reset();
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error creating step');
                    }
                });
            });

            // Handle workflow deletion
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var workflowName = form.find('button[type="submit"]').data('name');

                if (confirm('Are you sure you want to delete the workflow "' + workflowName + '"?')) {
                    form.off('submit').submit();
                }
            });
        });

        function editWorkflow(id) {
            $.get(`/workflows/${id}/edit`, function(data) {
                $('#workflowForm').attr('action', `/workflows/${id}`);
                $('#workflowForm').append('@method("PUT")');
                $('#workflow_name').val(data.name);
                $('#workflow_description').val(data.description);
                $('.modal-title').text('Edit Workflow');
                $('#workflowModal').modal('show');
            });
        }

        function addStep(workflowId) {
            $('#stepForm')[0].reset();
            $('#workflow_id').val(workflowId);
            $('#stepForm').attr('');
            $('.modal-title').text('Add New Step');
            $('#stepModal').modal('show');
        }

        function editStep(id) {
            $.get(`/workflow-steps/${id}/edit`, function(data) {
                $('#stepForm').attr('action', `/workflow-steps/${id}`);
                $('#stepForm').append('@method("PUT")');
                $('#step_name').val(data.name);
                $('#step_description').val(data.description);
                $('#step_type').val(data.type);
                $('#workflow_id').val(data.workflow_id);
                $('.modal-title').text('Edit Step');
                $('#stepModal').modal('show');
            });
        }

        function deleteStep(id) {
            if (confirm('Are you sure you want to delete this step?')) {
                $.ajax({
                    url: `/workflow-steps/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        alert('Error deleting step');
                    }
                });
            }
        }

        function updateStepOrder(workflowId) {
            const steps = document.querySelectorAll(`[data-workflow-id="${workflowId}"] .workflow-step`);
            const order = Array.from(steps).map((step, index) => ({
                id: step.dataset.stepId,
                order: index + 1
            }));

            $.ajax({
                url: '/workflow-steps/reorder',
                method: 'POST',
                data: {
                    workflow_id: workflowId,
                    steps: order,
                    _token: '{{ csrf_token() }}'
                },
                error: function() {
                    alert('Error updating step order');
                }
            });
        }
    </script>
@endsection
