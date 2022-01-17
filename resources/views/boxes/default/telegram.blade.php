<div class="box box-info">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body">

        <div class="input-group">
            <select name="telegram_send_to">
                <option value="">هیچکدام</option>
                <option value="group">ارسال به گروه</option>
                <option value="channel">ارسال به کانال</option>
            </select>

            <?php
                $telegramMessage = json_decode($postType->metas, true)['telegram_message_pattern'] ?? '';

                if (!empty($telegramMessage)) {

                    $telegramMessage = str_replace('{title}', $post->title, $telegramMessage);
                    $telegramMessage = str_replace('{excerpt}', $post->excerpt, $telegramMessage);
                    $telegramMessage = str_replace('{shortLink}', str_replace('https://', '', str_replace('http://', '', url("p/{$post->id}"))), $telegramMessage);

                    $categoriesTags = [];
                    foreach($post->categories as $c)
                        $categoriesTags[] = '#' . str_replace('-', '_', $c->slug);

                    $telegramMessage = str_replace('{categories_tags}', implode(' ', $categoriesTags), $telegramMessage);

                }
            ?>

            <textarea name="telegram_message" class="w100" style="height: 200px">{{ $telegramMessage }}</textarea>

        </div>

    </div>

</div>
