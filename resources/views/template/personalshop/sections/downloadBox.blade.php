<?php
    $filesGroups = $post->filesGroups();
?>
@if(count($filesGroups))
    <div class="pt-3">
        @include(includeTemplate('divider.2') , ['title' => $title ?? 'باکس دانلود'])
        </h1>
        <div class="mt-3 files-groups {{ $font ?? 'sahel' }}">
            <?php $i = 1;?>
            @foreach($post->filesGroups() as $item)
                @if(count($item['files']) > 0)
                    <div class="item {{ $i == 1 ? 'active' : '' }}">
                        <h3>{{ $item['title'] }}</h3>
                        <i class="arrow far fa-angle-left"></i>
                    </div>
                    <div class="files {{ $i == 1 ? 'active' : '' }}">
                        @foreach($item['files'] as $file)
                            @if($file['file']->status == '2')
                                <div class="file {{ $file['file']->status == '2' ? 'disable' : ($file['file']->can() ? '' : 'lock') }}">
                                    <div class="circle">
                                        <span class="counter">{{ $i }}</span>
                                        <span class="lock far fa-clock"></span>
                                    </div>
                                    <h4>{{ $file['file']->title }}</h4>
                                    <div class="details">
                                        @if(!empty($file['file']->note))
                                            <span class="note">{{ $file['file']->note }}</span>
                                        @endif
                                        @if($file['attachment']->duration > 0)
                                            <span>{{ convertSecondToTime($file['attachment']->duration) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <a href="{{ $file['file']->url($post) }}" class="file {{ $file['file']->status == '2' ? 'disable' : ($file['file']->can() ? '' : 'lock') }}">
                                    <div class="circle">
                                        <span class="counter">{{ $i }}</span>
                                        <span class="lock far fa-lock"></span>
                                    </div>
                                    <h4>{{ $file['file']->title }}</h4>
                                    <div class="details">
                                        @if(!empty($file['file']->note))
                                            <span class="note">{{ $file['file']->note }}</span>
                                        @endif
                                        @if($file['attachment']->duration > 0)
                                            <span>{{ convertSecondToTime($file['attachment']->duration) }}</span>
                                        @endif
                                        <span>{{ $file['file']->title() }}</span>
                                    </div>
                                </a>
                            @endif
                            <?php $i++;?>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
        <script>
            $('.files-groups .item').click(function () {
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $(this).next().removeClass('active');
                } else {
                    $(this).addClass('active');
                    $(this).next().addClass('active');
                }
            });
        </script>
    </div>
@endif

@if(isset($product))
    @if($product != null)
        <form onSuccess="coursePayment" method="post" action="{{ url('api/payment/course') }}" id="payment-course-modal" class="modal fade ajaxForm" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <input type="hidden" name="postId" value="{{ $post->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $post->title }}</h5>
                    </div>
                    <div class="modal-body iransansFa">
                        <span>مبلغ قابل پرداخت</span>
                        <div class="text-center">
                            @if($product->discount() > 0)
                                <del class="text-danger">{{ number_format($product->discount() + $product->price()) }}</del>
                            @endif
                            <h4 class="text-success text-center">{{ number_format($product->price()) }} تومان</h4>
                        </div>
                        <div class="payment-error text-center mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <span class="btn btn-danger" data-dismiss="modal">بستن</span>
                        <span id="payment-course-form-submit" class="btn btn-success mr-2">پرداخت آنلاین</span>
                    </div>
                </div>
            </div>
        </form>
        <script>
        $('.payment-course-button').click(function () {
            $('#payment-course-modal').modal();
        });
        $('#payment-course-form-submit').click(function () {
            $('.payment-error').html('');
            if($(this).text() != 'درحال انتقال به درگاه پرداخت...') {
                $(this).text('درحال انتقال به درگاه پرداخت...');
                $('#payment-course-modal').submit();
            }
        });
        function coursePayment(response) {
            if (response.status == 'success') {
                window.location = response.url;
            } else {
                $('.payment-error').html(response.message);
                $('#payment-course-form-submit').text('پرداخت آنلاین');
            }
        }
    </script>
    @endif
@endif

