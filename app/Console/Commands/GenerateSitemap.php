<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Wisdom;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate the XML sitemap';

    public function handle(): void
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'));

        Category::all()->each(
            fn($cat) =>
            $sitemap->add(
                Url::create("/category/{$cat->category_url}")
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
            )
        );

        Wisdom::select('id', 'updated_at')->each(
            fn($w) =>
            $sitemap->add(
                Url::create("/wisdom/{$w->id}")
                    ->setLastModificationDate($w->updated_at)
                    ->setPriority(0.6)
                    ->setChangeFrequency('monthly')
            )
        );

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated at public/sitemap.xml');
    }
}
