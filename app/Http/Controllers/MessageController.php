<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    //

public function store(Request $request)
{
    $request->validate([
        'content'     => 'nullable|string',
        'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10048',
        'channel_id'  => 'nullable|exists:channels,id',
        'receiver_id' => 'nullable|exists:users,id',
    ]);

    $attachmentName = null;

    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $filename = time() . '_' . rand(1000,9999) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('attachments', $filename, 'public');
        $attachmentName = $filename;
    }

    Message::create([
        'user_id'     => auth()->id(),
        'channel_id'  => $request->channel_id,
        'receiver_id' => $request->receiver_id,
        'content'     => $request->content,
        'attachment'  => $attachmentName,
    ]);

    return response()->json(['success' => true]);
}




    public function getDirectMessages($receiverId)
    {
        $receiver = User::findOrFail($receiverId);
        $messages = $receiver->directMessagesWith(auth('auth')->id());

       $html = '';
foreach ($messages as $message) {
    $html .= '<div class="mb-3">';
    $html .= '<strong>' . e($message->sender->name) . '</strong>';

    // Show message content if exists
    if ($message->content) {
        $html .= '<p>' . e($message->content) . '</p>';
    }

    // Handle attachment
    if ($message->attachment) {
        $fileUrl = asset('storage/attachments/' . $message->attachment);
        $extension = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $html .= '<a href="' . $fileUrl . '" target="_blank">';
            $html .= '<img src="' . $fileUrl . '" alt="attachment" style="max-width:150px; max-height:150px; display:block; margin-top:5px;">';
            $html .= '</a>';
        } elseif ($extension === 'pdf') {
             // $html .= '<embed src="' . $fileUrl . '" type="application/pdf" width="20%" height="200px" style="margin-top:5px;" />';
            $html .= '<br><a href="' . $fileUrl . '" target="_blank" class="text-primary">📄 Open PDF in new tab</a>';
        } else {
            $html .= '<a href="' . $fileUrl . '" target="_blank" download class="text-primary">📎 Download File</a>';
        }
    }

    $html .= '<small class="text-muted d-block mt-1">' . $message->created_at->diffForHumans() . '</small>';
    $html .= '</div>';
}


        return response($html);
    }

   public function showDM($receiverId)
{
    $receiver = User::findOrFail($receiverId);

    // Get all users except the authenticated user for the sidebar
    $users = User::where('id', '!=', auth('auth')->id())->get();

    return view('dm', compact('receiver', 'users'));
}

}
