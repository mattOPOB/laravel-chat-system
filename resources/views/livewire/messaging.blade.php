<div class="">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messaging') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center pt-3 max-w-7xl mx-auto">
        <div class="lg:max-w-xs w-full">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input id="search" name="search" class="block w-full rounded-md border-0 bg-white py-1.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search for a user here" type="search">
            </div>
        </div>
    </div>

    <div class="flex py-3 h-max" x-data="{ open: false }">
        <div class="flex shadow-xl bg-white rounded-r-lg">
            <div x-show="open" x-transition class=" overflow-hidden sm:rounded-lg h-full">

                <div class="flex text-blue-500 justify-center pl-2 py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <div>
                        All Participants
                    </div>
                </div>

                @foreach($users as $user)
                    <div class="align-middle pl-3">
                        <table class="divide-y divide-gray-300">
                            <tbody class="divide-y divide-gray-200 bg-white">
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-gray-500"> {{ $user->position }}</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach

            </div>
            <button @click="open = !open">
                <div :class="{ 'rotate-180' : open }">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </button>
        </div>

        <div class="sm:px-6 lg:px-8 w-full h-max">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-full w-full">
                    <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
                        <div class="relative flex items-center space-x-4">
                            <div class="relative">
                                <span class="absolute flex h-3 w-3 right-0 bottom-0">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-400"></span>
                                </span>
                                <img src="{{ request()->user()->profile_photo_url }}" alt="" class="w-10 sm:w-16 h-10 sm:h-16 rounded-full">
                            </div>
                            <div class="flex flex-col leading-tight">
                                <div class="text-2xl mt-1 flex items-center">
                                    <span class="text-gray-700 mr-3">{{ request()->user()->name }}</span>
                                </div>
                                <span class="text-lg text-gray-600">{{ request()->user()->position }}</span>
                            </div>
                        </div>
                    </div>


                    <div id="messages" class="flex flex-col max-h-[50rem] space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                        @if(count($dbMessages) < 1)
                            <div class="flex justify-center">
                                <div class="space-x-auto text-blue-500 justify-center animate-pulse">
                                    Send a message to start the conversation!
                                </div>
                            </div>
                        @endif

                        @foreach($dbMessages as $dbMessage)
                            @if($dbMessage->user_id == request()->user()->id)
                                <div class="chat-message">
                                    <div class="flex items-end">
                                        <div class="flex flex-col space-y-2 text-sm max-w-xs mx-2 order-2 items-start">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">{{ $dbMessage->message }}</span></div>
                                        </div>
                                        <img src="{{ $dbMessage->user->profile_photo_url }}" alt="My profile" class="w-6 h-6 rounded-full order-1">
                                    </div>
                                    <div class="text-xs text-gray-500 flex pl-8 pt-1"> {{ $dbMessage->user->name }}</div>
                                </div>
                            @else
                                <div class="chat-message">
                                    <div class="flex items-end justify-end">

                                        <div class="flex flex-col space-y-2 text-sm max-w-xs mx-2 order-1 items-end">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white ">{{ $dbMessage->message }}</span></div>
                                        </div>

                                        <img src="{{ $dbMessage->user->profile_photo_url }}" alt="My profile" class="w-6 h-6 rounded-full order-2">
                                    </div>
                                    <div class="text-xs text-gray-500 flex justify-end pr-8 pt-1"> {{ $dbMessage->user->name }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0 items-center w-full">

                        <div class="mt-2 w-11/12">
                            <input wire:model="message" wire:keydown.enter="sendMessage" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-3" placeholder="Type your message here ...">
                        </div>

                        <div class="mt-2 w-1/12">
                            <button wire:click="sendMessage" type="button" class="flex space-x-2 ml-3 py-1.5 items-center rounded-md bg-blue-500 px-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <div> Send </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>

                    </div>
                    <div class="pl-4 pt-2">
                        @error('message') <span class="error text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


            </div>
        </div>
        <script>

            // Scroll to bottom of chat page so user can see the latest messages first

            let scroll_to_bottom = document.getElementById('messages');
            scroll_to_bottom.scrollTop = scroll_to_bottom.scrollHeight;

            window.addEventListener('scrollDown', function() {
                scroll_to_bottom.scrollTop = scroll_to_bottom.scrollHeight;
            });

        </script>
    </div>
</div>
