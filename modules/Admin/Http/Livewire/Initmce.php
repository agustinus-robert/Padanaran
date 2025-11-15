<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Initmce extends Component
{
    #[On('helper')]

    public function handleNewPost($refreshPosts){
        return $refreshPosts;
    }
}
