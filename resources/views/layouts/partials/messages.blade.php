  <div id="messagesection" class="flex-grow-1 overflow-auto p-3" style="background-color: #f8f9fa;">
        @foreach ($receiver->directMessagesWith(auth()->id()) as $message)
            <div class="mb-3">
                <strong>{{ $message->sender->name }}</strong>
                <p>{{ $message->content }}</p>
                @if ($message->attachment)
                    <a href="{{ asset('storage/attachments/' . $message->attachment) }}" target="_blank">📎 Attachment</a>
                @endif
                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
            </div>
        @endforeach
    </div>