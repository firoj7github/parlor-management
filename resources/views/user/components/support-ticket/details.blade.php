@if (isset($support_ticket))
<div class="support-profile-wrapper">
    <div class="support-profile-header">
        <div class="custom-check-group two mb-0">
            <span class="{{ $support_ticket->stringStatus->class }}">{{ $support_ticket->stringStatus->value }}</span>
        </div>
        <div class="chat-cross-btn">
            <i class="las la-times"></i>
        </div>
    </div>
    <div class="support-profile-body">
        <h5 class="title">{{ __("Support Details") }}</h5>
        <ul class="support-profile-list">
            <li>{{ __("Subject") }} : <span>{{ $support_ticket->subject ?? ""}}</span></li>
            <li>{{ __("Description") }} : <span>{{ $support_ticket->desc ?? ""}}</span></li>
            @foreach ($support_ticket->attachments as $key => $item)
                <li>{{ __("Attachments") }} - {{ $key + 1 }} : 
                    <span class="text--danger">
                        <a href="{{ setRoute('file.download',['support-attachment',$item->attachment]) }}">
                            {{ Str::words($item->attachment_info->original_base_name ?? "", 5, '...' . $item->attachment_info->extension ?? "" ) }}
                        </a>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif