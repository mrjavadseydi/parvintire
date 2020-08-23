<footer>
    <div class="container">
        <div class="row py-3">
            <div class="col-md-8">
               <div class="row">
                   <div class="col-md-4 col-6 pl-3 pl-md-5 mt-4 mt-md-0">
                       <h4 class="h6">لینک های اصلی</h4>
                       <ul>
                           <?php $menu = getMenuById(getOption('digishopFooter1')); ?>
                           @if($menu != null)
                               @foreach($menu as $item)
                                   <li>
                                       <a href="{{ $item['link'] }}" {{ echoAttributes($item['attributes']) }}>
                                           <i class="fa fa-caret-left"></i>
                                           <span>{{ $item['title'] }}</span>
                                       </a>
                                   </li>
                               @endforeach
                           @endif
                       </ul>
                   </div>
                   <div class="col-md-4 col-6 pl-3 pl-md-4 mt-4 mt-md-0">
                       <h4 class="h6">بیشترین جستجو ها</h4>
                       <?php
                       $search = \LaraBase\Posts\Models\Search::where('keyword', '!=', '')->limit(7)->groupBy('keyword')->orderBy('c', 'desc')->selectRaw('*, count(*) as c')->get();
                       ?>
                       <ul>
                           @foreach($search as $s)
                               <li><a href=""></a></li>
                               <li>
                                   <a href="{{ url("search?q={$s->keyword}") }}">
                                       <i class="fa fa-caret-left"></i>
                                       <span>{{ $s->keyword }}</span>
                                   </a>
                               </li>
                           @endforeach
                       </ul>
                   </div>
                   <div class="col-md-4">
                       <h4 class="h6">عضویت در خبرنامه</h4>
                       <form clear="#nl1, #nl2, #nl3" method="post" class="newsletters ajaxForm ajaxForm-iziToast" action="{{ route('addNewsLetters') }}">
                           @csrf
                           <input id="nl1" name="name" placeholder="نام و نام خانوادگی " type="text">
                           <input id="nl2" name="mobile" placeholder="موبایل" type="text">
                           <input id="nl3" name="email" placeholder="ایمیل" type="text">
                           <button>عضویت</button>
                       </form>
                   </div>
               </div>
            </div>
            <div class="col-md-4 pt-3">
                <div class="d-flex flex-column certificate">
                    <div class="d-flex justify-content-center">
                        <div class="d-flex flex-column justify-content-center">
                            <h3 class="text-left text-white mb-2">مشاوره رایگان</h3>
                            <span class="d-block text-left text-second-color ltr">{{ sitePhone() }}</span>
                        </div>
                        <i class="fal fa-user-headset text-second-color mr-3"></i>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <?php $menu = getMenuById(getOption('digishopFooterCertificate')); ?>
                        @if($menu != null)
                            @foreach($menu as $item)
                                <a class="ml-2" {{ echoAttributes($item['attributes']) }} href="{{ $item['link'] }}">
                                    <figure class="text-center border-radius-10 bg-white p-2">
                                        <img src="{{ resizeImage($item['image'], 130, 180) }}" alt="{{ $item['title'] }}">
                                    </figure>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright py-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {!! siteCopyright() !!}
                </div>
                <div class="col-md-4 d-flex justify-content-end align-items-center">
                    <ul class="social d-inline-block">
                        <?php $menu = getMenuById(getOption('digishopFooterSocial')); ?>
                        @if($menu != null)
                            @foreach($menu as $item)
                                <li><a  href="{{ $item['link'] }}" {{ echoAttributes($item['attributes']) }} class="{{ $item['icon'] }}"></a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
