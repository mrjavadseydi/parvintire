<?php
$createdAt = jDateTime('Y/m/d H:i:s', strtotime($record->created_at));
$updatedAt = jDateTime('Y/m/d H:i:s', strtotime($record->updated_at));
?>
<td class="jgh-tooltip ltr" title="ایجاد شده در {{ $createdAt }}">{{ $updatedAt ?? $createdAt }}</td>
