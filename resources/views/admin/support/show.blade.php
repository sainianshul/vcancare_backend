@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->reference_id)

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Support', 'url' => route('admin.support.index')],
        ['label' => 'View Ticket: ' . $ticket->reference_id],
    ]" />

    <div class="d-flex flex-column gap-5">

        {{-- ── HEADER ───────────────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.support.index') }}" class="btn btn-icon btn-light btn-active-secondary btn-sm">
                    <i class="ki-outline ki-arrow-left fs-4"></i>
                </a>
                <h1 class="fw-bold text-gray-900 fs-4 mb-0">
                    Ticket <span class="text-primary">#{{ $ticket->reference_id }}</span>
                </h1>
                <span class="badge badge-light-{{ $ticket->status_color }} fs-8 px-3 py-1 border border-{{ $ticket->status_color }}">
                    {{ $ticket->status_text }}
                </span>
            </div>
        </div>

        <div class="row g-5">
            {{-- ── LEFT COLUMN (CHAT) ───────────────────────────────────────── --}}
            <div class="col-lg-8">
                <div class="card border border-gray-200" id="kt_chat_messenger">
                    <!-- Card header -->
                    <div class="card-header border-0 pt-4 pb-2" id="kt_chat_messenger_header">
                        <div class="card-title w-100">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex flex-column">
                                    <span class="fs-4 fw-bold text-gray-900 mb-2">{{ $ticket->subject ?? 'Support Ticket' }}</span>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge badge-light-primary text-uppercase fw-bold fs-8 py-1 px-3 border border-primary"><i class="ki-outline ki-category fs-8 text-primary me-1"></i> {{ $ticket->category ?? 'General' }}</span>
                                        <span class="text-muted fw-semibold fs-7"><i class="ki-outline ki-time text-muted fs-7 me-1"></i> Opened {{ $ticket->created_at ? $ticket->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="separator separator-dashed border-gray-200"></div>

                    <!-- Card body -->
                    <div class="card-body pt-5" id="kt_chat_messenger_body">
                        <div class="scroll-y me-n5 pe-5 h-400px h-lg-auto" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer" data-kt-scroll-wrappers="#kt_content, #kt_chat_messenger_body" data-kt-scroll-offset="5px" style="max-height: 500px;">
                            
                            @forelse($ticket->messages as $msg)
                                @if($msg->is_admin)
                                    <!-- Outgoing Message (Admin) -->
                                    <div class="d-flex justify-content-end mb-7">
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="me-3">
                                                    <span class="text-muted fs-8 me-2">{{ $msg->created_at ? $msg->created_at->format('d M Y, h:i A') : '' }}</span>
                                                    <span class="fs-6 fw-bold text-gray-900">You (Admin)</span>
                                                </div>
                                                <div class="symbol symbol-35px symbol-circle">
                                                    <span class="symbol-label bg-light-primary text-primary fs-5 fw-bold">A</span>
                                                </div>
                                            </div>
                                            <div class="p-3 rounded bg-light-primary text-dark fw-medium fs-7 mw-lg-400px text-end" data-kt-element="message-text">
                                                {!! nl2br(e($msg->message)) !!}
                                                
                                                @if(!empty($msg->attachments))
                                                    <div class="d-flex flex-wrap gap-2 mt-2 justify-content-end">
                                                        @foreach($msg->attachments as $att)
                                                            <a href="{{ Storage::url($att) }}" target="_blank" class="btn btn-sm btn-light-primary btn-active-light-primary px-2 py-1 fs-8 fw-medium">
                                                                <i class="ki-outline ki-file fs-7 me-1"></i> Attachment
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Incoming Message (User) -->
                                    <div class="d-flex justify-content-start mb-7">
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="symbol symbol-35px symbol-circle me-3">
                                                    @if(isset($msg->user) && $msg->user->profile_photo)
                                                        <img src="{{ Storage::url($msg->user->profile_photo) }}" alt="Pic" class="object-fit-cover" />
                                                    @else
                                                        <span class="symbol-label bg-light-info text-info fs-5 fw-bold">
                                                            {{ mb_strtoupper(mb_substr($msg->user->name ?? 'U', 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="fs-6 fw-bold text-gray-900 me-2">{{ $msg->user->name ?? 'User' }}</span>
                                                    <span class="text-muted fs-8">{{ $msg->created_at ? $msg->created_at->format('d M Y, h:i A') : '' }}</span>
                                                </div>
                                            </div>
                                            <div class="p-3 rounded bg-light text-dark fw-medium fs-7 mw-lg-400px text-start border border-gray-200" data-kt-element="message-text">
                                                {!! nl2br(e($msg->message)) !!}

                                                @if(!empty($msg->attachments))
                                                    <div class="d-flex flex-wrap gap-2 mt-2 justify-content-start">
                                                        @foreach($msg->attachments as $att)
                                                            <a href="{{ Storage::url($att) }}" target="_blank" class="btn btn-sm btn-light-info btn-active-light-info px-2 py-1 fs-8 fw-medium">
                                                                <i class="ki-outline ki-file fs-7 me-1"></i> Attachment
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center text-muted fs-7 py-5">No messages found.</div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="separator separator-dashed border-gray-200"></div>

                    <!-- Card footer -->
                    <div class="card-footer pt-3 pb-4" id="kt_chat_messenger_footer">
                        @if(!$ticket->isClosed())
                            <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <textarea class="form-control form-control-solid mb-3 text-gray-800 border border-gray-200 rounded p-3 fs-7" rows="2" data-kt-element="input" placeholder="Type your reply here..." name="message" required></textarea>
                                <div class="d-flex flex-stack">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-icon btn-light-primary me-2" type="button" data-bs-toggle="tooltip" title="Attach Files" onclick="document.getElementById('attachments').click()">
                                            <i class="ki-outline ki-paper-clip fs-5"></i>
                                        </button>
                                        <input type="file" id="attachments" name="attachments[]" multiple class="d-none">
                                        <span class="text-muted fs-8 fw-medium" id="attachment-count"></span>
                                    </div>
                                    <button class="btn btn-primary btn-sm fw-semibold fs-8" type="submit"><i class="ki-outline ki-send fs-6 me-1"></i> Send Reply</button>
                                </div>
                            </form>
                        @else
                            <div class="alert bg-light-warning border border-warning d-flex align-items-center p-3 mb-0">
                                <i class="ki-outline ki-information-5 fs-3 text-warning me-3"></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <span class="fs-7 text-gray-800 fw-medium">This support ticket is closed. Change the status to open it before replying.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN (DETAILS & USER) ────────────────────────────── --}}
            <div class="col-lg-4">
                {{-- Ticket Info Card --}}
                <div class="card border border-gray-200 mb-5">
                    <div class="card-header border-0 pt-4 pb-2 min-h-40px">
                        <h3 class="card-title align-items-center m-0">
                            <i class="ki-outline ki-information fs-3 text-primary me-2"></i>
                            <span class="card-label fw-bold fs-5 text-gray-900">Ticket Settings</span>
                        </h3>
                    </div>
                    
                    <div class="separator separator-dashed border-gray-200 mx-5"></div>

                    <div class="card-body pt-4 pb-5">
                        <div class="d-flex flex-column gap-4">
                            
                            {{-- Status Updater --}}
                            <div class="d-flex flex-column">
                                <span class="fw-medium text-gray-700 fs-8 mb-1 text-uppercase">Current Status</span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light-{{ $ticket->status_color }} border border-{{ $ticket->status_color }} dropdown-toggle w-100 fw-medium fs-7 d-flex justify-content-between align-items-center px-3 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span>{{ $ticket->status_text }}</span>
                                    </button>
                                    <ul class="dropdown-menu w-100 py-2">
                                        @foreach(\App\Models\SupportTicket::getStatusList() as $val => $label)
                                            <li>
                                                <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ $val }}">
                                                    <button type="submit" class="dropdown-item fw-medium fs-7 d-flex align-items-center {{ $ticket->status == $val ? 'active' : '' }}">
                                                        {{ $label }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="separator separator-dashed border-gray-200"></div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-chart-line fs-3 text-gray-600 me-2"></i>
                                    <span class="fw-semibold text-gray-600 fs-7">Priority</span>
                                </div>
                                <span class="badge badge-light-{{ $ticket->priority == 1 ? 'success' : ($ticket->priority == 2 ? 'warning' : 'danger') }} border border-{{ $ticket->priority == 1 ? 'success' : ($ticket->priority == 2 ? 'warning' : 'danger') }} px-3 py-1 fw-bold fs-7">{{ $ticket->priority_text }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-calendar-8 fs-3 text-gray-600 me-2"></i>
                                    <span class="fw-semibold text-gray-600 fs-7">Created At</span>
                                </div>
                                <span class="text-gray-900 fw-bold fs-6">{{ $ticket->created_at ? $ticket->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                            </div>

                            @if($ticket->resolved_at)
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-check-circle fs-3 text-success me-2"></i>
                                        <span class="fw-semibold text-gray-600 fs-7">Resolved At</span>
                                    </div>
                                    <span class="text-gray-900 fw-bold fs-6">{{ $ticket->resolved_at->format('d M Y, h:i A') }}</span>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- User Card --}}
                <div class="card border border-gray-200 mb-5">
                    <div class="card-header border-0 pt-4 pb-2 min-h-40px">
                        <h3 class="card-title align-items-center m-0">
                            <i class="ki-outline ki-profile-circle fs-3 text-info me-2"></i>
                            <span class="card-label fw-bold fs-5 text-gray-900">User Profile</span>
                        </h3>
                    </div>

                    <div class="separator separator-dashed border-gray-200 mx-5"></div>

                    <div class="card-body pt-4 pb-5">
                        @if($ticket->user)
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px symbol-circle me-4 border border-gray-200">
                                    @if($ticket->user->profile_photo)
                                        <img src="{{ Storage::url($ticket->user->profile_photo) }}" alt="{{ $ticket->user->name }}" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-light-primary text-primary fs-3 fw-bold">
                                            {{ mb_strtoupper(mb_substr($ticket->user->name ?? 'U', 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fs-5 text-gray-900 fw-bold mb-1">
                                        {{ $ticket->user->name ?? 'Unknown' }}
                                    </span>
                                    <div class="fs-7 fw-semibold text-gray-600">ID: {{ $ticket->user->id }} • {{ $ticket->user->role == 1 ? 'Patient' : 'Nurse' }}</div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column gap-3 bg-light rounded p-4 border border-gray-200">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-sms fs-4 text-gray-600 me-3"></i>
                                    <div class="fs-7 text-gray-800 fw-semibold">{{ $ticket->user->email ?? 'No email provided' }}</div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-phone fs-4 text-gray-600 me-3"></i>
                                    <div class="fs-7 text-gray-800 fw-semibold">{{ $ticket->user->phone ?? 'No phone provided' }}</div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                @if($ticket->user->role == 1)
                                    <a href="{{ route('admin.patients.show', $ticket->user->id) }}"
                                        class="btn btn-light-primary border border-primary w-100 fw-medium fs-8 px-3 py-2">
                                        View Profile <i class="ki-outline ki-arrow-right fs-7 ms-1"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.nurses.show', $ticket->user->id) }}"
                                        class="btn btn-light-info border border-info w-100 fw-medium fs-8 px-3 py-2">
                                        View Profile <i class="ki-outline ki-arrow-right fs-7 ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center text-center p-3">
                                <i class="ki-outline ki-user-cross fs-2x text-muted mb-2"></i>
                                <div class="text-gray-500 fs-8 fw-medium">User profile is missing.</div>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>

                {{-- Comments Component --}}
                <x-comments type="{{ \App\Models\SupportTicket::class }}" :model-id="$ticket->id" />

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('attachments').addEventListener('change', function(e) {
        var count = e.target.files.length;
        var el = document.getElementById('attachment-count');
        if(count > 0) {
            el.innerHTML = '<span class="badge badge-light-primary border border-primary px-2 py-1 fs-9"><i class="ki-outline ki-paper-clip fs-8 me-1"></i> ' + count + ' attached</span>';
        } else {
            el.innerHTML = '';
        }
    });
</script>
@endpush
