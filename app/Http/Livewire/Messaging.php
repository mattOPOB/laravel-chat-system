<?php

namespace App\Http\Livewire;

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
        'echo-private:laravel-chat,.MessageSent' => 'refreshChat'
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
        $this->dbMessages[] = $messageToAdd;
    }

    public function sendMessage(){
        $this->validate();

        $message = Message::create([
            'user_id' => $this->userId,
            'message' => $this->message,
        ]);

        $this->dbMessages[] = $message;

        broadcast(new MessageSent($this->userId, $message->id))->toOthers();

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.messaging');
    }
}
