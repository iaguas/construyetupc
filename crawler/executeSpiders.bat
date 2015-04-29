@echo off
cls
SET /P uname=Quieres ejecutar el script? (consulta a Kevin) [Y/N]: 
IF "%uname%"=="y" GOTO Confirm
IF "%uname%"=="Y" GOTO Confirm
GOTO End

:Confirm
scrapy crawl frags-processor -o cpus.json
scrapy crawl frags-powerSupply -o powerSupplies.json
scrapy crawl frags-opticalDrive -o opticalDrives.json
scrapy crawl frags-motherboard -o motherboards.json
scrapy crawl frags-monitor -o monitors.json
scrapy crawl frags-memory -o memories.json
scrapy crawl frags-gpu -o gpus.json
scrapy crawl frags-CPUCooler -o cpuCoolers.json
scrapy crawl frags-case -o cases.json
scrapy crawl frags-storage -o storages.json

:End