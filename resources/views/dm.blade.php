@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100">
    <div class="row h-100">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content: DM Chat -->
        <div class="col-md-9 col-lg-10 p-0 d-flex flex-column vh-100">
            <!-- Chat Header -->
            <div class="bg-white border-bottom p-3">
                <h5 class="mb-0">💬 {{ $receiver->name }}</h5>
            </div>

            <!-- Messages Area -->
            <div id="messagesection" class="flex-grow-1 overflow-auto p-3" style="background-color: #f8f9fa;">
                <!-- Messages will load here -->
            </div>

            <!-- Dropzone + Message Input -->
            <div class="border-top bg-white p-3">
                <form id="messageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">

                    <!-- Dropzone -->
                    <div id="dropzone" class="border border-2 border-secondary rounded p-3 mb-2 text-center"
                         style="cursor: pointer;">
                        <p id="dropzone-text" class="m-0">📁 Drag & drop a file here or click to browse</p>
                        <input type="file" name="attachment" id="attachmentInput" class="d-none" accept="image/*">
                    </div>

                    <!-- File Preview -->
                    <div id="file-preview" class="mb-2 d-none"></div>

                    <!-- Message Input -->
                    <div class="input-group">
                        <input type="text" name="content" id="contentInput" class="form-control"
                               placeholder="Type your message...">
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script>
const dropzone = document.getElementById('dropzone');
const messagesection = document.getElementById('messagesection');
const attachmentInput = document.getElementById('attachmentInput');
const dropzoneText = document.getElementById('dropzone-text');
const filePreview = document.getElementById('file-preview');
const messageForm = document.getElementById('messageForm');
const contentInput = document.getElementById('contentInput');

// Drag & Drop + Click to Upload
dropzone.addEventListener('click', () => attachmentInput.click());

[dropzone, messagesection].forEach(area => {
    area.addEventListener('dragover', e => {
        e.preventDefault();
        area.classList.add('bg-light');
        dropzoneText.textContent = '📤 Drop the file to attach';
    });
    area.addEventListener('dragleave', () => {
        area.classList.remove('bg-light');
        dropzoneText.textContent = '📁 Drag & drop a file here or click to browse';
    });
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('bg-light');
        handleFileDrop(e.dataTransfer.files);
    });
});

attachmentInput.addEventListener('change', () => handleFileDrop(attachmentInput.files));

function handleFileDrop(files) {
    if (files.length > 0) {
        attachmentInput.files = files;
        const file = files[0];
        dropzoneText.textContent = `📎 ${file.name} ready to send`;
        filePreview.classList.remove('d-none');
        filePreview.innerHTML = `<small class="text-muted">Selected: ${file.name}</small>`;
    } else {
        filePreview.classList.add('d-none');
        dropzoneText.textContent = '📁 Drag & drop a file here or click to browse';
    }
}

// Auto-refresh messages
let firstLoad = true;
function refreshMessages() {
    fetch("{{ route('dm.messages', $receiver->id) }}")
        .then(response => response.text())
        .then(html => {
            const isAtBottom = messagesection.scrollTop + messagesection.clientHeight >= messagesection.scrollHeight - 50;
            messagesection.innerHTML = html;
            if (firstLoad || isAtBottom) {
                messagesection.scrollTop = messagesection.scrollHeight;
                firstLoad = false;
            }
        });
}
setInterval(refreshMessages, 3000);
refreshMessages();

// AJAX Submit Message
messageForm.addEventListener('submit', sendMessage);
contentInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage(e);
    }
});

function sendMessage(e) {
    e.preventDefault();
    const formData = new FormData(messageForm);

    // Prevent sending if both content and attachment are empty
    if (!formData.get('content') && !formData.get('attachment').name) return;

    fetch("{{ route('messages.store') }}", {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            contentInput.value = '';
            attachmentInput.value = '';
            filePreview.classList.add('d-none');
            dropzoneText.textContent = '📁 Drag & drop a file here or click to browse';
            refreshMessages();
        } else {
            alert('Error sending message');
        }
    });
}
</script>
@endsection
