@php
    if(count($errors)) {
        flash('Validation Failed!')->error();
    }
@endphp
<v-flash :data-messages="{{ 
    session('flash_notification', collect())->each(function($item, $key) {
        return $item['id'] = $key + 1;
    })->toJson() 
}}"></v-flash>
{{ session()->forget('flash_notification') }}
