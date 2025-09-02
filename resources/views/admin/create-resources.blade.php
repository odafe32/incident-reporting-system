

                    @extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-end">
                    <div class="btn-group">
                        <a href="{{ route('admin.resources') }}" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-arrow-left me-1"></i> Back to Resources
                        </a>
                        <button class="btn btn-info btn-sm" onclick="showBulkImportModal()">
                            <i class="mdi mdi-upload me-1"></i> Bulk Import
                        </button>
                    </div>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-plus-circle me-2"></i>Add New Resource
                </h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Resource Information</h4>
                </div>
                <div class="card-body">
                    <form id="createResourceForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Resource Type Selection -->
                        <div class="mb-4">
                            <label class="form-label">Resource Type <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="type" id="typeBed" value="bed" required>
                                        <label class="form-check-label card-radio-label" for="typeBed">
                                            <div class="card-radio">
                                                <div class="text-center p-3">
                                                    <i class="mdi mdi-bed display-4 text-primary"></i>
                                                    <h6 class="mt-2 mb-0">Bed</h6>
                                                    <p class="text-muted font-13">Hospital beds, ICU beds, etc.</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="type" id="typeEquipment" value="equipment" required>
                                        <label class="form-check-label card-radio-label" for="typeEquipment">
                                            <div class="card-radio">
                                                <div class="text-center p-3">
                                                    <i class="mdi mdi-medical-bag display-4 text-success"></i>
                                                    <h6 class="mt-2 mb-0">Equipment</h6>
                                                    <p class="text-muted font-13">Medical equipment, devices</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="type" id="typeStaff" value="staff" required>
                                        <label class="form-check-label card-radio-label" for="typeStaff">
                                            <div class="card-radio">
                                                <div class="text-center p-3">
                                                    <i class="mdi mdi-account-group display-4 text-info"></i>
                                                    <h6 class="mt-2 mb-0">Staff</h6>
                                                    <p class="text-muted font-13">Human resources, specialists</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Please select a resource type.
                            </div>
                        </div>

                        <!-- Resource Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Resource Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter resource name" required>
                                    <div class="invalid-feedback">
                                        Please provide a resource name.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g., ICU Ward A, Room 205" required>
                                    <div class="invalid-feedback">
                                        Please provide a location.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Additional details about the resource..."></textarea>
                        </div>

                        <!-- Quick Add Multiple -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="multipleResources">
                                <label class="form-check-label" for="multipleResources">
                                    Add multiple resources of the same type
                                </label>
                            </div>
                        </div>

                        <!-- Multiple Resources Section (Hidden by default) -->
                        <div id="multipleResourcesSection" class="mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Quick Add Multiple Resources</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" id="resourceQuantity" class="form-control" 
                                                       min="2" max="50" value="2" placeholder="Number of resources">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Naming Pattern</label>
                                                <select id="namingPattern" class="form-select">
                                                    <option value="numbered">Add numbers (e.g., Bed 1, Bed 2)</option>
                                                    <option value="lettered">Add letters (e.g., Bed A, Bed B)</option>
                                                    <option value="custom">Custom suffix</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Custom Suffix Input (Hidden by default) -->
                                    <div id="customSuffixSection" class="mb-3" style="display: none;">
                                        <label class="form-label">Custom Suffix Pattern</label>
                                        <input type="text" id="customSuffix" class="form-control" 
                                               placeholder="e.g., -A, -Unit, etc.">
                                        <small class="text-muted">This will be appended to each resource name</small>
                                    </div>
                                    
                                    <!-- Preview Section -->
                                    <div id="resourcePreview" class="mt-3">
                                        <h6 class="text-muted">Preview:</h6>
                                        <div id="previewList" class="bg-white p-2 rounded border">
                                            <!-- Dynamic preview content -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="mdi mdi-refresh me-1"></i>Reset Form
                            </button>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="previewResources()">
                                    <i class="mdi mdi-eye me-1"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check me-1"></i>Create Resource(s)
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Templates -->
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-lightning-bolt me-2"></i>Quick Templates
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="template-card" onclick="applyTemplate('icu-bed')">
                                <div class="text-center p-3">
                                    <i class="mdi mdi-bed text-primary display-6"></i>
                                    <h6 class="mt-2">ICU Bed Setup</h6>
                                    <p class="text-muted font-13">Standard ICU bed configuration</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="template-card" onclick="applyTemplate('or-equipment')">
                                <div class="text-center p-3">
                                    <i class="mdi mdi-medical-bag text-success display-6"></i>
                                    <h6 class="mt-2">OR Equipment</h6>
                                    <p class="text-muted font-13">Operating room equipment set</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="template-card" onclick="applyTemplate('emergency-staff')">
                                <div class="text-center p-3">
                                    <i class="mdi mdi-account-group text-info display-6"></i>
                                    <h6 class="mt-2">Emergency Staff</h6>
                                    <p class="text-muted font-13">Emergency department staff</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Import Modal -->
