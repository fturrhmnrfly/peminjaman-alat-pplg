<table class="meta">
    @foreach($rows as $row)
        <tr>
            <td class="{{ $labelClass ?? 'meta-label' }}">{{ $row['label'] }}</td>
            <td>: {{ $row['value'] }}</td>
        </tr>
    @endforeach
</table>
