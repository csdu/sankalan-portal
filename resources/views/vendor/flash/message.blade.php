@php
if(count($errors)) {
foreach($errors->all() as $error) {
flash($error)->error();
}
}
@endphp
<v-flash :data-messages="{{
    session('flash_notification', collect())
        ->each(fn($item, $key) => $item['id'] = $key + 1)
        ->toJson()
}}"></v-flash>
{{ session()->forget('flash_notification') }}
