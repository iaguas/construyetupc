from scrapy.spider import BaseSpider
from scrapy.selector import Selector
from scrapy.contrib.spiders import CrawlSpider, Rule
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from frags.items import MonitorItem

class MySpider(CrawlSpider):
	name = "frags-monitor"
	allowed_domains = ["4frags.com"]
	start_urls = ["http://www.4frags.com/perifericos/monitores.html?limit=all"]
	
	rules = (
        # Extract links matching '.html' and parse them with the spider's method parse_item
		Rule(SgmlLinkExtractor(allow=('.*\.html',), restrict_xpaths=('//ul[contains(@class, "products-grid")]')), callback='parse_item'),
    )

	def parse_item(self, response):
		item = MonitorItem()
		item ["pn"] = response.xpath('//div[@class="product-name"]/p/text()').extract()
		item ["name"] = response.xpath('//h4[@class="product-name-view"]/text()').extract()
		item ["resolution"] = ""
		item ["size"] = ""
		item ["prices"] = {}
		item ["prices"]["provider"] = "4frags"
		item ["prices"]["price"] = response.xpath('//div[@class="price-box"]/span/span[@class="price"]/text()').extract()
		item ["prices"]["delivery-fare"] = ""
		item ["prices"]["url"] = response.url
		item ["img"] = response.xpath('//p[contains(@class, "product-image")]/a/img/@src').extract()

		if (item ["pn"][0] != ""):
			return item
		else:
			return None