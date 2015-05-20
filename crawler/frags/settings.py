# -*- coding: utf-8 -*-

# Scrapy settings for frags project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/en/latest/topics/settings.html
#

BOT_NAME = 'frags'

SPIDER_MODULES = ['frags.spiders']
NEWSPIDER_MODULE = 'frags.spiders'

#ITEM_PIPELINES = {'frags.pipelines.JsonWithEncodingPipeline': 1}

# Crawl responsibly by identifying yourself (and your website) on the user-agent
#USER_AGENT = 'frags (+http://www.yourdomain.com)'
