@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Edit Vendor Mapping - {{ $vendor->vendor_name }}</span>
                    <a href="{{ route('projects.edit', $project->id) }}#addVendor" class="btn btn-primary btn-sm">&larr; Back to Project</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('projects.updateVendorMapping', [$project->id, $vendor->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Quota -->
                            <div class="col-md-6">
                                <label for="quota" class="form-label">Quota</label>
                                <input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror"
                                       value="{{ old('quota', $project->vendorQuotas->where('vendor_id', $vendor->id)->first()->quota_allot ?? 0) }}" min="0" required>
                                @error('quota') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Study URL -->
                            <div class="col-md-6">
                                <label for="study_url" class="form-label">Study URL (Live)</label>
                                <div class="input-group">
                                    <input type="url" id="study_url" name="study_url" class="form-control @error('study_url') is-invalid @enderror"
                                           value="{{ old('study_url', $mapping->study_url ?? '') }}">
                                    <button type="button" class="btn btn-outline-primary" onclick="generateLiveUrl()">
                                        <i class="fa fa-magic"></i> Generate
                                    </button>
                                </div>
                                @error('study_url') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="text-muted">Auto-generates: {{ url('/survey') }}?pid={{ $project->projectID }}&vid={{ $vendor->vendor_id }}&toid=[TOID]</small>
                            </div>

                            <!-- Security Full URL -->
                            <div class="col-md-6">
                                <label for="security_full_url" class="form-label">Security Full URL</label>
                                <input type="url" id="security_full_url" name="security_full_url" class="form-control @error('security_full_url') is-invalid @enderror"
                                       value="{{ old('security_full_url', $mapping->security_full_url ?? $vendor->security_full_redirect_url) }}">
                                @error('security_full_url') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="text-muted">Enter the security full redirect URL manually</small>
                                @if($vendor->security_full_redirect_url)
                                    <small class="text-info d-block">Vendor default: {{ $vendor->security_full_redirect_url }}</small>
                                @endif
                            </div>

                            <!-- Success URL -->
                            <div class="col-md-6">
                                <label for="success_url" class="form-label">Success Redirect URL</label>
                                <input type="url" id="success_url" name="success_url" class="form-control @error('success_url') is-invalid @enderror"
                                       value="{{ old('success_url', $mapping->success_url ?? $vendor->completed_redirect_url) }}">
                                @error('success_url') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="text-muted">Enter the success redirect URL manually</small>
                                @if($vendor->completed_redirect_url)
                                    <small class="text-info d-block">Vendor default: {{ $vendor->completed_redirect_url }}</small>
                                @endif
                            </div>

                            <!-- Terminate URL -->
                            <div class="col-md-6">
                                <label for="terminate_url" class="form-label">Terminate Redirect URL</label>
                                <input type="url" id="terminate_url" name="terminate_url" class="form-control @error('terminate_url') is-invalid @enderror"
                                       value="{{ old('terminate_url', $mapping->terminate_url ?? $vendor->terminated_redirect_url) }}">
                                @error('terminate_url') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="text-muted">Enter the terminate redirect URL manually</small>
                                @if($vendor->terminated_redirect_url)
                                    <small class="text-info d-block">Vendor default: {{ $vendor->terminated_redirect_url }}</small>
                                @endif
                            </div>

                            <!-- Over Quota URL -->
                            <div class="col-md-6">
                                <label for="over_quota_url" class="form-label">Over Quota Redirect URL</label>
                                <input type="url" id="over_quota_url" name="over_quota_url" class="form-control @error('over_quota_url') is-invalid @enderror"
                                       value="{{ old('over_quota_url', $mapping->over_quota_url ?? $vendor->quote_full_redirect_url) }}">
                                @error('over_quota_url') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="text-muted">Enter the over quota redirect URL manually</small>
                                @if($vendor->quote_full_redirect_url)
                                    <small class="text-info d-block">Vendor default: {{ $vendor->quote_full_redirect_url }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Update Vendor Mapping</button>
                                <a href="{{ route('projects.edit', $project->id) }}#addVendor" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-generate URLs based on the original pattern
function generateLiveUrl() {
    const projectId = '{{ $project->projectID }}';
    const vendorId = '{{ $vendor->vendor_id }}';
    const baseUrl = '{{ url("/survey") }}';
    const liveUrl = `${baseUrl}?pid=${projectId}&vid=${vendorId}&toid=[TOID]`;
    
    document.getElementById('study_url').value = liveUrl;
    
    // Show success message
    showToast('Live URL generated successfully!', 'success');
}

// Auto-generate URLs on page load if fields are empty
document.addEventListener('DOMContentLoaded', function() {
    const liveUrlField = document.getElementById('study_url');
    
    // Auto-generate if field is empty
    if (!liveUrlField.value) {
        generateLiveUrl();
    }
});

// Simple toast notification function
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}
</script>
@endsection
