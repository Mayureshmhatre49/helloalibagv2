<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
<channel>
    <title>{{ config('app.name') }} Blog</title>
    <atom:link href="{{ url('/blog/feed') }}" rel="self" type="application/rss+xml" />
    <link>{{ url('/blog') }}</link>
    <description>Latest luxury stay curated guides and insider tips about Alibaug.</description>
    <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
    <language>en-US</language>
    <sy:updatePeriod>hourly</sy:updatePeriod>
    <sy:updateFrequency>1</sy:updateFrequency>

    @foreach($posts as $post)
        <item>
            <title><![CDATA[{{ $post->title }}]]></title>
            <link>{{ route('blog.show', $post->slug) }}</link>
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            <dc:creator><![CDATA[{{ $post->author->name }}]]></dc:creator>
            @if($post->category)
                <category><![CDATA[{{ $post->category->name }}]]></category>
            @endif
            @foreach($post->tags as $tag)
                <category><![CDATA[{{ $tag->name }}]]></category>
            @endforeach
            <guid isPermaLink="false">{{ route('blog.show', $post->slug) }}</guid>
            <description><![CDATA[{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 250) }}]]></description>
            <content:encoded><![CDATA[{!! $post->content !!}]]></content:encoded>
        </item>
    @endforeach
</channel>
</rss>
