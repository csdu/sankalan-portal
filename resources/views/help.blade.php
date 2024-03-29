@extends('layouts.app')
@section('title', "HELP | ")
@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl my-6" >Help / FAQ</h1>
    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">How do I participate as a team?</h2>
        </div>
        <div class="card-content">
            <ol class="text-lg leading-normal mb-4">
                <li class="my-1">Before creating a team, each team member needs to register seperately on the portal.</li>
                <li class="my-1">Any one of them can now create team with the other registered partner, using his/her registered email.</li>
                <li class="my-1">You can create multiple teams with different partners and participate in different events.</li>
                <li class="my-1">Make sure you have selected the correct team, when you click on <em>participate</em> button.</li>
                <li class="my-1">If you mistakenly participated as a different team, you can withdraw participation using "Withdraw" button and participate again as the team you prefer.</li>
            </ol>
            <h4 class="mb-2">Example:</h4>
            <ol>
                <li class="my-1">"John Doe" & "Jane Doe" wants to create a team, with name "The Does", and participate in an event "Brain Spark".</li>
                <li class="my-1">"John Doe" & "Jane Doe" registers sepereately on the portal using their respective emails.</li>
                <li class="my-1">Now either "John Doe" or "Jane Doe" can login and create a team with name "The Does", by entering other member's registered email in the <em class="whitespace-nowrap">partner's email</em> field.</li>
                <li class="my-1">Both of the members can see "The Does" on their dashboard, either of them can now participate in "Brain Spark" by selecting "The Does" from dropdown and click on button "Participate".</li>
            </ol>
        </div>
    </div>
    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">How do I participate as Individual?</h2>
        </div>
        <div class="card-content">
            <ol class="text-lg leading-normal">
                <li class="my-1">Register on the portal.</li>
                <li class="my-1">To participate as individual you need to create a team which has only one member i.e. <em>You</em>.</li>
                <li class="my-1">You can create team right from your dashboard.</li>
                <li class="my-1">Give your team a name, leave partner's email field blank.</li>
                <li class="my-1">Make sure you have selected the correct team, when you click on <em>participate</em> button.</li>
            </ol>
        </div>
    </div>

    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">What happens when I participate without creating any team?</h2>
        </div>
        <div class="card-content">
            <p class="leading-normal text-lg">When you participate in any event without creating any team, we create a team for you on the fly. This team has only one member, i.e. <em>You</em>, and team name is same as your name. Say, your name is "John Doe", your team name will also be "John Doe". If you want to customize, your team name. Currently we have not added feature to rename your team. But you can make sure you create a team before participating so that you are given a chance to name your team.</p>
        </div>
    </div>

    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">Does my teammate also need to login to play the quiz?</h2>
        </div>
        <div class="card-content">
            <p class="leading-normal text-lg">No, Only one of the member needs to login to play the quiz and other can coordinate with him/her online (using google meet or whatever) or offline in-person. <b>Note: All of the member of the team needs to be registered first no matter what.</b></p>
        </div>
    </div>

    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">Why I'm getting "This page isn’t working right now" error in the middle of the gameplay?</h2>
        </div>
        <div class="card-content">
            <p class="leading-normal text-lg">This error usually occur when either the quiz has been ended by the organisers or you might have been disqualified and you're trying to reload the page. In either cases you'll see the exact reason on your home page (<a href="{{ route('dashboard') }}">{{ route('dashboard') }}</a>). In case you see this as an error, reach out to respective quiz organisers. Don't panic as your responses are saved with us.</p>
        </div>
    </div>

    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">Why I'm getting "Error occured in saving response" message?</h2>
        </div>
        <div class="card-content">
            <p class="leading-normal text-lg">This message usually displayed when either the quiz has been ended by the organisers or you might have been disqualified. In either cases you'll see the exact reason on your home page (<a href="{{ route('dashboard') }}">{{ route('dashboard') }}</a>). In case you see this as an error, reach out to respective quiz organisers.</p>
        </div>
    </div>

    <div class="card seperated mb-8">
        <div class="card-header">
            <h2 class="text-xl font-normal">Still Confused or Facing Some Issue?</h2>
        </div>
        <div class="card-content">
            <p class="leading-normal text-lg">
                If you are still confused about the registration process, feel free to get in touch with us, Call / WhatsApp at <a class="link" href="tel:{{ config('app.event_management_contact_number') }}">{{ config('app.event_management_contact_number') }}</a>, or drop us an email, <a class="link" href="mailto:dev@ducs.in">dev@ducs.in</a>.
                <br>
                If you facing some issue report it to us with your contact details, call / WhatsApp at <a class="link" href="tel:{{ config('app.event_management_contact_number') }}">{{ config('app.event_management_contact_number') }}</a>, or drop us an email, <a class="link" href="mailto:dev@ducs.in">dev@ducs.in</a>.
            </p>
        </div>
    </div>

</div>
@endsection
