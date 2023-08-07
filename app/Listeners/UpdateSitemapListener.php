<?php

namespace App\Listeners;

use App\Events\WisdomUpdated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class UpdateSitemapListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\WisdomUpdated  $event
     * @return void
     */
    public function handle(WisdomUpdated $event)
    {
        $wisdom = $event->wisdom;
        $sitemapPath = public_path('sitemap.xml');
        $existingSitemap = file_get_contents($sitemapPath);
        $xml = simplexml_load_string($existingSitemap);
        // Register the namespace with a prefix (e.g., "ns")
        $xml->registerXPathNamespace('ns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        // Use the registered namespace in the XPath expression

        foreach ($wisdom->categories as $category) {
            $searchUrl = 'https://dralmutawa.com/category/' . $category->category_url;
            $node = $xml->xpath('/ns:urlset/ns:url[ns:loc="' . $searchUrl . '"]');
            $formattedUpdatedAt = Carbon::parse($wisdom->updated_at)->toIso8601String();
            $node[0]->lastmod = $formattedUpdatedAt;
        }
        // Add other wisdom data as needed

        // Convert the updated SimpleXML object back to a string
        $updatedSitemap = $xml->asXML();

        // Save the updated sitemap.xml
        file_put_contents($sitemapPath, $updatedSitemap);
    }
}
