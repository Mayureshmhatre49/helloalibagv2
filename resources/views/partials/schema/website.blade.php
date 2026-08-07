{{-- resources/views/partials/schema/website.blade.php --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://helloalibaug.com/#organization",
      "name": "Hello Alibaug",
      "url": "https://helloalibaug.com",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('images/helloalibaug-logo.png') }}"
      },
      "description": "Alibaug's local marketplace for verified villas, stays, dining, experiences and real estate.",
      "email": "hello@helloalibaug.com",
      "areaServed": {
        "@type": "AdministrativeArea",
        "name": "Alibaug, Raigad District, Maharashtra, India"
      },
      "sameAs": [
        "https://www.instagram.com/helloalibaug/"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "https://helloalibaug.com/#website",
      "url": "https://helloalibaug.com",
      "name": "Hello Alibaug",
      "publisher": { "@id": "https://helloalibaug.com/#organization" },
      "inLanguage": "en-IN",
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "https://helloalibaug.com/search?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
  ]
}
</script>
