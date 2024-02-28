<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\SubscribtionInterface;
use App\Contracts\JobInterface;
use App\Contracts\MailInterface;

class Subscribtion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SubscribtionInterface $subscribtionRepo,
                                JobInterface $jobRepo,
                                MailInterface $mailRepo)
    {
        parent::__construct();
        $this->subscribtionRepo = $subscribtionRepo;
        $this->mailRepo = $mailRepo;
        $this->jobRepo = $jobRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('asd');
        // get all subscriptions
        $subscribtions = $this->subscribtionRepo->getSubscribtions();
        foreach ($subscribtions as $key => $subscribtion) {
            try {
                 // foreach subscribtion get details
                $subscribtionData = [
                                    'country' => $subscribtion->country,
                                    'city' => $subscribtion->city,
                                    'keyword' => $subscribtion->keyword,
                                    'latitude' => $subscribtion->latitude,
                                    'longtitude' => $subscribtion->longtitude,
                                    'category_id' => $subscribtion->category_id,
                                    'sector_id' => $subscribtion->sector_id
                                    ];
                // find companies for this subscribtion
                $companies = $this->jobRepo->getJobsBySubscribtionData($subscribtionData);
                foreach ($companies as $company) {
                    // get company's unsent jobs
                    $jobs = $company->jobs->where('is_sent', false);
                    if(count($jobs) > 0){
                        foreach ($jobs as $job) {

                            $isSent = $this->jobRepo->checkIfSent($job->id, $subscribtion->email);
                            
                            if(!$isSent) {
                                $jobUrl = '/#!/'.$subscribtion->code.'/company/'.$company->name.'/'.$company->country->name;
                                // send email notification to subscribtion email
                                $mailData = [
                                            'email' => $subscribtion->email,
                                            'jobUrl' => $jobUrl,
                                            'alertId' => $subscribtion->id,
                                            ];

                                $sendMail = $this->mailRepo->notifyForJob($mailData);

                                //if email successfully sent, update job sent status to true
                                $updateData = ['is_sent' => true];
                                $this->jobRepo->editJob($job, $updateData);
                                $data = ['job_id' => $job->id, 'email' => $subscribtion->email];
                                $this->jobRepo->setJobSent($data);
                            }                             
                        }
                    }else{
                        continue;
                    }
                }

            } catch (\Exception $e) {
                \Log::error($e);
            }
           
        }
    }
}
