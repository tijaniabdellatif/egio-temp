<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($data as $d)

        <?php
            $link = '';
            if($d===-1) $link = '/immobilier';
            else if(isset($d->category)&&$d->category){
                $link = '/immobilier/'.$d->category;

                if(isset($d->neighborhood)&&$d->neighborhood){
                    $link .= '/'.$d->neighborhood;
                }

                if(isset($d->city)&&$d->city){
                    $link .= '/'.$d->city;
                }
            }
            else{
                $link = '/immobilier';
                if(isset($d->city)&&$d->city){
                    $link .= '-'.$d->city;
                    if(isset($d->neighborhood)&&$d->neighborhood){
                        $link .= '/'.$d->neighborhood;
                    }
                }
                if(isset($d->type)&&$d->type){
                    $link .= '/'.$d->type.'-type';
                }
            }

        ?>
        @if($link)
        <url>
            <loc>{{ url('/') . $link }}</loc>
            <changefreq>always</changefreq>
            <priority>0.9</priority>
        </url>
        @endif
    @endforeach
</urlset>
