<div class="px-2 sm:w-1/2 mb-4" style="order: {{ $event->activeQuiz ? 0 : 1}};">
    <div class="relative card seperated h-full flex flex-col">
        @if($event->started_at != null)
        <span class="absolute top-0 right-0 px-2 py-1 uppercase text-xs text-white bg-{{$event->ended_at ? 'red' : 'green'}}">
            {{ $event->ended_at ? 'Ended' : 'Live' }}
        </span> 
        @endif

        <h4 class="card-header capitalize">
            {{ $event->title }}
        </h4>

        <div class="card-content flex-1">
            <p>{!! $event->description !!}</p>
        </div>

        @if(!$event->hasEnded)
            <div class="card-footer">
                @auth
                    @if(!$participatingTeam = $event->participatingTeamByUser($signedInUser))
                        <form action="{{ route('events.participate', $event) }}" method="POST" class="flex items-center">
                            @csrf
                            <button type="submit" class="btn is-blue is-sm">
                                {{ count($signedInUser->teams) ? 'Participate' : 'Participate Alone' }}
                            </button> 
                            @if(count($signedInUser->teams))
                                <span class="ml-3">as:</span>
                                <select name="team_id" class="ml-1 control is-sm">
                                    @if(!$signedInUser->team_id)
                                        <option value="">Individual</option>
                                    @endif
                                    @foreach($signedInUser->teams as $team)
                                        <option value="{{ $team->id }}">{{$team->name}} - {{$team->uid}}{{ $team->id == $signedInUser->team_id ? ' (Individual)' : '' }}</option>
                                    @endforeach
                                </select> 
                            @else
                                <p class="text-xs text-grey-dark ml-3">
                                    You have not created any teams yet, you can participate <em>alone</em> or <a href="{{ route('teams') }}"
                                        class="hover:underline text-blue">Create Team</a>. When you participate <em>alone</em>, a team will be
                                    created with a name <strong>"{{ $signedInUser->name }}"</strong>
                                </p>
                            @endif
                        </form>
                        
                    @elseif(!$event->isLive)
                        <div class="flex-1 flex items-center">
                            <p class="flex-1">Participating as <strong class="text-xs">{{ $participatingTeam->name }} - {{ $participatingTeam->uid }}</strong>!</p>
                            <form action="{{ route('events.withdraw-part', $event) }}" method="POST">
                                @csrf @method('delete')
                                <button class="btn is-red is-sm">Withdraw</button>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex items-center">
                            <p class="flex-1">Participat{{ optional($event->activeQuiz->participationByTeam($participatingTeam))->finished_at ? 'ed' : 'ing' }} as 
                                <strong class="text-xs">{{ $participatingTeam->name }} - {{ $participatingTeam->uid }}</strong>!
                            </p>
                            @if ($event->activeQuiz)
                                @if (!optional($event->activeQuiz->participationByTeam($participatingTeam))->finished_at)
                                    <a href="{{ route('quizzes.show', $event->activeQuiz) }}" class="btn is-green p-1 text-xs">
                                        @if (!!$event->activeQuiz->participationByTeam($participatingTeam) && !$event->activeQuiz->participationByTeam($participatingTeam)->finished_at)
                                            Resume Quiz
                                        @else
                                            Take Quiz                        
                                        @endif
                                    </a>
                                @endif
                            @else                                
                                <a href="{{ route('dashboard') }}" class="btn is-blue is-sm">Go to dashboard</a>
                            @endif
                        </div>
                    @endif
                @else 
                    <p>Please <a href="{{ route('login') }}" class="link">sign in</a> to participate</p>
                @endauth
            </div>
        @endif
    </div>
</div>