<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserDatatable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 15;
    public $isActive;

    public function removeUser($id)
    {
        if(auth()->user()->id == $id) {
            return;
        }

        User::where('id', $id)->delete();
    }

    public function setStatus($id, $status)
    {
        if(auth()->user()->id == $id) {
            return;
        }

        User::where('id', $id)->update([
            'is_active' => $status,
        ]);
    }

    public function render()
    {
        return view('livewire.user-datatable', [
            'users' => User::query()
                ->when(
                    $this->isActive == 1,
                    fn($query) => $query->active(),
                )
                ->when(
                    $this->isActive == 2,
                    fn($query) => $query->inActive(),
                )
                ->when(
                    strlen($this->search) > 3,
                    fn($query) => $query->search($this->search)
                )->paginate($this->perPage),
        ]);
    }
}
