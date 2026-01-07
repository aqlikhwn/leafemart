<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBadge extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('notification-read')]
    public function updateCount()
    {
        if (Auth::check()) {
            $this->count = Notification::where('user_id', Auth::id())
                ->where('read', false)
                ->count();
        } else {
            $this->count = 0;
        }
    }

    public function render()
    {
        return view('livewire.notification-badge');
    }
}
