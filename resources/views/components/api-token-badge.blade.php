{{--
    API Token Badge Component
    Usage: <x-api-token-badge :token="$apiToken" :user-id="$userId" />
    NOTE: Sanctum never stores plaintext tokens — only SHA-256 hashes.
    The "Get Test Token" button issues a new *additional* token (named admin-swagger-testing)
    without revoking the user's active sessions. Safe for Swagger/Postman testing.
--}}
@props(['token' => null, 'userId' => null])

@php
    $hasToken = !is_null($token);
    $tokenName = $hasToken ? $token->name : null;
    $tokenCreated = $hasToken ? $token->created_at->format('d M Y, h:i A') : null;
    $tokenLastUsed = $hasToken ? ($token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never used') : null;
@endphp

{{-- ─── Trigger Badge ────────────────────────────────────────── --}}
<span
    data-bs-toggle="modal"
    data-bs-target="#apiTokenModal_{{ $userId }}"
    style="cursor:pointer;"
    title="{{ $hasToken ? 'View API Token' : 'No active token found' }}"
    class="badge fw-semibold fs-9 px-3 py-2 d-inline-flex align-items-center gap-1 hover-scale
           {{ $hasToken
               ? 'badge-light-success border border-success text-success'
               : 'badge-light-warning border border-warning text-warning' }}"
>
    @if($hasToken)
        <i class="ki-outline ki-shield-tick fs-8 text-success"></i> Token Active
    @else
        <i class="ki-outline ki-shield-cross fs-8 text-warning"></i> No Token
    @endif
</span>

{{-- ─── Modal ────────────────────────────────────────────────── --}}
<div class="modal fade" id="apiTokenModal_{{ $userId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            {{-- Header --}}
            <div class="modal-header px-7 py-5 border-0" style="background:linear-gradient(135deg,#0d1527 0%,#16213e 60%,#0f3460 100%); border-bottom: 1px solid rgba(255,255,255,0.08) !important;">
                <div class="d-flex align-items-center gap-3 w-100">
                    <div class="symbol symbol-40px">
                        <span class="symbol-label" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);">
                            <i class="ki-outline ki-key fs-2 text-white"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-white fs-5 lh-1 mb-1">API Access Token</div>
                        <div class="text-white opacity-50 fs-9 fw-medium">Generate a test token for Swagger / Postman</div>
                    </div>
                    <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal" style="color:rgba(255,255,255,0.6);">
                        <i class="ki-outline ki-cross fs-3"></i>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="modal-body px-7 py-6 bg-body">

                @if($hasToken)
                    {{-- Status & Meta --}}
                    <div class="d-flex align-items-center gap-3 p-4 mb-5 rounded bg-light-success border border-success border-dashed">
                        <span class="bullet bullet-dot bg-success h-12px w-12px animate-pulse"></span>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-success fs-7">Active Session Exists</div>
                            <div class="text-muted fs-9">Token: <span class="fw-bold text-gray-700">{{ $tokenName }}</span></div>
                        </div>
                        <span class="badge badge-success py-1 px-3 fs-9">Active</span>
                    </div>

                    {{-- Meta Stats --}}
                    <div class="row g-3 mb-5">
                        <div class="col-6">
                            <div class="bg-light rounded px-4 py-3 border border-gray-200 h-100">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="ki-outline ki-calendar fs-7 text-primary"></i>
                                    <span class="text-muted fs-9 fw-semibold text-uppercase">Created</span>
                                </div>
                                <div class="fw-bold text-gray-800 fs-8">{{ $tokenCreated }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded px-4 py-3 border border-gray-200 h-100">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="ki-outline ki-time fs-7 text-info"></i>
                                    <span class="text-muted fs-9 fw-semibold text-uppercase">Last Used</span>
                                </div>
                                <div class="fw-bold text-gray-800 fs-8">{{ $tokenLastUsed }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Info: why we can't show existing token --}}
                <div class="alert bg-light-info border border-info border-dashed d-flex p-4 mb-5 rounded-3">
                    <i class="ki-outline ki-information-5 fs-2hx text-info me-4 mt-1"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-info fw-bold fs-7">How this works</h4>
                        <span class="text-gray-700 fw-medium fs-8 lh-base">
                            Sanctum stores tokens as hashes — the plaintext is only shown once at login.
                            Click <strong>"Get Test Token"</strong> to generate a fresh token for Swagger/Postman.
                            The user's active sessions will <strong>not</strong> be affected.
                        </span>
                    </div>
                </div>

                {{-- Security Warning --}}
                <div class="alert bg-light-warning border border-warning border-dashed d-flex p-4 mb-5 rounded-3">
                    <i class="ki-outline ki-shield-cross fs-2hx text-warning me-4 mt-1"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-warning fw-bold fs-7">Keep Token Private</h4>
                        <span class="text-gray-700 fw-medium fs-8 lh-base">
                            Do not share this token. Anyone with it can fully authenticate as this user.
                        </span>
                    </div>
                </div>

                {{-- Token Display Area (hidden until generated) --}}
                <div id="tokenDisplayArea_{{ $userId }}" class="d-none mb-4 p-4 bg-light-dark border border-gray-300 rounded-3">
                    <label class="fw-bold text-gray-800 fs-8 mb-2 d-block d-flex align-items-center gap-1">
                        <i class="ki-outline ki-shield-tick fs-7 text-success"></i>
                        Bearer Token — Ready to use
                    </label>
                    <div class="input-group input-group-solid">
                        <input
                            type="text"
                            id="fullTokenInput_{{ $userId }}"
                            class="form-control form-control-sm bg-white border-gray-300 text-gray-800 fw-bold fs-8 font-monospace shadow-sm"
                            value=""
                            readonly
                            style="letter-spacing:0.05em;"
                        >
                        <button
                            class="btn btn-dark btn-sm px-4 fw-bold shadow-sm"
                            id="copyFullTokenBtn_{{ $userId }}"
                            onclick="copyFullToken('{{ $userId }}')"
                            type="button"
                        >
                            <i class="ki-outline ki-copy fs-6 me-1"></i>Copy
                        </button>
                    </div>
                    <div class="text-success fs-9 fw-bold mt-2 d-none d-flex align-items-center gap-1" id="copySuccess_{{ $userId }}">
                        <i class="ki-outline ki-check-circle fs-8 text-success"></i> Copied! Paste it in Swagger / Postman as Bearer token.
                    </div>
                </div>

                {{-- Generate Button --}}
                <button
                    type="button"
                    class="btn btn-primary w-100 fw-bold"
                    id="generateTokenBtn_{{ $userId }}"
                    onclick="generateTestToken('{{ $userId }}')"
                >
                    <i class="ki-outline ki-key fs-5 me-2"></i>
                    <span id="generateTokenBtnText_{{ $userId }}">Get Test Token</span>
                </button>

            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-light border-top border-gray-200 px-7 py-4">
                <button type="button" class="btn btn-sm btn-light border border-gray-300 fw-bold" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function generateTestToken(userId) {
    const btn     = document.getElementById('generateTokenBtn_' + userId);
    const btnText = document.getElementById('generateTokenBtnText_' + userId);
    const area    = document.getElementById('tokenDisplayArea_' + userId);
    const input   = document.getElementById('fullTokenInput_' + userId);

    // Loading state
    btn.disabled = true;
    btnText.textContent = 'Generating...';

    fetch('/admin/api-tokens/' + userId + '/issue', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.token) {
            input.value = data.token;
            area.classList.remove('d-none');
            btnText.textContent = 'Regenerate Token';
        } else {
            alert('Failed to generate token. Please try again.');
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
    })
    .finally(() => {
        btn.disabled = false;
    });
}

function copyFullToken(userId) {
    const input   = document.getElementById('fullTokenInput_' + userId);
    const success = document.getElementById('copySuccess_' + userId);
    const btn     = document.getElementById('copyFullTokenBtn_' + userId);

    if (!input.value) return;

    navigator.clipboard.writeText(input.value).then(() => {
        success.classList.remove('d-none');
        btn.innerHTML = '<i class="ki-outline ki-check fs-6 me-1"></i>Copied!';
        btn.classList.replace('btn-dark', 'btn-success');

        setTimeout(() => {
            success.classList.add('d-none');
            btn.innerHTML = '<i class="ki-outline ki-copy fs-6 me-1"></i>Copy';
            btn.classList.replace('btn-success', 'btn-dark');
        }, 2500);
    }).catch(() => {
        alert('Copy failed. Please select and copy manually.');
    });
}
</script>
@endpush
@endonce
