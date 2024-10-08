<?php

namespace App\View\Components;

use App\Models\Url;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UrlTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $urls = Url::all();
        return view('components.url-table');
    }

    public function deleteUrl($id) {
        Url::destroy($id);
        session()->flash('message', 'URL deleted successfully.');
        return redirect()->to('dashboard');
    }
}
