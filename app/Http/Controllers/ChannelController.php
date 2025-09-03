<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;


class ChannelController extends Controller
{
    //

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_private' => 'nullable|boolean',
    ]);

    Channel::create([
        'name' => $request->name,
        'description' => $request->description,
        'is_private' => $request->has('is_private'),
        'created_by' => auth('auth')->id(), 
    ]);

    return redirect()->back()->with('success', 'Channel created successfully.');
}


 public function join($id)
    {
        $channel = Channel::findOrFail($id);
        $channel->members()->syncWithoutDetaching([auth('auth')->id()]);
        return redirect()->back()->with('success', 'You joined the channel!');
    }


}
