{{-- resources/views/partials/schema/howto.blade.php --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "How to take the ferry from Mumbai to Alibaug",
  "description": "Getting from Gateway of India to Mandwa Jetty and on to Alibaug by ferry.",
  "totalTime": "PT90M",
  "estimatedCost": { "@type": "MonetaryAmount", "currency": "INR", "value": "250" },
  "step": [
    {
      "@type": "HowToStep",
      "position": 1,
      "name": "Choose your operator",
      "text": "M2M/RoRo carries vehicles and runs 7:00 AM–7:00 PM at ₹250–₹350 per foot passenger. PNP, Ajanta, Maldar and Apollo run open-deck ferries 7:10 AM–8:15 PM at ₹150–₹200.",
      "url": "https://helloalibaug.com/ferry-schedule#operators"
    },
    {
      "@type": "HowToStep",
      "position": 2,
      "name": "Book ahead on weekends",
      "text": "Walk-ins are accepted at Gateway of India, but weekends and holidays see 30–45 minute waits.",
      "url": "https://helloalibaug.com/ferry-schedule#booking"
    },
    {
      "@type": "HowToStep",
      "position": 3,
      "name": "Cross to Mandwa Jetty",
      "text": "The crossing takes about 60 minutes.",
      "url": "https://helloalibaug.com/ferry-schedule#crossing"
    },
    {
      "@type": "HowToStep",
      "position": 4,
      "name": "Drive on to Alibaug",
      "text": "Mandwa to Alibaug town is a 20–30 minute drive by cab or auto.",
      "url": "https://helloalibaug.com/ferry-schedule#onward"
    }
  ]
}
</script>
