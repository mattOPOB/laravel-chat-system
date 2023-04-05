<?php

namespace App\Http\Livewire;

use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Messaging extends Component
{

    public $message = '';
    public $userId;
    public $dbMessages;
    public $users;

    protected $listeners = [
        'echo-private:laravel-chat,.MessageSent' => 'refreshChat',
        'echo-private:laravel-chat,.MessageDeleted' => 'refreshChatOnDelete'
    ];

    protected $rules = [
      'message' => 'required'
    ];

    public function mount(){
        $this->userId = request()->user()->id;
        $this->dbMessages = Message::all();
        $this->users = User::all();
    }

    public function refreshChat($payload) {
        $messageToAdd = Message::find($payload['message']);

        // Initially queried the DB for all messages here.
        // To improve efficiency the new message is just pushed to the class message array.
        $this->dbMessages[] = $messageToAdd;
        $this->dispatchBrowserEvent('scrollDown');

    }

    public function sendMessage(){
        $this->validate();

        $message = Message::create([
            'user_id' => $this->userId,
            'message' => $this->message,
        ]);

        $this->dbMessages[] = $message;

        // Event that will be broadcasted on the application pusher channel
        broadcast(new MessageSent($this->userId, $message->id))->toOthers();

        $this->message = '';

        // This event will be picked up by the browser and scroll to bottom of chat page
        // The user will see the latest messages first
        $this->dispatchBrowserEvent('scrollDown');

    }

    public function deleteMessage(Message $message, $key){
        $message->delete();
        $this->refreshChatOnDelete($key);
        broadcast(new MessageDeleted($key))->toOthers();

    }

    public function refreshChatOnDelete($key){
        $this->dbMessages->forget($key);
}

    public function render()
    {
        return view('livewire.messaging');
    }
}
