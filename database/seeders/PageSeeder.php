<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\PageImage;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Home Page
        $homePage = Page::create([
            'name' => 'Home',
            'slug' => 'home',
            'meta_description' => 'Welcome to Family Cloud - A secure platform for sharing family memories',
            'is_published' => true,
        ]);

        // Home page content
        PageContent::create([
            'page_id' => $homePage->id,
            'key' => 'hero_title',
            'value' => 'Welcome to'
        ]);

        PageContent::create([
            'page_id' => $homePage->id,
            'key' => 'hero_subtitle',
            'value' => 'Family Cloud'
        ]);

        PageContent::create([
            'page_id' => $homePage->id,
            'key' => 'hero_description',
            'value' => 'A secure and private platform for sharing family memories, photos, and important files with your loved ones.'
        ]);

        PageContent::create([
            'page_id' => $homePage->id,
            'key' => 'features_title',
            'value' => 'Everything you need for family sharing'
        ]);

        PageContent::create([
            'page_id' => $homePage->id,
            'key' => 'features_description',
            'value' => 'Secure, private, and easy to use. Family Cloud provides all the tools you need to share and organize your family memories.'
        ]);

        PageImage::create([
            'page_id' => $homePage->id,
            'label' => 'hero_image',
            'path' => 'images/hero/family-hero.jpg',
            'alt_text' => 'Family enjoying time together'
        ]);

        // About Page
        $aboutPage = Page::create([
            'name' => 'About',
            'slug' => 'about',
            'meta_description' => 'Learn more about Family Cloud and our mission to help families stay connected',
            'is_published' => true,
        ]);

        PageContent::create([
            'page_id' => $aboutPage->id,
            'key' => 'title',
            'value' => 'About Family Cloud'
        ]);

        PageContent::create([
            'page_id' => $aboutPage->id,
            'key' => 'introduction',
            'value' => 'Family Cloud was born from a simple idea: families should have a secure, private space to share their most precious memories without worrying about privacy or data security.'
        ]);

        PageContent::create([
            'page_id' => $aboutPage->id,
            'key' => 'content',
            'value' => '<h2>Our Story</h2>
            <p>Founded in 2024, Family Cloud emerged from the recognition that families needed a better way to share and preserve their digital memories. Unlike social media platforms where your content becomes public and subject to algorithms, we provide a private sanctuary for your family\'s most important moments.</p>

            <h2>Our Mission</h2>
            <p>We believe that family memories deserve the highest level of protection and the easiest sharing experience. Our mission is to provide families with a secure, intuitive platform that brings loved ones closer together through shared experiences and preserved memories.</p>

            <h2>What Makes Us Different</h2>
            <p>Privacy is not just a feature for us—it\'s our foundation. Every photo, video, and document you share is encrypted and stored securely. We don\'t sell your data, show advertisements, or use your content for any purpose other than helping your family stay connected.</p>

            <h2>Our Values</h2>
            <ul>
                <li><strong>Privacy First:</strong> Your family\'s content remains private and secure</li>
                <li><strong>Simplicity:</strong> Easy to use for all family members, regardless of technical expertise</li>
                <li><strong>Reliability:</strong> Your memories are safe with our robust backup and security systems</li>
                <li><strong>Family Focused:</strong> Every feature is designed with families in mind</li>
            </ul>'
        ]);

        PageImage::create([
            'page_id' => $aboutPage->id,
            'label' => 'team_image',
            'path' => 'images/about/team.jpg',
            'alt_text' => 'Our dedicated team'
        ]);

        PageContent::create([
            'page_id' => $aboutPage->id,
            'key' => 'team_caption',
            'value' => 'Our dedicated team working to keep families connected'
        ]);

        // Contact Page
        $contactPage = Page::create([
            'name' => 'Contact',
            'slug' => 'contact',
            'meta_description' => 'Get in touch with Family Cloud - We\'re here to help',
            'is_published' => true,
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'title',
            'value' => 'Contact Us'
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'description',
            'value' => 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.'
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'phone',
            'value' => '+1 (555) 123-4567'
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'phone_hours',
            'value' => 'Mon-Fri 9am to 6pm PST'
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'email',
            'value' => 'support@familycloud.com'
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'email_description',
            'value' => 'We\'ll respond within 24 hours'
        ]);

        PageContent::create([
            'page_id' => $contactPage->id,
            'key' => 'address',
            'value' => '123 Family Street, Cloud City, CC 12345'
        ]);
    }
}