<div class="modal fade" id="bulkImportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-upload me-2"></i>Bulk Import Resources
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Upload CSV File</label>
                    <input type="file" id="bulkImportFile" class="form-control" accept=".csv">
                    <small class="text-muted">
                        CSV should have columns: name, type, location, description
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6>Sample CSV Format:</h6>
                    <div class="bg-light p-3 rounded">
                        <code>
                            name,type,location,description<br>
                            ICU Bed 1,bed,ICU Ward A,Standard ICU bed<br>
                            Ventilator 1,equipment,ICU Ward A,Mechanical ventilator<br>
                            Dr. Smith,staff,Emergency Dept,Emergency physician
                        </code>
                    </div>
                </div>
                
                <div class="mb-3">
                    <a href="#" class="btn btn-outline-primary btn-sm" onclick="downloadTemplate()">
                        <i class="mdi mdi-download me-1"></i>Download Template
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processBulkImport()">
                    <i class="mdi mdi-upload me-1"></i>Import Resources
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Card Radio Styles */
.form-check-card {
    margin-bottom: 0;
}

.card-radio {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
}

.card-radio:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
}

.form-check-input:checked + .card-radio-label .card-radio {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

.form-check-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.card-radio-label {
    cursor: pointer;
    margin-bottom: 0;
    width: 100%;
}

/* Template Cards */
.template-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}

.template-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
    transform: translateY(-2px);
}

/* Form Validation Styles */
.was-validated .form-check-input:invalid ~ .card-radio-label .card-radio {
    border-color: #dc3545;
}

.was-validated .form-check-input:valid ~ .card-radio-label .card-radio {
    border-color: #28a745;
}

/* Preview Styles */
.preview-item {
    padding: 0.5rem;
    margin: 0.25rem 0;
    background: #f8f9fa;
    border-radius: 4px;
    border-left: 3px solid #007bff;
}

/* Loading States */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-radio {
        margin-bottom: 1rem;
    }
    
    .template-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize event listeners
    initializeEventListeners();
});

function initializeFormValidation() {
    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                event.preventDefault();
                submitForm();
            }
            form.classList.add('was-validated');
        });
    });
}

function initializeEventListeners() {
    // Multiple resources checkbox
    document.getElementById('multipleResources').addEventListener('change', function() {
        const section = document.getElementById('multipleResourcesSection');
        if (this.checked) {
            section.style.display = 'block';
            updatePreview();
        } else {
            section.style.display = 'none';
        }
    });
    
    // Naming pattern change
    document.getElementById('namingPattern').addEventListener('change', function() {
        const customSection = document.getElementById('customSuffixSection');
        if (this.value === 'custom') {
            customSection.style.display = 'block';
        } else {
            customSection.style.display = 'none';
        }
        updatePreview();
    });
    
    // Update preview on input changes
    document.getElementById('resourceQuantity').addEventListener('input', updatePreview);
    document.getElementById('customSuffix').addEventListener('input', updatePreview);
    document.querySelector('input[name="name"]').addEventListener('input', updatePreview);
    
    // Resource type change
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', updatePreview);
    });
}

function updatePreview() {
    const multipleChecked = document.getElementById('multipleResources').checked;
    if (!multipleChecked) return;
    
    const baseName = document.querySelector('input[name="name"]').value || 'Resource';
    const quantity = parseInt(document.getElementById('resourceQuantity').value) || 2;
    const pattern = document.getElementById('namingPattern').value;
    const customSuffix = document.getElementById('customSuffix').value;
    
    const previewList = document.getElementById('previewList');
    let previewHtml = '';
    
    for (let i = 1; i <= Math.min(quantity, 10); i++) { // Limit preview to 10 items
        let name = baseName;
        
        switch (pattern) {
            case 'numbered':
                name += ` ${i}`;
                break;
            case 'lettered':
                name += ` ${String.fromCharCode(64 + i)}`; // A, B, C, etc.
                break;
            case 'custom':
                name += customSuffix + i;
                break;
        }
        
        previewHtml += `<div class="preview-item">${name}</div>`;
    }
    
    if (quantity > 10) {
        previewHtml += `<div class="text-muted text-center">... and ${quantity - 10} more</div>`;
    }
    
    previewList.innerHTML = previewHtml;
}

function submitForm() {
    const form = document.getElementById('createResourceForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Add loading state
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;
    
    // Check if multiple resources
    const multipleChecked = document.getElementById('multipleResources').checked;
    
    if (multipleChecked) {
        submitMultipleResources(formData, submitBtn);
    } else {
        submitSingleResource(formData, submitBtn);
    }
}

function submitSingleResource(formData, submitBtn) {
    fetch('{{ route("admin.resources.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Resource created successfully!');
            resetForm();
        } else {
            showAlert('error', data.message || 'Failed to create resource');
        }
    })
    .catch(error => {
        console.error('Error creating resource:', error);
        showAlert('error', 'An error occurred while creating the resource');
    })
    .finally(() => {
        submitBtn.classList.remove('btn-loading');
        submitBtn.disabled = false;
    });
}

