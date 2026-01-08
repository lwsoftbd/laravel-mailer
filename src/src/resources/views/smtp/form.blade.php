<div class="row mb-3">
    <div class="col-md-6">
        <label>SMTP Host</label>
        <input type="text" name="host" class="form-control"
               value="{{ old('host', $smtp->host ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label>Port</label>
        <input type="number" name="port" id="smtp_port" class="form-control"
               value="{{ old('port', $smtp->port ?? 25) }}" required>
    </div>

    <div class="col-md-3">
        <label>Encryption</label>
        <select name="encryption" id="smtp_encryption" class="form-control">
            <option value="">None</option>
            <option value="tls" @selected(old('encryption', $smtp->encryption ?? '')=='tls')>TLS</option>
            <option value="ssl" @selected(old('encryption', $smtp->encryption ?? '')=='ssl')>SSL</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label>Username</label>
        <input type="text" name="username" class="form-control"
               value="{{ old('username', $smtp->username ?? '') }}">
    </div>

    <div class="col-md-6">
        <label>Password</label>
        <input type="password" name="password" class="form-control"
            @if(isset($smtp))
                placeholder="Leave blank to keep unchanged"
            @else
                placeholder="Enter SMTP password"
            @endif
        >
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label>From Email</label>
        <input type="email" name="from_address" class="form-control"
               value="{{ old('from_address', $smtp->from_address ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label>From Name</label>
        <input type="text" name="from_name" class="form-control"
               value="{{ old('from_name', $smtp->from_name ?? '') }}" required>
    </div>
</div>

{{-- 🔹 JS for auto-port --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const encryption = document.getElementById('smtp_encryption');
    const port = document.getElementById('smtp_port');

    const portMap = {
        'tls': 587,
        'ssl': 465,
        '': 25
    };

    encryption.addEventListener('change', function() {
        const value = encryption.value;
        if (!port.dataset.userSet) {
            port.value = portMap[value] ?? 25;
        }
    });

    // Detect if user manually edits port
    port.addEventListener('input', function() {
        port.dataset.userSet = true;
    });
});
</script>
@endpush