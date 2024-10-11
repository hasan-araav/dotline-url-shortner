<table>
    <thead>
        <th>Original URL</th>
        <th>Short URL</th>
        <th>Total Clicks</th>
        <th>Created At</th>
        <th>Expires At</th>
    </thead>
    <tbody>
        <tr>
            <td>{{ $url->original_url }}</td>
            <td>{{ url($url->short_code) }}</td>
            <td>{{ $url->clicks }}</td>
            <td>{{ $url->created_at }}</td>
            <td>{{ $url->updated_at }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <th>Country</th>
        <th>Device Type</th>
        <th>Browser</th>
        <th>OS</th>
        <th>Visits</th>
    </thead>
    <tbody>
        @foreach ($clicks as $click)
        <tr>
            <td>{{ $click->country }}</td>
            <td>{{ $click->device_type }}</td>
            <td>{{ $click->browser }}</td>
            <td>{{ $click->os }}</td>
            <td>{{ $click->count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>