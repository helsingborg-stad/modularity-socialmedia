@if(is_array($feed) && !empty($feed))
    <ul id="{{ $sectionID }}" class="social social-feed-v2" data-packery='{ "itemSelector": ".social-item", "percentPosition": true, "transitionDuration": 0 }'>

        @foreach($feed as $item)

            <li class="social-item {{$columns}}">
                <div class="material">
                    @if(!is_null($item['image_large']))
                    <div class="social-image">
                        <a href="{{ $item['network_source'] }}" class="ratio-16-9" style="background-image: url('{{ $item['image_large'] }}');"></a>
                    </div>
                    @endif
                    <div class="social-user">

                        @if(!empty($item['profile_pic']))
                            <div class="user-image material-shadow-md">
                                 <img src="{{ $item['profile_pic'] }}"/>
                            </div>
                        @endif

                        <div class="social-post-details">
                            <a class="social-author" rel="author" href="#author">{{ $item['user_name'] }}</a>
                            <time class="social-publish">{{ $item['timestamp_readable'] }} {{ $translations['ago'] }}</time>
                        </div>

                     </div>

                    @if(!empty($item['content']))
                        <div class="social-content">
                            <a href="{{ $item['network_source'] }}" class="social-text" class="social-text">
                                {{ $item['content'] }}
                            </a>
                        </div>
                    @endif

                    <div class="social-footer clearfix">
                        <span class="text-left"><i class="pricon pricon-thumbs-up"></i> {{ $item['number_of_likes'] }} {{ $translations['likes'] }}</span>
                        <span class="text-right">{{ $translations['posted'] }} {{ $item['network_name'] }}</span>
                    </div>

                </div>
            </li>

        @endforeach

    </ul>

    @if($link && $linkTarget && $linkLabel)
        <a href="{{ $linkTarget }}" class="btn btn-block btn-outline btn-primary btn-md">{{ $linkLabel }}</a>
    @endif

@else
    <div class="notice info">
         <i class="pricon pricon-info-o"></i> {{ $translations['noposts'] }}
    </div>
@endif

