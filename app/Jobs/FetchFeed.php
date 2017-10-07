<?php

namespace App\Jobs;

use App\Feed;
use App\FeedItem;
use Cake\Chronos\Chronos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $feed;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
            'title' => $feed->get_title(),
            'link' => $feed->get_link(),
            'description' => $feed->get_description(),
            'language' => $feed->get_language()
        ])->save();
    }
}
