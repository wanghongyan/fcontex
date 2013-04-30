<?php echo '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL; ?>
<rss version="2.0">
<channel>
	<title><![CDATA[<?php echo $channel['title']; ?>]]></title>
	<link><?php echo $channel['link']; ?></link>
	<description><![CDATA[<?php echo $channel['description']; ?>]]></description>
	<generator><?php echo $channel['generator']; ?></generator>
	<ttl><?php echo $channel['ttl']; ?></ttl>
	<?php
	foreach ($channel['item'] as $item)
	{
	?>
	<item>
		<link><?php echo $item['link']; ?></link>
		<title><![CDATA[<?php echo $item['title']; ?>]]></title>
		<author><?php echo $item['author']; ?></author>
		<category><![CDATA[<?php echo $item['category']; ?>]]></category>
		<pubDate><?php echo $item['pubDate']; ?></pubDate>
		<description><![CDATA[<a href="<?php echo $item['link']; ?>"><img src="<?php echo $item['cover']; ?>" alt="<?php echo $item['title']; ?>" border="0" /></a><br /><?php echo $item['description']; ?>]]></description>
	</item>
	<?php
	}
	?>
</channel>
</rss>