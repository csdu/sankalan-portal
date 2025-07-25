    <li class="px-6 py-3 hover:bg-slate-100"style="order: {{$event->isLive ? 0 : ($event->hasEnded ? 3 : 1)}};">
    <div class="content flex-1 flex flex-col">
        <h3 class="text-lg mb-1 flex items-center">
            <span>{{ $event->title }}</span>
            @if($event->hasStarted || $event->hasEnded)
            <span
                class="ml-1 w-2 h-2 inline-block rounded-full {{ $event->isLive ? 'bg-emerald-400' : 'bg-red-500' }}"></span>
            @endif
        </h3>
        <p class="mb-1">
            <b class="mr-1">Team:</b>
            <span class="underline">{{ $team->name }}</span>
        </p>
        @if($event->activeQuiz)
            <p class="text-grey-darker my-1">
                <b>{{ $event->activeQuiz->title }}</b> is live.
            </p>
        @elseif($event->isLive && $quizzes_count)
            <p class="text-grey-darker font-semibold my-1">
                Quiz will start soon
            </p>
        @endif
        <div class="mt-auto flex-items-center">
            @if(!$event->hasEnded && !optional($event->activeQuiz)->participationByTeam($team))
            <form action="{{ route('events.withdraw-part', $event) }}" method="POST" class="inline-block mr-2">
                @csrf @method('delete')
                <button class="btn is-red p-1 text-xs">Withdraw</button>
            </form>
            @endif
            @if($event->isLive && $quizzes_count && $event->activeQuiz)
                @if (optional($event->activeQuiz->participationByTeam($team))->finished_at)
                    <p class="text-xs font-bold">Thank you for taking this quiz.</p>
                @else
                    <a href="{{ route('quizzes.verify', $event->activeQuiz) }}" class="btn is-green p-1 text-xs">
                        @if (!!$event->activeQuiz->participationByTeam($team) && !$event->activeQuiz->participationByTeam($team)->finished_at)
                            Resume Quiz
                        @else
                            Take Quiz
                        @endif
                    </a>
                @endif
            @endif
        </div>
    </div>
</li>
