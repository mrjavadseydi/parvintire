"aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": {{ $post->rateByLikes() }},
    "reviewCount": {{ getPostVisit($post->id) }},
    "bestRating": 5,
    "worstRating": 0
}
