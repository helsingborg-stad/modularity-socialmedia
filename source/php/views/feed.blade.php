
@if(is_array($feed) && !empty($feed))

    <div class="container">
        <div class="grid">
            <div class="grid-xs-12">
                <ul class="social social-feed-v2" data-packery='{ "itemSelector": ".social-item", "percentPosition": true, "transitionDuration": 0 }'>

                    @foreach($feed as $item)

                        <li class="social-item">
                             <div class="material">
                                 <div class="social-image">
                                     <a href="#post" class="social-text">
                                         <img src="{{ $item['image_large'] }}" class="ratio-16-9"/>
                                     </a>
                                 </div>
                                 <div class="social-user">
                                     <div class="user-image material-shadow-md">
                                         <img src="{{ $item['image_small'] }}"/>
                                     </div>
                                     <div class="social-post-details">
                                         <a class="social-author" rel="author" href="#author">John Doe</a>
                                         <time class="social-publish">9 hours ago</time>
                                     </div>
                                 </div>
                                 <div class="social-content">
                                     <a href="#post" class="social-text" class="social-text">
                                         {{ $item['content'] }}
                                     </a>
                                 </div>
                                 <div class="social-footer clearfix">
                                     <span class="text-left"><i class="pricon pricon-thumbs-up"></i> {{ $item['number_of_likes'] }} likes</span>
                                     <span class="text-right">posted on {{ $item['network_name'] }}</span>
                                 </div>
                             </div>
                        </li>

                    @endforeach

                </ul>
            </div>
        </div>
    </div>


@endif

