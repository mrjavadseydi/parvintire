<?php

function shareToTelegram($title, $href) {
    return "https://t.me/share/url?url={$href}&amp;text={$title}";
}

function shareToWhatsApp($title, $href) {
    return "whatsapp://send?text={$href}";
}

function shareToFacebook($title, $href) {
    return "http://www.facebook.com/share.php?u={$href }&amp;t={$title}";
}

function shareToTwitter($title, $href) {
    return "https://www.twitter.com/intent/tweet?url={$href}&amp;text={$title}";
}

function shareToGooglePlus($title, $href) {
    return "https://plus.google.com/share?url={$href}";
}

function shareToEmail($title, $href) {
    return "mailto:?subject={$title}&amp;body={$href}";
}

function shareToLinkedin($title, $href) {
    return "https://www.linkedin.com/shareArticle?mini=true&amp;url={$href}&amp;title={$title}&amp;summary=&amp;source=" . env('APP_NAME');
}

function shareToPinterest($title, $href) {
    return "http://pinterest.com/pinthis?url={$href}";
}
