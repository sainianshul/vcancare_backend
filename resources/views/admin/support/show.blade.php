@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->reference_id)

@section('content')

    <x-breadcrumb :items="[
        ['label' => 'Support', 'url' => route('admin.support.index')],
        ['label' => 'View Ticket: ' . $ticket->reference_id],
    ]" />

    <div class="d-flex flex-column gap-7 gap-lg-10">

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
                <span class="badge badge-light-{{ $ticket->priority == 1 ? 'success' : ($ticket->priority == 2 ? 'warning' : 'danger') }} fs-8 px-3 py-1 border border-{{ $ticket->priority == 1 ? 'success' : ($ticket->priority == 2 ? 'warning' : 'danger') }}">
                    Priority: {{ $ticket->priority_text }}
                </span>
            </div>
        </div>

        <div class="row g-7">
            {{-- ── LEFT COLUMN (CHAT) ───────────────────────────────────────── --}}
            <div class="col-lg-8">
                <div class="card mb-7 border border-gray-300" id="kt_chat_messenger" style="box-shadow: none;">
                    <!-- Card header -->
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-4 min-h-50px" id="kt_chat_messenger_header">
                        <div class="card-title w-100 m-0">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex flex-column">
                                    <span class="fs-5 fw-bold text-gray-900 mb-2">{{ $ticket->subject ?? 'Support Ticket' }}</span>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge badge-light-primary text-uppercase fw-bold fs-9 py-1 px-2 border border-primary"><i class="ki-outline ki-category fs-9 text-primary me-1"></i> {{ $ticket->category ?? 'General' }}</span>
                                        <span class="text-dark fw-normal fs-8"><i class="ki-outline ki-time text-dark fs-8 me-1"></i> Opened {{ $ticket->created_at ? $ticket->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card body -->
                    <div class="card-body pt-5 bg-white" id="kt_chat_messenger_body">
                        <div class="scroll-y me-n5 pe-5 h-400px h-lg-auto" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer" data-kt-scroll-wrappers="#kt_content, #kt_chat_messenger_body" data-kt-scroll-offset="5px" style="max-height: 500px;">
                            
                            @forelse($ticket->messages as $msg)
                                @if($msg->is_admin)
                                    <!-- Outgoing Message (Admin) -->
                                    <div class="d-flex justify-content-end mb-5">
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-3">
                                                    <span class="text-muted fs-9 me-2">{{ $msg->created_at ? $msg->created_at->format('d M Y, h:i A') : '' }}</span>
                                                    <span class="fs-7 fw-semibold text-gray-900">You (Admin)</span>
                                                </div>
                                                <div class="symbol symbol-30px symbol-circle border border-primary">
                                                    <span class="symbol-label bg-light-primary text-primary fs-7 fw-bold">A</span>
                                                </div>
                                            </div>
                                            <div class="p-4 bg-light-primary text-dark fw-medium fs-7 mw-lg-400px text-start border border-primary border-opacity-25" data-kt-element="message-text" style="border-radius: 16px; border-top-right-radius: 4px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.02);">
                                                {!! nl2br(e($msg->message)) !!}
                                                
                                                @if(!empty($msg->attachments))
                                                    <div class="d-flex flex-wrap gap-2 mt-3 justify-content-start">
                                                        @foreach($msg->attachments as $att)
                                                            @php
                                                                $ext = pathinfo($att, PATHINFO_EXTENSION);
                                                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                            @endphp
                                                            @if($isImage)
                                                                <a href="{{ Storage::url($att) }}" target="_blank" class="d-block border border-primary border-opacity-25" style="border-radius: 8px; overflow: hidden;">
                                                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover min-h-80px min-w-80px" style="background-image:url('{{ Storage::url($att) }}');"></div>
                                                                </a>
                                                            @else
                                                                <a href="{{ Storage::url($att) }}" target="_blank" class="d-flex align-items-center bg-white border border-primary border-opacity-50 px-3 py-2 text-dark text-hover-primary text-decoration-none" style="border-radius: 8px;">
                                                                    <i class="ki-outline ki-file fs-4 me-2 text-primary"></i>
                                                                    <span class="fs-8 fw-semibold text-dark">Attachment</span>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Incoming Message (User) -->
                                    <div class="d-flex justify-content-start mb-5">
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="symbol symbol-30px symbol-circle me-3 border border-gray-300">
                                                    @if(isset($msg->user) && $msg->user->profile_photo)
                                                        <img src="{{ Storage::url($msg->user->profile_photo) }}" alt="Pic" class="object-fit-cover" />
                                                    @else
                                                        <span class="symbol-label bg-white text-dark fs-7 fw-bold">
                                                            {{ mb_strtoupper(mb_substr($msg->user->name ?? 'U', 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="fs-7 fw-semibold text-gray-900 me-2">{{ $msg->user->name ?? 'User' }}</span>
                                                    <span class="text-muted fs-9">{{ $msg->created_at ? $msg->created_at->format('d M Y, h:i A') : '' }}</span>
                                                </div>
                                            </div>
                                            <div class="p-4 bg-light text-dark fw-medium fs-7 mw-lg-400px text-start border border-gray-200" data-kt-element="message-text" style="border-radius: 16px; border-top-left-radius: 4px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.02);">
                                                {!! nl2br(e($msg->message)) !!}

                                                @if(!empty($msg->attachments))
                                                    <div class="d-flex flex-wrap gap-2 mt-3 justify-content-start">
                                                        @foreach($msg->attachments as $att)
                                                            @php
                                                                $ext = pathinfo($att, PATHINFO_EXTENSION);
                                                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                            @endphp
                                                            @if($isImage)
                                                                <a href="{{ Storage::url($att) }}" target="_blank" class="d-block border border-gray-300" style="border-radius: 8px; overflow: hidden;">
                                                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover min-h-80px min-w-80px" style="background-image:url('{{ Storage::url($att) }}');"></div>
                                                                </a>
                                                            @else
                                                                <a href="{{ Storage::url($att) }}" target="_blank" class="d-flex align-items-center bg-white border border-gray-300 px-3 py-2 text-dark text-hover-primary text-decoration-none" style="border-radius: 8px;">
                                                                    <i class="ki-outline ki-file fs-4 me-2 text-dark"></i>
                                                                    <span class="fs-8 fw-semibold text-dark">Attachment</span>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center text-muted fs-7 py-5">
                                    <i class="ki-outline ki-message-text-2 fs-2x text-muted mb-2"></i>
                                    <div class="fw-medium">No messages found.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Card footer -->
                    <div class="card-footer pt-3 pb-4 border-top border-gray-200 bg-white" id="kt_chat_messenger_footer">
                        @if(!$ticket->isClosed())
                            <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <textarea class="form-control form-control-solid mb-3 text-gray-900 border border-gray-300 rounded p-3 fs-7 bg-white" rows="2" data-kt-element="input" placeholder="Type your reply here..." name="message" required style="border-radius: 8px;"></textarea>
                                <div class="d-flex flex-stack">
                                    <div class="d-flex align-items-center">
                                        <span class="text-dark fs-8 fw-normal" id="attachment-count"></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-icon btn-light border border-gray-300 me-2" type="button" data-bs-toggle="tooltip" title="Attach Files" onclick="document.getElementById('attachments').click()">
                                            <i class="ki-outline ki-paper-clip fs-5 text-dark"></i>
                                        </button>
                                        <input type="file" id="attachments" name="attachments[]" multiple class="d-none">
                                        
                                        <button class="btn btn-primary btn-sm fw-semibold px-4" type="submit" style="border-radius: 8px;"><i class="ki-outline ki-send fs-7 me-2"></i> Send Reply</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="alert bg-white border border-warning d-flex align-items-center p-3 mb-0">
                                <i class="ki-outline ki-information-5 fs-2 text-warning me-3"></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <span class="fs-7 text-gray-900 fw-bold mb-1">Ticket Closed</span>
                                    <span class="fs-8 text-gray-800 fw-normal">This support ticket is closed. Change the status to open it before replying.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Comments Component --}}
                <x-comments type="{{ \App\Models\SupportTicket::class }}" :model-id="$ticket->id" />
            </div>

            {{-- ── RIGHT COLUMN (DETAILS & USER) ────────────────────────────── --}}
            <div class="col-lg-4">
                
                {{-- Ticket Info Card --}}
                <div class="card mb-7 border border-gray-300" style="box-shadow: none;">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-4 min-h-40px">
                        <h3 class="card-title align-items-center m-0">
                            <span class="card-label fw-bold fs-6 text-gray-900">Ticket Settings</span>
                        </h3>
                    </div>
                    
                    <div class="card-body pt-4 pb-5">
                        <div class="d-flex flex-column gap-5">
                            
                            {{-- Status Updater --}}
                            <div class="d-flex flex-column">
                                <span class="text-dark fw-normal fs-8 mb-2">Current Status</span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light-{{ $ticket->status_color }} border border-{{ $ticket->status_color }} dropdown-toggle w-100 fw-semibold fs-7 d-flex justify-content-between align-items-center px-3 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span>{{ $ticket->status_text }}</span>
                                    </button>
                                    <ul class="dropdown-menu w-100 py-2">
                                        @foreach(\App\Models\SupportTicket::getStatusList() as $val => $label)
                                            <li>
                                                <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ $val }}">
                                                    <button type="submit" class="dropdown-item fw-normal fs-7 py-2 {{ $ticket->status == $val ? 'active' : '' }}">
                                                        {{ $label }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="separator separator-dashed border-gray-300 my-1"></div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark fw-normal fs-7">Priority</span>
                                </div>
                                <span class="badge badge-light-{{ $ticket->priority == 1 ? 'success' : ($ticket->priority == 2 ? 'warning' : 'danger') }} border border-{{ $ticket->priority == 1 ? 'success' : ($ticket->priority == 2 ? 'warning' : 'danger') }} px-2 py-1 fw-semibold fs-8">{{ $ticket->priority_text }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark fw-normal fs-7">Created At</span>
                                </div>
                                <span class="text-gray-900 fw-medium fs-7">{{ $ticket->created_at ? $ticket->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                            </div>

                            @if($ticket->resolved_at)
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="text-dark fw-normal fs-7">Resolved At</span>
                                    </div>
                                    <span class="text-gray-900 fw-medium fs-7">{{ $ticket->resolved_at->format('d M Y, h:i A') }}</span>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- User Profile Card --}}
                <div class="card mb-7 border border-gray-300" style="box-shadow: none;">
                    <div class="card-header border-bottom border-gray-200 pt-4 pb-4 min-h-40px">
                        <h3 class="card-title align-items-center m-0">
                            <span class="card-label fw-bold fs-6 text-gray-900">User Profile</span>
                        </h3>
                    </div>

                    <div class="card-body pt-5 pb-5">
                        @if($ticket->user)
                            <div class="d-flex flex-center flex-column mb-4">
                                <div class="symbol symbol-60px symbol-circle mb-3 border border-gray-300">
                                    @if($ticket->user->profile_photo)
                                        <img src="{{ Storage::url($ticket->user->profile_photo) }}" alt="{{ $ticket->user->name }}" class="object-fit-cover" />
                                    @else
                                        <span class="symbol-label bg-white text-dark fs-2 fw-semibold">
                                            {{ mb_strtoupper(mb_substr($ticket->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ $ticket->user->role == 1 ? route('admin.patients.show', $ticket->user->id) : route('admin.nurses.show', $ticket->user->id) }}"
                                    class="fs-6 text-gray-900 text-hover-primary fw-bold mb-1">
                                    {{ $ticket->user->name ?? 'Unknown' }}
                                </a>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="fs-8 fw-normal text-dark">ID: {{ $ticket->user->id }}</span>
                                    @if($ticket->user->role == 1)
                                        <span class="badge badge-light-success px-2 py-1 fs-9 fw-semibold border border-success">Patient</span>
                                    @else
                                        <span class="badge badge-light-info px-2 py-1 fs-9 fw-semibold border border-info">Nurse</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="separator separator-dashed border-gray-300 my-4"></div>

                            <div class="d-flex flex-column gap-3 mb-5">
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-sms fs-5 text-dark me-3"></i>
                                    <div class="fs-7 text-gray-900 fw-normal">{{ $ticket->user->email ?? 'No email provided' }}</div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-phone fs-5 text-dark me-3"></i>
                                    <div class="fs-7 text-gray-900 fw-normal">{{ $ticket->user->phone ?? 'No phone provided' }}</div>
                                </div>
                            </div>
                            
                            <div>
                                @if($ticket->user->role == 1)
                                    <div class="d-flex gap-2 mb-2">
                                        <button class="btn btn-sm btn-light-success border border-success w-100 fw-medium fs-8" data-bs-toggle="tooltip" title="Send SMS">
                                            <i class="ki-outline ki-sms fs-7 me-1"></i> SMS
                                        </button>
                                        <button class="btn btn-sm btn-light-warning border border-warning w-100 fw-medium fs-8" data-bs-toggle="tooltip" title="Send Notification">
                                            <i class="ki-outline ki-notification-on fs-7 me-1"></i> Notify
                                        </button>
                                    </div>
                                    <a href="{{ route('admin.patients.show', $ticket->user->id) }}"
                                        class="btn btn-light-primary border border-primary text-primary w-100 fw-bold fs-7 px-4 py-2 mt-2">
                                        View Profile
                                    </a>
                                @else
                                    <div class="d-flex gap-2 mb-2">
                                        <button class="btn btn-sm btn-light-primary border border-primary w-100 fw-medium fs-8" data-bs-toggle="tooltip" title="Send Email">
                                            <i class="ki-outline ki-directbox-default fs-7 me-1"></i> Email
                                        </button>
                                        <button class="btn btn-sm btn-light-success border border-success w-100 fw-medium fs-8" data-bs-toggle="tooltip" title="Send SMS">
                                            <i class="ki-outline ki-sms fs-7 me-1"></i> SMS
                                        </button>
                                    </div>
                                    <a href="{{ route('admin.nurses.show', $ticket->user->id) }}"
                                        class="btn btn-light-primary border border-primary text-primary w-100 fw-bold fs-7 px-4 py-2 mt-2">
                                        View Profile
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center text-center p-3">
                                <i class="ki-outline ki-user-cross fs-2x text-dark mb-2"></i>
                                <div class="text-dark fs-8 fw-normal">User profile is missing.</div>
                            </div>
                        @endif
                    </div>
                </div>

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
            el.innerHTML = '<span class="badge badge-light border border-gray-300 text-dark px-2 py-1 fs-9"><i class="ki-outline ki-paper-clip fs-8 me-1"></i> ' + count + ' file(s)</span>';
        } else {
            el.innerHTML = '';
        }
    });
</script>
@endpush
