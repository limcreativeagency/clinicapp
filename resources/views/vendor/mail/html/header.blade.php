<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<div style="font-size: 24px; font-weight: bold; color: #2563eb; text-align: center;">
🏥 MyClinic Center
</div>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
