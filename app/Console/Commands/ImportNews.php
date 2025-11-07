<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Illuminate\Support\Str;

class ImportNews extends Command
{
    protected $signature = 'news:import';
    protected $description = 'Import tin tức từ RSS feed vào bảng post (có thumbnail lưu cục bộ)';

    public function handle()
    {
        $rssUrl = 'https://vnexpress.net/rss/tin-moi-nhat.rss';
        $xml = @simplexml_load_file($rssUrl, "SimpleXMLElement", LIBXML_NOCDATA);
        if (!$xml) {
            $this->error('Không tải được RSS feed!');
            return;
        }

        $items = $xml->channel->item;

        // Tạo thư mục nếu chưa có
        $dir = public_path('assets/images/post');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($items as $item) {
            $title = (string) $item->title;
            $link = (string) $item->link;
            $content = (string) $item->description;
            $pubDate = date('Y-m-d H:i:s', strtotime((string) $item->pubDate));

            // Tránh trùng lặp theo slug hoặc link
            if (Post::where('slug', Str::slug($title))->orWhere('description', $link)->exists()) {
                continue;
            }

            // Lấy thumbnail: media:content trước, nếu không có mới lấy từ description
            $thumbnail = null;
            $imageUrl = null;

            // 1. media:content
            if (isset($item->children('media', true)->content)) {
                $media = $item->children('media', true)->content;
                $imageUrl = (string) $media->attributes()->url;
            }

            // 2. Nếu không có, thử regex <img> trong description
            if (!$imageUrl && preg_match('/<img.*?src=["\'](.*?)["\']/', $content, $matches)) {
                $imageUrl = $matches[1];
            }

            // Download ảnh nếu có
            if (!empty($imageUrl)) {
                try {
                    $imageContents = file_get_contents($imageUrl);
                    $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    if (!in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp'])) {
                        $ext = 'jpg';
                    }
                    $imageName = time() . '_' . Str::random(6) . '.' . $ext;
                    file_put_contents($dir . '/' . $imageName, $imageContents);
                    $thumbnail = $imageName;
                } catch (\Exception $e) {
                    $this->warn("Không download được ảnh: {$imageUrl}");
                }
            }

            // Lấy detail sạch HTML, limit 500 ký tự
            $detailText = strip_tags($content);
            $detailText = html_entity_decode($detailText, ENT_QUOTES | ENT_HTML5);
            $detailText = Str::limit($detailText, 500);

            Post::create([
                'topic_id' => rand(1, 3), // topic random 1–3
                'title' => $title,
                'slug' => Str::slug($title),
                'detail' => $detailText,
                'thumbnail' => $thumbnail,
                'type' => 'post',
                'description' => $link, // lưu link nguồn
                'created_by' => 1,
                'updated_by' => null,
                'status' => true,
            ]);
        }

        $this->info('Đã import xong tin tức với thumbnail và topic random!');
    }
}
