<tr>
    <td align="center" style="padding: 20px;">
        @if (trim($slot) === 'Laravel')
            <img src="{{ asset('logo.png') }}" class="logo" alt="Laravel Logo">
        @else
            {{ $slot }}
        @endif
    </td>
</tr>
