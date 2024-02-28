<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\CompanyInterface;
use App\Contracts\CountryInterface;
use App\Contracts\UserInterface;
use App;
use DOMDocument;

class HashBangHtmls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hashbang:html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rewrite route html.';

    /**
     * Object of CompanyInterface class
     *
     * @var companyRepo
     */
    private $companyRepo;

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CompanyInterface $companyRepo, CountryInterface $countryRepo, UserInterface $userRepo)
    {
        $this->companyRepo = $companyRepo;
        $this->countryRepo = $countryRepo;
        $this->userRepo = $userRepo;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = new DOMDocument();
        $sitemap->load('example.xml');
        // $routes = [
        //             'https://sharado.com/#!/',
        //             'https://sharado.com/#!/contact-us'
        //             ];
        $routes = ['https://sharado.com/it',
                   'https://sharado.com/it/landing-page',
                   'https://sharado.com/it/contact-us',
                   'https://sharado.com/it/gigs',
                   'https://sharado.com/it/all-categories'];
        //$locales = config('translatable.locales');
        $locales = array('it');
        // foreach($routes as $route) {
        //     $doc = $sitemap->documentElement;
        //     $url = $sitemap->createElement('url');
        //     $url = $doc->appendChild($url);

        //     $loc = $sitemap->createElement('loc');
        //     $loc = $url->appendChild($loc); 

            
        //     foreach ($locales as $locale) {
        //         $xhtml = $sitemap->createElement('xhtml:link');
        //         $rel = $sitemap->createAttribute('rel');
        //         $rel->value = 'alternate';
        //         $hreflang = $sitemap->createAttribute('hreflang');
        //         $href = $sitemap->createAttribute('href');
        //         $hreflang->value = $locale;
        //         $route = substr_replace($route,$locale,20,2);
        //         $href->value = htmlspecialchars($route);
        //         $xhtml->appendChild($rel);
        //         $xhtml->appendChild($hreflang);
        //         $xhtml->appendChild($href);
        //         $url->appendChild($xhtml);
        //     }

        //     $changefreq = $sitemap->createElement('changefreq');

        //     $locText = $sitemap->createTextNode($route);
        //     $locText = $loc->appendChild($locText);

        //     $changefreqText = $sitemap->createTextNode('daily');
        //     $changefreqText = $changefreq->appendChild($changefreqText);
        // }
        $companies = $this->companyRepo->getPublishedCompanies();
        $users = $this->userRepo->getAllUsers();
        foreach ($companies as $company) {
            if(strtolower($company->country->abbreviation) == 'it') {
                $category = $company->category;
                $categoryNameOrigin = $category->getTranslation('it', true)->name;
                $category->defaultCategory =  $category->getTranslation('en', true);
                $categoryName = $categoryNameOrigin ? $categoryNameOrigin : $category->defaultCategory->name;
                
                $categoryName = strtolower($categoryName);
                $categoryName = str_replace('-', '', $categoryName);
                $categoryName = preg_replace('!\s+!', ' ', $categoryName);
                $categoryName = str_replace(' ', '-', $categoryName);
                
                $route = 'https://sharado.com/it/company/'.$categoryName.'/'.$company->name;
                
                $doc = $sitemap->documentElement;
                $url = $sitemap->createElement('url');
                $url = $doc->appendChild($url);

                $loc = $sitemap->createElement('loc');
                $loc = $url->appendChild($loc); 

                
                // foreach ($locales as $locale) {
                //     $xhtml = $sitemap->createElement('xhtml:link');
                //     $rel = $sitemap->createAttribute('rel');
                //     $rel->value = 'alternate';
                //     $hreflang = $sitemap->createAttribute('hreflang');
                //     $href = $sitemap->createAttribute('href');
                //     $hreflang->value = $locale;
                //     $firstSub = 'https://sharado.com/';
                //     $secondSub = 'company'.'/'.$company->name.'/'.$company->country->name;
                //     if($secondSub) {
                //         $href->value = htmlspecialchars($firstSub.$locale).'/'.htmlspecialchars($secondSub);
                //     }else {
                //         $href->value = htmlspecialchars($firstSub).$locale;
                //     }
                //     $xhtml->appendChild($rel);
                //     $xhtml->appendChild($hreflang);
                //     $xhtml->appendChild($href);
                //     $url->appendChild($xhtml);
                // }

                $changefreq = $sitemap->createElement('changefreq');

                $locText = $sitemap->createTextNode($route);
                $locText = $loc->appendChild($locText);

                $changefreqText = $sitemap->createTextNode('daily');
                $changefreqText = $changefreq->appendChild($changefreqText);
            }
            
            
        }

        
        // exec('cd '.public_path('AngularJS-SEO-Article') . '; bash snapshot-commands.sh');
        
        // $sitemap = simplexml_load_file('sitemap.xml');
        

        foreach($routes as $route){
            $doc = $sitemap->documentElement;
            $url = $sitemap->createElement('url');
            $url = $doc->appendChild($url);

            $loc = $sitemap->createElement('loc');
            $loc = $url->appendChild($loc); 

            
            // foreach ($locales as $locale) {
            //     $xhtml = $sitemap->createElement('xhtml:link');
            //     $rel = $sitemap->createAttribute('rel');
            //     $rel->value = 'alternate';
            //     $hreflang = $sitemap->createAttribute('hreflang');
            //     $href = $sitemap->createAttribute('href');
            //     $hreflang->value = $locale;
            //     $firstSub = 'https://sharado.com/';
            //     $secondSub = 'company'.'/'.$company->name.'/'.$company->country->name;
            //     if($secondSub) {
            //         $href->value = htmlspecialchars($firstSub.$locale).'/'.htmlspecialchars($secondSub);
            //     }else {
            //         $href->value = htmlspecialchars($firstSub).$locale;
            //     }
            //     $xhtml->appendChild($rel);
            //     $xhtml->appendChild($hreflang);
            //     $xhtml->appendChild($href);
            //     $url->appendChild($xhtml);
            // }

            $changefreq = $sitemap->createElement('changefreq');

            $locText = $sitemap->createTextNode($route);
            $locText = $loc->appendChild($locText);

            $changefreqText = $sitemap->createTextNode('daily');
            $changefreqText = $changefreq->appendChild($changefreqText);

        }

        foreach ($locales as $locale) {
            $country = $this->countryRepo->getCountryByLanguage($locale);
            if($country) {
                $keyword = '';
                $results = $this->companyRepo->getBrowseJobsGigs($country->latitude, $country->longitude, 0, $country->id, $keyword);
                $companiesCount = $results['count'];
                $count_pages = $companiesCount%10 == 0 ? $companiesCount/10 : floor($companiesCount/10 + 1);
                if($count_pages >= 1) {
                    for ($i=1; $i <= $count_pages; $i++) { 
                            $doc = $sitemap->documentElement;
                            $url = $sitemap->createElement('url');
                            $url = $doc->appendChild($url);

                            $loc = $sitemap->createElement('loc');
                            $loc = $url->appendChild($loc);

                            // $xhtml = $sitemap->createElement('xhtml:link');
                            // $rel = $sitemap->createAttribute('rel');
                            // $rel->value = 'alternate';
                            // $hreflang = $sitemap->createAttribute('hreflang');
                            // $href = $sitemap->createAttribute('href');
                            // $hreflang->value = $locale;
                            $firstSub = 'https://sharado.com/'.$locale.'/gigs?page='.$i;
                            // $href->value = htmlspecialchars($firstSub);

                            // $xhtml->appendChild($rel);
                            // $xhtml->appendChild($hreflang);
                            // $xhtml->appendChild($href);
                            // $url->appendChild($xhtml);
                            $locText = $sitemap->createTextNode($firstSub);
                            $locText = $loc->appendChild($locText);
                     } 

                        
                    
                }
            }

            if($users) {
                foreach($users as $user) {
                    $doc = $sitemap->documentElement;
                    $url = $sitemap->createElement('url');
                    $url = $doc->appendChild($url);

                    $loc = $sitemap->createElement('loc');
                    $loc = $url->appendChild($loc); 

                    $urlText = 'https://sharado.com/'.$locale.'/public-profile/'.$user->username;
                    $locText = $sitemap->createTextNode($urlText);
                    $locText = $loc->appendChild($locText);
                }
            }
            
        }
            $sitemap = $sitemap->save('sitemap.xml');


       
    }
}
