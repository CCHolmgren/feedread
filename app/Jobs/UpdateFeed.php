<?php

namespace App\Jobs;

use App\Feed;
use App\FeedItem;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $feed;
    protected $force;
    protected $shouldRepush;
    protected $refreshTime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Feed $feed, $shouldRepush = true, $force = false)
    {
        $this->feed = $feed;
        $this->force = $force;
        $this->shouldRepush = $shouldRepush;
        $this->refreshTime = random_int(60 * 5, 60 * 10);
    }

    protected function repush()
    {
        if($this->shouldRepush) {
            \Log::info('Repushing ' . $this->feed->id . ' with delay ' . $this->refreshTime . ' seconds (~' . round($this->refreshTime / 60) . ' minutes)');
            UpdateFeed::dispatch($this->feed)->delay(Carbon::now()->addSeconds($this->refreshTime));
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->feed->updated !== null && Carbon::parse($this->feed->updated)->diffInSeconds(Carbon::now()) < ($this->refreshTime - 10) && !$this->force) {
            $this->repush();
            return;
        }

        \Log::info('Working on ' . $this->feed->id);

        $feed = new \SimplePie();
        $feed->set_feed_url($this->feed->url);
        $feed->enable_order_by_date(false);
        $feed->enable_cache(false);
        $feed->strip_htmltags(['script']);
        $success = $feed->init();
        $feed->handle_content_type();

        $items = $feed->get_items();

        foreach($items as $item) {
            $fields = [
                'pub_date' => $item->get_date('Y-m-d H:i:s'),
                'author' => $item->get_author() ? $item->get_author()->get_name() : null,
                'guid' => $item->get_id(),
                'title' => $item->get_title(),
                'description' => $item->get_description(),
                'content' => $item->get_content(),
                'link' => $item->get_permalink(),
            ];
            $guid = $fields['guid'];
            if(FeedItem::where('guid', $guid)->count() > 0) {
                unset($fields['pub_date']);
                FeedItem::where('guid', $guid)->first()->fill($fields)->save();
                continue;
            }

            $this->feed->items()->create($fields);
        }

        $this->feed->fill([
            'updated' => Carbon::now()->format('Y-m-d H:i:s')
        ])->save();
        \Log::info('Updated ' . $this->feed->id);
        $this->repush();
    }
}
