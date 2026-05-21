@extends('admin.layouts.app')

@section('title', 'Ticket #' . $ticket->reference_id)

@section('content')
<div class="d-flex flex-column flex-xl-row">
    <!-- Sidebar -->
    <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
        <div class="card card-flush shadow-sm" data-kt-sticky="true" data-kt-sticky-name="account-navbar" data-kt-sticky-offset="{default: false, xl: '80px'}" data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto" data-kt-sticky-top="80px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
            <div class="card-header border-0 pt-6">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Ticket Details</h3>
                </div>
            </div>
            <div class="card-body pt-4">
                <div class="d-flex align-items-center mb-7">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-primary text-primary fw-bold fs-2x">
                            {{ mb_strtoupper(mb_substr($ticket->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold fs-5 text-gray-900">{{ $ticket->user->name }}</span>
                        <span class="fs-7 text-muted">{{ $ticket->user->email }}</span>
                        <span class="badge badge-light-primary fw-bold fs-8 mt-1">{{ $ticket->user->role == 1 ? 'Patient' : 'Nurse' }}</span>
                    </div>
                </div>

                <div class="separator separator-dashed mb-7"></div>

                <div class="d-flex flex-column mb-5">
                    <span class="fw-bold text-gray-800 fs-6 mb-1">Status</span>
                    <div>
                        <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select form-select-sm form-select-solid mt-1" onchange="this.form.submit()">
                                <option value="0" {{ $ticket->status == 0 ? 'selected' : '' }}>Open</option>
                                <option value="1" {{ $ticket->status == 1 ? 'selected' : '' }}>In Progress</option>
                                <option value="2" {{ $ticket->status == 2 ? 'selected' : '' }}>Resolved</option>
                                <option value="3" {{ $ticket->status == 3 ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="d-flex flex-column mb-5">
                    <span class="fw-bold text-gray-800 fs-6 mb-1">Priority</span>
                    <span class="text-gray-600">{{ $ticket->priority_text }}</span>
                </div>

                <div class="d-flex flex-column mb-5">
                    <span class="fw-bold text-gray-800 fs-6 mb-1">Category</span>
                    <span class="text-gray-600 text-capitalize">{{ $ticket->category }}</span>
                </div>

                <div class="d-flex flex-column">
                    <span class="fw-bold text-gray-800 fs-6 mb-1">Created At</span>
                    <span class="text-gray-600">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-lg-row-fluid ms-lg-15">
        <div class="card shadow-sm border border-gray-300" id="kt_chat_messenger">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">{{ $ticket->subject }}</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Reference ID: {{ $ticket->reference_id }}</span>
                </h3>
            </div>
            
            <div class="card-body" id="kt_chat_messenger_body">
                <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer" data-kt-scroll-wrappers="#kt_content, #kt_chat_messenger_body" data-kt-scroll-offset="5px" style="max-height: 500px;">
                    
                    @foreach($ticket->messages as $msg)
                        @if($msg->is_admin)
                            <!-- Outgoing Message (Admin) -->
                            <div class="d-flex justify-content-end mb-10">
                                <div class="d-flex flex-column align-items-end">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3">
                                            <span class="text-muted fs-7 mb-1">{{ $msg->created_at->format('d M Y, h:i A') }}</span>
                                            <span class="text-gray-900 fs-6 fw-bold ms-1">You</span>
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle">
                                            <span class="symbol-label bg-light-danger text-danger fw-bold">A</span>
                                        </div>
                                    </div>
                                    <div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end">
                                        {{ $msg->message }}
                                        
                                        @if($msg->attachments)
                                            <div class="mt-3">
                                                @foreach($msg->attachments as $att)
                                                    <a href="{{ Storage::url($att) }}" target="_blank" class="badge badge-light-primary">View Attachment</a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Incoming Message (User) -->
                            <div class="d-flex justify-content-start mb-10">
                                <div class="d-flex flex-column align-items-start">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="symbol symbol-35px symbol-circle me-3">
                                            <span class="symbol-label bg-light-success text-success fw-bold">
                                                {{ mb_strtoupper(mb_substr($msg->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-900 fs-6 fw-bold me-1">{{ $msg->user->name }}</span>
                                            <span class="text-muted fs-7 mb-1">{{ $msg->created_at->format('d M Y, h:i A') }}</span>
                                        </div>
                                    </div>
                                    <div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start">
                                        {{ $msg->message }}

                                        @if($msg->attachments)
                                            <div class="mt-3">
                                                @foreach($msg->attachments as $att)
                                                    <a href="{{ Storage::url($att) }}" target="_blank" class="badge badge-light-info">View Attachment</a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="card-footer pt-4" id="kt_chat_messenger_footer">
                @if(!$ticket->isClosed())
                    <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea class="form-control form-control-flush mb-3 text-gray-900" rows="2" data-kt-element="input" placeholder="Type a message" name="message" required></textarea>
                        <div class="d-flex flex-stack border-top pt-4">
                            <div class="d-flex align-items-center me-2">
                                <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button" data-bs-toggle="tooltip" title="Attach Files" onclick="document.getElementById('attachments').click()">
                                    <i class="ki-outline ki-paper-clip fs-3"></i>
                                </button>
                                <input type="file" id="attachments" name="attachments[]" multiple class="d-none">
                            </div>
                            <button class="btn btn-primary btn-sm" type="submit">Send</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning text-center">
                        This ticket is closed. Re-open it to continue chatting.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
