@props(['urls'])

<div>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table-auto">
        <thead>
            <tr>
                <th>ID</th>
                <th>Original URL</th>
                <th>Short URL</th>
                <th>Clicks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($urls as $url)
                <tr>
                    <td>{{ $url->id }}</td>
                    <td><a href="{{ $url->original_url }}" target="_blank">{{ $url->original_url }}</a></td>
                    <td><a href="{{ url($url->short_code) }}" target="_blank">{{ url($url->short_code) }}</a></td>
                    <td>{{ $url->clicks }}</td>
                    <td>
                        <a href="{{ route('url.analytics', ['shortCode' => $url->short_code]) }}" class="btn btn-info">Analytics</button>
                        <a href="{{ route('url.delete', ['shortCode' => $url->short_code]) }}" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