function submitMultipleResources(formData, submitBtn) {
    const baseName = formData.get('name');
    const type = formData.get('type');
    const location = formData.get('location');
    const description = formData.get('description');
    const quantity = parseInt(document.getElementById('resourceQuantity').value);
    const pattern = document.getElementById('namingPattern').value;
    const customSuffix = document.getElementById('customSuffix').value;
    
    const resources = [];
    
    for (let i = 1; i <= quantity; i++) {
        let name = baseName;
        
        switch (pattern) {
            case 'numbered':
                name += ` ${i}`;
                break;
            case 'lettered':
                name += ` ${String.fromCharCode(64 + i)}`;
                break;
            case 'custom':
                name += customSuffix + i;
                break;
        }
        
        resources.push({
            name: name,
            type: type,
            location: location,
            description: description
        });
    }
    
    // Submit multiple resources
    fetch('{{ route("admin.resources.store-multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ resources: resources })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `${quantity} resources created successfully!`);
            resetForm();
        } else {
            showAlert('error', data.message || 'Failed to create resources');
        }
    })
    .catch(error => {
        console.error('Error creating resources:', error);
        showAlert('error', 'An error occurred while creating the resources');
    })
    .finally(() => {
        submitBtn.classList.remove('btn-loading');
        submitBtn.disabled = false;
    });
}

function resetForm() {
    const form = document.getElementById('createResourceForm');
    form.reset();
    form.classList.remove('was-validated');
    
    // Hide multiple resources section
    document.getElementById('multipleResourcesSection').style.display = 'none';
    document.getElementById('customSuffixSection').style.display = 'none';
    document.getElementById('multipleResources').checked = false;
    
    // Clear preview
    document.getElementById('previewList').innerHTML = '';
}

function previewResources() {
    const form = document.getElementById('createResourceForm');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        showAlert('warning', 'Please fill in all required fields');
        return;
    }
    
    // Show preview in modal or alert
    const multipleChecked = document.getElementById('multipleResources').checked;
    const baseName = document.querySelector('input[name="name"]').value;
    
    if (multipleChecked) {
        const quantity = parseInt(document.getElementById('resourceQuantity').value);
        showAlert('info', `Preview: Will create ${quantity} resources starting with "${baseName}"`);
    } else {
        showAlert('info', `Preview: Will create 1 resource named "${baseName}"`);
    }
}

function applyTemplate(templateType) {
    const templates = {
        'icu-bed': {
            type: 'bed',
            name: 'ICU Bed',
            location: 'ICU Ward A',
            description: 'Standard ICU bed with monitoring capabilities'
        },
        'or-equipment': {
            type: 'equipment',
            name: 'OR Equipment Set',
            location: 'Operating Room',
            description: 'Complete operating room equipment set'
        },
        'emergency-staff': {
            type: 'staff',
            name: 'Emergency Physician',
            location: 'Emergency Department',
            description: 'Emergency department medical staff'
        }
    };
    
    const template = templates[templateType];
    if (!template) return;
    
    // Apply template values
    document.querySelector(`input[value="${template.type}"]`).checked = true;
    document.querySelector('input[name="name"]').value = template.name;
    document.querySelector('input[name="location"]').value = template.location;
    document.querySelector('textarea[name="description"]').value = template.description;
    
    // Enable multiple resources for templates
    document.getElementById('multipleResources').checked = true;
    document.getElementById('multipleResourcesSection').style.display = 'block';
    
    updatePreview();
    showAlert('success', `Applied ${templateType} template`);
}

function showBulkImportModal() {
    const modal = new bootstrap.Modal(document.getElementById('bulkImportModal'));
    modal.show();
}

function downloadTemplate() {
    const csvContent = "name,type,location,description\nICU Bed 1,bed,ICU Ward A,Standard ICU bed\nVentilator 1,equipment,ICU Ward A,Mechanical ventilator\nDr. Smith,staff,Emergency Dept,Emergency physician";
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'resource_template.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function processBulkImport() {
    const fileInput = document.getElementById('bulkImportFile');
    const file = fileInput.files[0];
    
    if (!file) {
        showAlert('warning', 'Please select a CSV file');
        return;
    }
    
    const formData = new FormData();
    formData.append('csv_file', file);
    
    fetch('{{ route("admin.resources.bulk-import") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `Successfully imported ${data.count} resources`);
            bootstrap.Modal.getInstance(document.getElementById('bulkImportModal')).hide();
        } else {
            showAlert('error', data.message || 'Failed to import resources');
        }
    })
    .catch(error => {
        console.error('Error importing resources:', error);
        showAlert('error', 'An error occurred during import');
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const iconClass = type === 'success' ? 'mdi-check-circle' : 
                     type === 'error' ? 'mdi-alert-circle' : 
                     type === 'warning' ? 'mdi-alert-triangle' : 'mdi-information';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="mdi ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>
@endsection