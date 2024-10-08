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