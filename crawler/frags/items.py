# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy

class ProcessorItem(scrapy.Item):
    # define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	family = scrapy.Field()
	socket = scrapy.Field()
	cores = scrapy.Field()
	frecuency = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class MemoryItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	frecuency = scrapy.Field()
	modules = scrapy.Field()
	size = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class GPUItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	memory = scrapy.Field()
	frecuency = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class CPUCoolerItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	size = scrapy.Field()
	rpm = scrapy.Field()
	noise = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class MotherboardItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	socket = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()

class PowerSupplyItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	eficiency = scrapy.Field()
	watts = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class CaseItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	format = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class OpticalDriveItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class StorageItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	type = scrapy.Field()
	capacity = scrapy.Field()
	format = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()
	
class MonitorItem(scrapy.Item):
	# define the fields for your item here like:
	pn = scrapy.Field()
	name = scrapy.Field()
	resolution = scrapy.Field()
	size = scrapy.Field()
	prices = scrapy.Field()
	img = scrapy.Field()