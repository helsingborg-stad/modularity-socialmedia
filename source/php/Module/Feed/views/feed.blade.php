<section class="section-tiles">
    <div class="container container-fullwidth">
        <div class="tile-grid grid no-gutter" data-packery='{ "itemSelector": ".tile-grid > *", "percentPosition": true, "transitionDuration": 500, "gutter" : 0 }'>
            @foreach ($tiles as $tile)
                @if($tile->image)
                    <div class="{{$tile->grid}}">
                        <div class="{{$tile->tile}} tile-img" style="background-image: url('{{$tile->image}}');">
                            <div>
                                <div class="content">
                                    @if($tile->title)
                                        <h4>{{$tile->title}}</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="{{$tile->grid}}">
                        <div class="{{$tile->tile}}">
                            <a href="{{$tile->url}}">
                                <div class="content">
                                    @if($tile->title)
                                        <h4>{{$tile->title}}</h4>
                                    @endif
                                    @if($tile->content)
                                        <p>{{$tile->content}}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
</section>
