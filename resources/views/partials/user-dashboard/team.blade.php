<li class="py-3 px-6 hover:bg-grey-lighter">
    <h3 class="text-base mb-1">{{ $team->name }}</h3>
    <ul class="list-reset flex flex-wrap mb-1 -mx-2 my-1">
        @foreach($team->members as $member)
        <li class="mx-2 my-1 bg-grey-light py-2 px-4 flex items-center rounded text-sm whitespace-no-wrap">
            <img src="https://gravatar.com/avatar/{{ $member->emailHash }}?s=50&d=retro" alt="{{ $member->name }}"
                class="w-4 h-4 rounded-full mr-1">
            <span>{{ ucwords(strtolower($member->name)) }}</span>
        </li>
        @endforeach
    </ul>
</li>