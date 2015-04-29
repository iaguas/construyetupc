from scrapy.spider import BaseSpider
from scrapy.selector import Selector
from scrapy.contrib.spiders import CrawlSpider, Rule
#from scrapy.contrib.linkextractors import LinkExtractor
#from scrapy.contrib.linkextractors.lxmlhtml import LxmlLinkExtractor
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from frags.items import ProcessorItem

class MySpider(CrawlSpider):
	name = "frags-processor"
	allowed_domains = ["4frags.com"]
	start_urls = ["http://www.4frags.com/hardware/procesadores.html?limit=all"]
	
	rules = (
        # Extract links matching '.html' and parse them with the spider's method parse_item
		Rule(SgmlLinkExtractor(allow=('.*\.html',), restrict_xpaths=('//ul[contains(@class, "products-grid")]')), callback='parse_item'),
    )

	def parse_item(self, response):
		#sel = Selector(response)
		item = ProcessorItem()
		item ["pn"] = response.xpath('//div[@class="product-name"]/p/text()').extract()
		item ["name"] = response.xpath('//h4[@class="product-name-view"]/text()').extract()
		item ["family"] = response.xpath('//tr[@id="ProcessorNumber"]/td[@class="rc"]/text()').extract()
		item ["socket"] = ""
		item ["cores"] = response.xpath('//tr[@id="ThreadCount"]/td[@class="rc"]/text()').extract()
		item ["frecuency"] = response.xpath('//tr[@id="ClockSpeed"]/td[@class="rc"]/text()').extract()
		item ["price"] = response.xpath('//div[@class="price-box"]/span/span[@class="price"]/text()').extract()

		if (item ["pn"][0] != ""):
			return item
		else:
			return None
		#item ["price"] = sel.xpath('//a[@class="product-image"]/@href').extract()
		#elems = sel.xpath('//div[contains(@class,"products-name")]/p')
		#print elems
		#items = []
		#for sel in elems:
			#item = FragsItem()
			#item ["name"] = sel.xpath('h2/a/@title').extract()
			#item ["price"] = sel.xpath('div/div/span/span[@class="price"]/text()').extract()
			#items.append(item)
		#return items
		