
@foreach($fbBot as $item)

    <x-moonshine::alert icon="heroicons.arrow-right">
        <x-moonshine::title>
            {{ $item->username}}
        </x-moonshine::title>

        <x-moonshine::link href="http://t.me/{{$item->username}}?start={{md5($item->bot_token)}}">Start Newslatter</x-moonshine::link>
    </x-moonshine::alert>

    <x-moonshine::divider />

    {{--    <x-moonshine::title>--}}
{{--        {{ md5($item->bot_token)}}--}}
{{--    </x-moonshine::title>--}}
@endforeach
